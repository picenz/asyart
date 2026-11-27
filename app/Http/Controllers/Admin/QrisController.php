<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrisController extends Controller
{
    public function index(Request $request)
    {
        // Expired hanya jika customer belum mengunggah bukti pembayaran.
        Payment::where('status', 'pending')
            ->whereNull('proof_path')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $receivedToday = Payment::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('amount');

            $receivedTodayCount = Payment::where('status', 'paid')
    ->whereDate('paid_at', today())->count();

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
            'rejected' => Payment::where('status', 'rejected')->count(),
        ];

        $query = Payment::with(['order.user']);

        $status = $request->query('status', 'all');

        if ($status !== 'all' && array_key_exists($status, $statusCounts)) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
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

        $dateRange = $request->query('date_range');

        if ($dateRange === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($dateRange === '7_days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($dateRange === '30_days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $qrisTransactions = $query->latest()
            ->paginate(8)
            ->withQueryString();

      return view('admin.qris', compact(
    'receivedToday',
    'receivedTodayCount',
    'pendingAmount',
    'pendingCount',
    'expiredTodayCount',
    'statusCounts',
    'qrisTransactions'
));
    }

    public function show($id)
    {
        $transaction = Payment::with([
            'order.user',
            'order.items',
        ])->findOrFail($id);

        return view('admin.qris-detail', compact('transaction'));
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $payment = Payment::with('order')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($payment->status !== 'pending') {
                abort(422, 'Transaksi ini tidak berstatus pending.');
            }

            if (empty($payment->proof_path)) {
                abort(422, 'Customer belum mengunggah bukti pembayaran.');
            }

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'verified_at' => now(),
                'rejection_note' => null,
            ]);

            // Jangan mengubah status order yang sudah dikirim atau selesai.
            if ($payment->order && strtolower($payment->order->status) === 'pending') {
                $payment->order->update([
                    'status' => 'Paid',
                ]);
            }
        });

        return redirect()
            ->route('admin.qris.detail', $id)
            ->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_note' => ['required', 'string', 'max:1000'],
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak berstatus pending.');
        }

        if (empty($payment->proof_path)) {
            return back()->with('error', 'Belum ada bukti pembayaran untuk ditolak.');
        }

        $payment->update([
            'status' => 'rejected',
            'rejection_note' => $validated['rejection_note'],
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.qris.detail', $id)
            ->with('success', 'Pembayaran ditolak. Customer dapat mengunggah bukti baru.');
    }
}
