<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil semua data pesanan, urutkan dari yang terbaru
        $orders = Order::latest()->get();

        return view('admin.orders', compact('orders'));
    }
}
