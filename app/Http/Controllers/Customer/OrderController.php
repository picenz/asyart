<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Orders::where('user_id', $request->user()->id)
            ->with([
                'items.product.images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                }
            ])
            ->latest()
            ->paginate(5);

        return view('orders', compact('orders'));
    }
}
