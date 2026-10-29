<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function store(Request $request)
    {
        // Validasi input: Kolom penting wajib diisi, apartment opsional (nullable)
        $validatedData = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'apartment' => 'nullable|string|max:255', // Bersifat opsional
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|string',
            'shipping_cost' => 'required|numeric', // Tangkap ongkir dari hidden input
        ]);

        // Simulasi subtotal produk (kalau datanya dari database/cart backend, sesuaikan ya)
        // Di sini kita set dummy subtotal atau bisa disesuaikan sama sistem keranjang lu
        $subtotal = 85000;
        $shippingCost = (int) $request->shipping_cost;
        $totalPayment = $subtotal + $shippingCost;

        // Simpan data pesanan sementara ke session biar bisa dibaca di halaman payment
        session([
            'checkout_data' => $validatedData,
            'order_summary' => [
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $totalPayment
            ]
        ]);

        // Kalau nanti model Order udah siap, lu bisa simpan ke database di sini:
        // $order = Order::create(array_merge($validatedData, ['total' => $totalPayment]));

        // Setelah lolos validasi, arahkan ke halaman pembayaran (payment)
        return redirect()->route('payment.page');
    }
}
