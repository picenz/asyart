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
        // Validasi input form checkout
        $validatedData = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        $shippingCost = (int) $request->shipping_cost;
        $deliveryMethod = $request->delivery_method;
        $orderNumber = 'RN-' . rand(10000, 99999);

        // Ambil data keranjang dari request atau JavaScript localStorage (dikirim via input hidden)
        $cartDataJson = $request->input('cart_data', '[]');

        // Simpan semua data penting ke session sementara (database diisi pas pembayaran sukses)
        session([
            'order_number' => $orderNumber,
            'address_data' => $validatedData,
            'delivery_method' => $deliveryMethod,
            'shipping_cost' => $shippingCost,
            'cart_data' => $cartDataJson,
        ]);

        // Arahkan ke halaman pembayaran QRIS
        return redirect()->route('payment.page');
    }
}
