<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Orders;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $items = CartItem::with(['product.images', 'variant'])
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->variant;
                $image = $product?->images->firstWhere('is_primary', true)
                    ?? $product?->images->first();

                return [
                    'id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->product_variant_id,
                    'nama' => $product?->name ?? 'Produk',
                    'harga' => (float) ($variant?->price ?? 0),
                    'gambar' => $image ? asset($image->image_path) : '',
                    'warna' => $variant?->color ?? '',
                    'ukuran' => $variant?->size ?? '',
                    'qty' => $cartItem->quantity,
                    'stock' => $variant?->stock ?? 0,
                ];
            });

        if ($items->isEmpty()) {
            return redirect()->route('cart.page')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        return view('checkout', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:2000',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:Standard delivery,Express delivery',
        ]);

        $userId = $request->user()->id;
        $shippingCost = $data['delivery_method'] === 'Express delivery'
            ? 20000
            : 0;

        $order = DB::transaction(function () use ($userId, $data, $shippingCost) {
            $cartItems = CartItem::where('user_id', $userId)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang belanja masih kosong.',
                ]);
            }

            $subtotal = 0;
            $orderLines = [];
            $lockedVariants = [];

            foreach ($cartItems as $cartItem) {
                $variant = ProductVariant::with('product')
                    ->whereKey($cartItem->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$variant ||
                    !$variant->is_active ||
                    !$variant->product?->is_active
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Ada produk yang sudah tidak tersedia. Periksa keranjang kembali.',
                    ]);
                }

                $qty = (int) $cartItem->quantity;

                if ($qty < 1 || $qty > $variant->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok produk tidak cukup untuk jumlah yang diminta.',
                    ]);
                }

                $price = (float) $variant->price;
                $lineSubtotal = $price * $qty;
                $subtotal += $lineSubtotal;

                $orderLines[] = [
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $lineSubtotal,
                ];

                $lockedVariants[] = [$variant, $qty];
            }

            $tax = (int) round($subtotal * 0.11);
            $total = $subtotal + $shippingCost + $tax;

            $order = Orders::create([
                'user_id' => $userId,
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'apartment' => $data['apartment'] ?? null,
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'province' => $data['province'],
                'phone' => $data['phone'],
                'delivery_method' => $data['delivery_method'],
                'total_price' => $total,
                'status' => 'pending',
            ]);

            Payment::create([
                'order_id' => $order->id,
                'reference' => 'QRIS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8)),
                'payment_method' => 'QRIS',
                'amount' => $order->total_price,
                'status' => 'pending',
                'expires_at' => $order->created_at->copy()->addMinutes(15),
            ]);

            foreach ($orderLines as $line) {
                $order->items()->create($line);
            }

            foreach ($lockedVariants as [$variant, $qty]) {
                $variant->decrement('stock', $qty);
            }

            CartItem::where('user_id', $userId)->delete();

            return $order;
        });

        session(['asyart_pending_order_id' => $order->id]);

        return redirect()->route('payment');
    }

    public function paymentPage(Request $request)
    {
        $orderId = session('asyart_pending_order_id');

        abort_unless($orderId, 404);

$order = Orders::with(['items', 'payment'])            ->where('user_id', $request->user()->id)
            ->findOrFail($orderId);

        $subtotal = (float) $order->items->sum('subtotal');

        $shippingCost = $order->delivery_method === 'Express delivery'
            ? 20000
            : 0;

        $total = (float) $order->total_price;
        $tax = max(0, $total - $subtotal - $shippingCost);
        $payment = $order->payment;

        abort_unless($payment, 404);

        $expiresAt = $payment->expires_at;
        $isExpired = $payment->status === 'expired'
            || now()->greaterThanOrEqualTo($expiresAt);
        return view('payment', compact(
            'order',
            'payment',
            'subtotal',
            'shippingCost',
            'tax',
            'total',
            'expiresAt',
            'isExpired'
        ));
    }

    /**
     * Demo only. Ini bukan verifikasi pembayaran QRIS sungguhan.
     */
    public function demoPaymentSuccess(Request $request)
    {
        $orderId = session('asyart_pending_order_id');

        abort_unless($orderId, 404);

        Orders::where('user_id', $request->user()->id)
            ->findOrFail($orderId);

        return redirect()->route('payment')
            ->with(
                'warning',
                'Demo saja: pembayaran belum diverifikasi oleh payment gateway.'
            );
    }
}
