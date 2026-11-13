<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class QrisController extends Controller
{
    public function index(Request $request)
    {
        // Tandai pembayaran pending yang sudah melewati batas waktu.
        Payment::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        // Statistik transaksi.
        $receivedToday = Payment::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('amount');

        $receivedTodayCount = Payment::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->count();

        $pendingAmount = Payment::where('status', 'pending')
            ->sum('amount');

        $pendingCount = Payment::where('status', 'pending')->count();

        $expiredTodayCount = Payment::where('status', 'expired')
            ->whereDate('updated_at', today())
            ->count();

        $statusCounts = [
            'all' => Payment::count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'pending' => $pendingCount,
            'expired' => Payment::where('status', 'expired')->count(),
        ];

        // Ambil transaksi untuk tabel.
        $query = Payment::with(['order.user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->date === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($request->date === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($request->date === '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $qrisTransactions = $query
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('admin.qris', compact(
            'qrisTransactions',
            'receivedToday',
            'receivedTodayCount',
            'pendingAmount',
            'pendingCount',
            'expiredTodayCount',
            'statusCounts'
        ));
    }
}
