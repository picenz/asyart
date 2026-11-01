<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function store(Request $request)
    {
        // 1. Validasi data checkout
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:Standard delivery,Express delivery',
            'cart_data' => 'required|json',
        ]);

        // 2. Ambil data cart dari frontend
        $cart = json_decode($validatedData['cart_data'], true);

        if (!is_array($cart) || count($cart) === 0) {
            throw ValidationException::withMessages([
                'cart_data' => 'Keranjang belanja kosong.',
            ]);
        }

        // 3. Tentukan ongkir dari server
        $shippingCost = $validatedData['delivery_method'] === 'Express delivery'
            ? 20000
            : 0;
        $tax = round($subtotal * 0.11);
        $total = $subtotal + $shippingCost + $tax;

        // 4. Simpan order dan detail order ke database
        $result = DB::transaction(function () use (
            $request,
            $validatedData,
            $cart,
            $shippingCost
        ) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                if (
                    empty($item['product_id']) ||
                    empty($item['variant_id']) ||
                    !isset($item['qty']) ||
                    !is_numeric($item['qty']) ||
                    (int) $item['qty'] < 1
                ) {
                    throw ValidationException::withMessages([
                        'cart_data' => 'Data keranjang tidak valid.',
                    ]);
                }

                // Ambil variant dari database
                $variant = ProductVariant::with('product')
                    ->where('id', $item['variant_id'])
                    ->where('product_id', $item['product_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$variant ||
                    !$variant->product ||
                    !$variant->product->is_active
                ) {
                    throw ValidationException::withMessages([
                        'cart_data' => 'Salah satu produk sudah tidak tersedia.',
                    ]);
                }

                $quantity = (int) $item['qty'];

                // Cek ketersediaan stok
                if ($variant->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart_data' => 'Stok ' . $variant->product->name .
                            ' (' . $variant->color . ' - ' . $variant->size .
                            ') tidak mencukupi.',
                    ]);
                }

                // Harga diambil dari database, bukan localStorage
                $price = (float) $variant->price;
                $itemSubtotal = $price * $quantity;

                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                    'total_price' => $total
                ];
            }

            // 5. Hitung total pembayaran
            $totalPayment = $subtotal + $shippingCost;

            // 6. Buat data order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'email' => $validatedData['email'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'address' => $validatedData['address'],
                'apartment' => $validatedData['apartment'] ?? null,
                'city' => $validatedData['city'],
                'postal_code' => $validatedData['postal_code'],
                'province' => $validatedData['province'],
                'phone' => $validatedData['phone'],
                'delivery_method' => $validatedData['delivery_method'],
                'total_price' => $totalPayment,
                'status' => 'pending',
            ]);

            // 7. Simpan setiap item ke order_items
            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
            }

            // Kembalikan hasil transaction
            return [
                'order' => $order,
                'subtotal' => $subtotal,
            ];
        });

        // 8. Ambil hasil transaction
        $order = $result['order'];
        $subtotal = $result['subtotal'];

        // 9. Simpan ringkasan pembayaran ke session
        session([
            'checkout_data' => $validatedData,
            'order_summary' => [
                'order_id' => $order->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $total,
            ],
        ]);

        // 10. Arahkan ke halaman pembayaran
        return redirect()->route('payment.page');
    }
}
