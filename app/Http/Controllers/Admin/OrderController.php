<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Orders::latest();

        if ($status == 'awaiting') {
            $query->whereIn('status', ['Awaiting payment', 'Processing']);
        } elseif ($status == 'paid') {
            $query->where('status', 'Paid');
        } elseif ($status == 'shipped') {
            $query->where('status', 'Shipped');
        } elseif ($status == 'completed') {
            $query->where('status', 'Completed');
        }

        $orders = $query->get();

        // Hitung total untuk badge tab
        $counts = [
            'all' => Orders::count(),
            'awaiting' => Orders::whereIn('status', ['Awaiting payment', 'Processing'])->count(),
            'paid' => Orders::where('status', 'Paid')->count(),
            'shipped' => Orders::where('status', 'Shipped')->count(),
            'completed' => Orders::where('status', 'Completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $order = Orders::findOrFail($id);

        // Parsing data item JSON supaya gampang dipanggil di view detail
        $orderItems = json_decode($order->items_json, true) ?? [];

        return view('admin.orders.detail', compact('order', 'orderItems'));
    }

    public function markAsShipped($id)
    {
        $order = Orders::findOrFail($id);
        $order->update(['status' => 'Shipped']);

        return redirect()->route('admin.orders.detail', $id)->with('success', 'Order marked as shipped.');
    }

    public function export()
    {
        // Logika export CSV bisa ditaruh sini
        return back();
    }

    public function markAsCompleted($id)
    {
        $order = Orders::findOrFail($id);
        $order->update(['status' => 'Completed']);

        return redirect()->route('admin.orders.detail', $id)->with('success', 'Order marked as completed.');
    }
}
