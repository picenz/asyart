@extends('layouts.admin', ['title' => 'Admin - QRIS Transactions'])

@section('content')
    <div class="space-y-6">
        <!-- PAGE HEADER & EXPORT CSV -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">QRIS Transactions</h1>
                <p class="text-xs text-gray-500 mt-0.5">Every QR code generated at checkout. Statuses update automatically.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.orders.export') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        <!-- STATS CARDS (3 KOTAK RINGKASAN) -->
        <!-- STATS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Received Today -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
        <p class="text-xs font-semibold text-gray-500">Received today</p>
        <h3 class="text-2xl font-bold tracking-tight text-gray-900">
            Rp{{ number_format($receivedToday, 0, ',', '.') }}
        </h3>
        <p class="text-[11px] text-gray-400">
            {{ $receivedTodayCount }} payments
        </p>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
        <p class="text-xs font-semibold text-gray-500">Pending</p>
        <h3 class="text-2xl font-bold tracking-tight text-gray-900">
            Rp{{ number_format($pendingAmount, 0, ',', '.') }}
        </h3>
        <p class="text-[11px] text-gray-400">
            {{ $pendingCount }} QR waiting for payment
        </p>
    </div>

    <!-- Expired Today -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
        <p class="text-xs font-semibold text-gray-500">Expired today</p>
        <h3 class="text-2xl font-bold tracking-tight text-gray-900">
            {{ $expiredTodayCount }}
        </h3>
        <p class="text-[11px] text-gray-400">Not paid within 15 minutes</p>
    </div>
</div>

        <!-- FILTER TABS & SEARCH / DATE BAR -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

          <!-- Tab Navigasi Status -->
<div class="px-6 pt-4 border-b border-gray-100 flex items-center gap-6 text-xs font-medium text-gray-500 overflow-x-auto">

    @php
        $currentStatus = request('status', 'all');
        $tabs = [
            'all' => 'All',
            'paid' => 'Paid',
            'pending' => 'Pending',
            'expired' => 'Expired',
        ];
    @endphp

    @foreach ($tabs as $key => $label)
        <a href="{{ route('admin.qris.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
            class="pb-3 border-b-2 flex items-center gap-1.5 whitespace-nowrap
            {{ $currentStatus === $key ? 'border-black text-black font-semibold' : 'border-transparent hover:text-gray-900' }}">

            {{ $label }}

            <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">
                {{ $statusCounts[$key] }}
            </span>
        </a>
    @endforeach

</div>x
            <!-- Search & Date Filter Input -->
          <!-- Search & Date Filter Input -->
<form method="GET" action="{{ route('admin.qris.index') }}"
    class="p-4 sm:p-6 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">

    <input type="hidden" name="status" value="{{ request('status', 'all') }}">

    <div class="w-full sm:w-80 relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>

        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search reference or order ID"
            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
    </div>

    <div class="w-full sm:w-auto flex items-center gap-2">
        <div class="relative w-full sm:w-auto">
            <select name="date" onchange="this.form.submit()"
                class="w-full sm:w-auto px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:border-black appearance-none pr-8">

                <option value="7days" {{ request('date', '7days') === '7days' ? 'selected' : '' }}>
                    Date: Last 7 days
                </option>

                <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>
                    Date: Today
                </option>

                <option value="30days" {{ request('date') === '30days' ? 'selected' : '' }}>
                    Date: Last 30 days
                </option>
            </select>

            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>

        <button type="submit"
            class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800">
            Search
        </button>
    </div>
</form>

            <!-- TABEL UTAMA TRANSAKSI QRIS -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="border-b border-gray-100 text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50/30">
                            <th class="py-3 px-6 font-medium">Reference</th>
                            <th class="py-3 px-4 font-medium">Order</th>
                            <th class="py-3 px-4 font-medium">Customer</th>
                            <th class="py-3 px-4 font-medium">Amount</th>
                            <th class="py-3 px-4 font-medium">Created</th>
                            <th class="py-3 px-4 font-medium">Paid / expiry</th>
                            <th class="py-3 px-6 font-medium">Status</th>
                        </tr>
                    </thead>

<tbody class="divide-y divide-gray-100 text-gray-800">
    @forelse($qrisTransactions as $trx)
        @php
            $order = $trx->order;
            $customerName = trim(
                ($order->first_name ?? '') . ' ' . ($order->last_name ?? '')
            );

            $orderNumber = $order
                ? '#FN-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)
                : '-';

            $dateInfo = $trx->status === 'paid' && $trx->paid_at
                ? 'Paid ' . $trx->paid_at->format('H:i')
                : 'Expires ' . ($trx->expires_at
                    ? $trx->expires_at->format('H:i')
                    : '-');
        @endphp

        <tr class="hover:bg-gray-50/50 transition">
            <td class="py-3.5 px-6 font-medium text-gray-900">
                <a href="{{ route('admin.qris.detail', $trx->id) }}"
                    class="text-black hover:underline font-semibold">
                    {{ $trx->reference }}
                </a>
            </td>

            <td class="py-3.5 px-4 font-semibold text-gray-900">
                {{ $orderNumber }}
            </td>

            <td class="py-3.5 px-4 text-gray-600">
                {{ $customerName ?: ($order->email ?? '-') }}
            </td>

            <td class="py-3.5 px-4 font-semibold">
                Rp{{ number_format((float) $trx->amount, 0, ',', '.') }}
            </td>

            <td class="py-3.5 px-4 text-gray-500">
                {{ $trx->created_at?->format('d M, H:i') ?? '-' }}
            </td>

            <td class="py-3.5 px-4 text-gray-500">
                {{ $dateInfo }}
            </td>

            <td class="py-3.5 px-6">
                @if ($trx->status === 'paid')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Paid
                    </span>
                @elseif ($trx->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Expired
                    </span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="py-10 text-center text-sm text-gray-500">
                No QRIS transactions found.
            </td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>


<!-- FOOTER PAGINATION -->
<div
    class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">

    <p class="text-xs text-gray-500">
        Showing {{ $qrisTransactions->firstItem() ?? 0 }}–{{ $qrisTransactions->lastItem() ?? 0 }}
        of {{ $qrisTransactions->total() }} transactions
    </p>

    <div>
        {{ $qrisTransactions->onEachSide(1)->links() }}
    </div>
</div>


        </div>
    </div>
@endsection
