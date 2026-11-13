@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
    <div>
        <!-- Judul & Tanggal -->
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Good morning, Asyart</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Here's what's happening in your store today — {{ now()->format('D, d M Y') }}.
                </p>
            </div>

<div>
    <form method="GET" action="{{ route('admin.dashboard') }}">
        <div class="relative flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4v-4m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>

            <select
                name="period"
                onchange="this.form.submit()"
                class="appearance-none bg-transparent outline-none cursor-pointer pr-4 text-xs font-medium text-gray-700"
            >
                <option value="today" @selected($period === 'today')>Today</option>
                <option value="yesterday" @selected($period === 'yesterday')>Yesterday</option>
                <option value="7days" @selected($period === '7days')>Last 7 days</option>
                <option value="30days" @selected($period === '30days')>Last 30 days</option>
            </select>

            <svg class="w-3 h-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </form>
</div>

        </div>

        <!-- 4 KARTU STATISTIK ATAS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Revenue Today -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500">Revenue today</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-4">
<h3 class="text-xl font-bold tracking-tight">
    Rp{{ number_format($revenueToday, 0, ',', '.') }}
</h3>                    <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                        <span>↗ +12% vs yesterday</span>
                    </p>
                </div>
            </div>

            <!-- Orders to Ship -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500">{{ $ordersToShip }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-bold tracking-tight">6</h3>
                    <p class="text-xs text-gray-500 mt-1">Paid and waiting to be shipped</p>
                </div>
            </div>

            <!-- Awaiting Payment -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500">{{ $awaitingPayment }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-bold tracking-tight">1</h3>
                    <p class="text-xs text-gray-500 mt-1">QRIS not paid yet</p>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500">{{ $lowStockCount }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-bold tracking-tight">3</h3>
                    <p class="text-xs text-red-600 font-medium mt-1">Variants with 3 or fewer left</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN BAWAH: TABEL RECENT ORDERS & LOW STOCK LIST -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Orders (Lebar 2 Kolom) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold">Recent orders</h2>
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-xs font-semibold text-gray-600 hover:text-black">View all orders</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-gray-100 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 font-medium">Order</th>
                                <th class="pb-3 font-medium">Customer</th>
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium">Total</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                      <tbody class="divide-y divide-gray-50 text-sm">
    @forelse ($recentOrders as $order)
        @php
            $status = strtolower($order->status ?? 'pending');

            $statusLabel = match ($status) {
                'pending', 'awaiting payment' => 'Awaiting payment',
                'paid' => 'Paid',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                default => ucfirst($status),
            };

            $statusClass = match ($status) {
                'pending', 'awaiting payment' =>
                    'bg-amber-50 text-amber-700 border border-amber-200/50',
                'paid' =>
                    'bg-black text-white',
                'processing' =>
                    'bg-blue-50 text-blue-700 border border-blue-200/50',
                'shipped' =>
                    'bg-gray-100 text-gray-800',
                'completed' =>
                    'bg-emerald-50 text-emerald-700 border border-emerald-200/50',
                default =>
                    'bg-gray-100 text-gray-700',
            };

            $dotClass = match ($status) {
                'pending', 'awaiting payment' => 'bg-amber-500',
                'paid' => 'bg-white',
                'processing' => 'bg-blue-500',
                'shipped' => 'bg-gray-500',
                'completed' => 'bg-emerald-500',
                default => 'bg-gray-500',
            };

            $customerName = $order->user?->name
                ?: trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? ''));
        @endphp

        <tr class="hover:bg-gray-50/50">
            <td class="py-3 font-medium text-xs">
                #FN-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </td>

            <td class="py-3 text-xs text-gray-700">
                {{ $customerName ?: $order->email }}
            </td>

            <td class="py-3 text-xs text-gray-500">
                {{ $order->created_at?->format('d M, H:i') }}
            </td>

            <td class="py-3 text-xs font-medium">
                Rp{{ number_format($order->total_price, 0, ',', '.') }}
            </td>

            <td class="py-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $statusClass }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                    {{ $statusLabel }}
                </span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="py-6 text-center text-xs text-gray-400">
                No orders yet.
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products (Lebar 1 Kolom) -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-base font-bold">Low stock</h2>
                        <a href="{{ route('admin.products.index') }}"
                            class="text-xs font-semibold text-gray-600 hover:text-black">View products</a>
                    </div>

                   <div class="space-y-4">
    @forelse ($lowStockProducts as $variant)
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center font-bold text-xs text-amber-800">
                    👕
                </div>

                <div>
                    <h4 class="text-xs font-medium text-gray-800">
                        {{ $variant->product?->name ?? 'Produk tidak ditemukan' }}
                    </h4>
                    <p class="text-[11px] text-gray-400">
                        {{ $variant->color }} / {{ $variant->size }}
                    </p>
                </div>
            </div>

            <span class="text-xs font-semibold {{ $variant->stock == 0 ? 'text-red-600' : 'text-amber-600' }}">
                {{ $variant->stock == 0 ? 'Out of stock' : $variant->stock . ' left' }}
            </span>
        </div>
    @empty
        <p class="text-xs text-gray-400">
            No low stock products.
        </p>
    @endforelse
</div>
                </div>
            </div>

        </div>
    </div>
@endsection
