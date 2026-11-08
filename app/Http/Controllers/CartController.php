<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{

    public function index(Request $request)
    {
        $items = CartItem::with([
            'product.images',
            'product.variants',
            'variant',
        ])
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
                    'gambar' => $image
                        ? asset($image->image_path)
                        : '',
                    'warna' => $variant?->color ?? '',
                    'ukuran' => $variant?->size ?? '',
                    'qty' => $cartItem->quantity,
                    'stock' => $variant?->stock ?? 0,

                    // Semua pilihan varian aktif dari database
                    'variants' => $product?->variants
                        ->where('is_active', true)
                        ->map(fn($item) => [
                            'id' => $item->id,
                            'color' => $item->color,
                            'size' => $item->size,
                            'price' => (float) $item->price,
                            'stock' => $item->stock,
                        ])
                        ->values()
                        ->all() ?? [],
                ];
            });

        return view('cart', compact('items'));
    }


    private function getUserCart(Request $request)
    {
        return CartItem::with(['product', 'variant'])
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function count(Request $request)
{
    $count = CartItem::where('user_id', $request->user()->id)
        ->count();

    return response()->json([
        'count' => $count,
    ]);
}

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|integer|min:1',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);

        DB::transaction(function () use ($request, $data, $quantity) {
            $variant = ProductVariant::with('product')->whereKey($data['variant_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (
                !$variant ||
                !$variant->product ||
                !$variant->product->is_active
            ) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Produk atau varian tidak tersedia.',
                ]);
            }

            $cartItem = CartItem::where('user_id', $request->user()->id)
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->first();

            $newQuantity = $quantity + ($cartItem?->quantity ?? 0);

            if ($newQuantity > $variant->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah melebihi stok yang tersedia.',
                ]);
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                ]);
            }
        });

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'count' => CartItem::where('user_id', $request->user()->id)
                ->count(),
        ]);
    }

    public function updateVariant(Request $request, CartItem $cartItem)
{
    $data = $request->validate([
        'variant_id' => 'required|integer|min:1',
    ]);

    abort_unless(
        $cartItem->user_id === $request->user()->id,
        404
    );

    DB::transaction(function () use ($request, $cartItem, $data) {
        $cartItem = CartItem::whereKey($cartItem->id)
            ->where('user_id', $request->user()->id)
            ->lockForUpdate()
            ->firstOrFail();

        $variant = ProductVariant::with('product')
            ->whereKey($data['variant_id'])
            ->lockForUpdate()
            ->first();

        if (
            !$variant ||
            !$variant->is_active ||
            !$variant->product ||
            !$variant->product->is_active ||
            $variant->product_id !== $cartItem->product_id
        ) {
            throw ValidationException::withMessages([
                'variant_id' => 'Varian produk tidak tersedia.',
            ]);
        }

        $existingItem = CartItem::where('user_id', $request->user()->id)
            ->where('product_variant_id', $variant->id)
            ->where('id', '!=', $cartItem->id)
            ->lockForUpdate()
            ->first();

        $newQuantity = $cartItem->quantity
            + ($existingItem?->quantity ?? 0);

        if ($newQuantity > $variant->stock) {
            throw ValidationException::withMessages([
                'variant_id' => 'Stok varian tidak mencukupi.',
            ]);
        }

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $newQuantity,
            ]);

            $cartItem->delete();
        } else {
            $cartItem->update([
                'product_variant_id' => $variant->id,
            ]);
        }
    });

    return response()->json([
        'message' => 'Varian berhasil diperbarui.',
    ]);
}

    public function update(Request $request, CartItem $cartItem)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        abort_unless(
            $cartItem->user_id === $request->user()->id,
            404
        );

        DB::transaction(function () use ($request, $cartItem, $data) {
            $variant = ProductVariant::with('product')->whereKey(
                $cartItem->product_variant_id
            )->lockForUpdate()->firstOrFail();

            if (!$variant->is_active || !$variant->product?->is_active) {
                throw ValidationException::withMessages([
                    'quantity' => 'Produk sudah tidak tersedia.',
                ]);
            }

            if ($data['quantity'] > $variant->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah melebihi stok yang tersedia.',
                ]);
            }

            $cartItem->update(['quantity' => $data['quantity']]);
        });

        return response()->json(['message' => 'Jumlah berhasil diperbarui.']);
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        abort_unless(
            $cartItem->user_id === $request->user()->id,
            404
        );

        $cartItem->delete();

        return response()->json(['message' => 'Item berhasil dihapus.']);
    }

    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Keranjang berhasil dikosongkan.']);
    }
}
