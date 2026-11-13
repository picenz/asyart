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
        $search = $request->query('search');
        $dateRange = $request->query('date_range', '30_days');

        $query = Orders::withSum('items', 'quantity')->latest();

        // Filter status
        if ($status === 'awaiting') {
            $query->whereIn('status', [
                'pending',
                'Awaiting payment',
                'Processing',
            ]);
        } elseif ($status === 'paid') {
            $query->where('status', 'Paid');
        } elseif ($status === 'shipped') {
            $query->where('status', 'Shipped');
        } elseif ($status === 'completed') {
            $query->where('status', 'Completed');
        }

        // Search ID pesanan, nama, atau email pelanggan
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($dateRange === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($dateRange === '7_days') {
            $query->where('created_at', '>=', now()->subDays(7)->startOfDay());
        } elseif ($dateRange === '30_days') {
            $query->where('created_at', '>=', now()->subDays(30)->startOfDay());
        }

        $orders = $query->get();

        // Hitung jumlah pesanan untuk badge tab
        $counts = [
            'all' => Orders::count(),

            'awaiting' => Orders::whereIn('status', [
                'pending',
                'Awaiting payment',
                'Processing',
            ])->count(),

            'paid' => Orders::where('status', 'Paid')->count(),
            'shipped' => Orders::where('status', 'Shipped')->count(),
            'completed' => Orders::where('status', 'Completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

   public function show($id)
{
    $order = Orders::with('items.product', 'items.variant')
        ->findOrFail($id);

    $orderItems = $order->items;

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

    public function updateShipment(Request $request, $id)
{
    $validated = $request->validate([
        'courier' => 'required|string|max:100',
        'tracking_number' => 'required|string|max:100',
        'shipped_at' => 'required|date',
    ]);

    $order = Orders::findOrFail($id);

    $order->update([
        'courier' => $validated['courier'],
        'tracking_number' => $validated['tracking_number'],
        'shipped_at' => $validated['shipped_at'],
        'status' => 'Shipped',
    ]);

    return redirect()
        ->route('admin.orders.detail', $order->id)
        ->with('success', 'Shipment details updated successfully.');
}
}
