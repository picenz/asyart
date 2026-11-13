<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $allowedPeriods = ['today', 'yesterday', '7days', '30days'];

        $period = $request->query('period', 'today');

        if (!in_array($period, $allowedPeriods)) {
            $period = 'today';
        }

        $now = now();

        switch ($period) {
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $periodLabel = 'Yesterday';
                break;

            case '7days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Last 7 days';
                break;

            case '30days':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Last 30 days';
                break;

            default:
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Today';
                break;
        }

        // Revenue sesuai periode yang dipilih
        $revenueToday = Orders::whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->whereIn('status', [
                'Paid',
                'Processing',
                'Shipped',
                'Completed',
            ])
            ->sum('total_price');

        // Order yang sudah dibayar dan menunggu pengiriman
        $ordersToShip = Orders::whereIn('status', [
            'Paid',
            'Processing',
        ])->count();

        // Order yang masih menunggu pembayaran
        $awaitingPayment = Orders::whereIn('status', [
            'pending',
            'Awaiting payment',
        ])->count();

        // Varian aktif dengan stok 3 atau kurang
        $lowStockCount = ProductVariant::where('is_active', true)
            ->where('stock', '<=', 3)
            ->count();

        $lowStockProducts = ProductVariant::with('product')
            ->where('is_active', true)
            ->where('stock', '<=', 3)
            ->orderBy('stock')
            ->take(3)
            ->get();

        // Lima order terbaru dalam periode yang dipilih
        $recentOrders = Orders::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'revenueToday',
            'ordersToShip',
            'awaitingPayment',
            'lowStockCount',
            'lowStockProducts',
            'recentOrders',
            'period',
            'periodLabel'
        ));
    }
}
