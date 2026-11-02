@extends('layouts.admin', ['title' => 'Admin Orders'])

@section('content')
    <div>
        <!-- Judul & Tombol Export -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Orders</h1>
                <p class="text-sm text-gray-500 mt-0.5">View, ship and complete customer orders.</p>
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

        <!-- TABS FILTER STATUS -->
        <div class="flex items-center gap-2 border-b border-gray-200 pb-4 mb-6 overflow-x-auto">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status', 'all') == 'all' ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                All <span
                    class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('status', 'all') == 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['all'] ?? 128 }}</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'awaiting', 'page' => 1]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') == 'awaiting' ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Awaiting payment <span
                    class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('status') == 'awaiting' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['awaiting'] ?? 1 }}</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'paid', 'page' => 1]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') == 'paid' ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Paid <span
                    class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('status') == 'paid' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['paid'] ?? 6 }}</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'shipped', 'page' => 1]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') == 'shipped' ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Shipped <span
                    class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('status') == 'shipped' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['shipped'] ?? 9 }}</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'completed', 'page' => 1]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') == 'completed' ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Completed <span
                    class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('status') == 'completed' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['completed'] ?? 110 }}</span>
            </a>
        </div>

        <!-- SEARCH & DATE FILTER BAR -->
        <form method="GET" action="{{ route('admin.orders.index') }}"
            class="flex flex-col sm:flex-row items-center gap-3 mb-6">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search order ID or customer"
                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
            </div>
            <div class="w-full sm:w-auto">
                <select name="date_range" onchange="this.form.submit()"
                    class="w-full sm:w-auto px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:border-black">
                    <option value="30_days" {{ request('date_range') == '30_days' ? 'selected' : '' }}>Date: Last 30 days
                    </option>
                    <option value="7_days" {{ request('date_range') == '7_days' ? 'selected' : '' }}>Date: Last 7 days
                    </option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Date: Today</option>
                </select>
            </div>
        </form>

        <!-- TABEL UTAMA ORDERS -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-gray-100 text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3.5 px-4 font-medium">Order</th>
                            <th class="py-3.5 px-4 font-medium">Date</th>
                            <th class="py-3.5 px-4 font-medium">Customer</th>
                            <th class="py-3.5 px-4 font-medium">Items</th>
                            <th class="py-3.5 px-4 font-medium">Total</th>
                            <th class="py-3.5 px-4 font-medium">Payment</th>
                            <th class="py-3.5 px-4 font-medium">Status</th>
                            <th class="py-3.5 px-4 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs text-gray-800">
                        @isset($orders)
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-900">
                                        <a href="{{ route('admin.orders.detail', $order->id) }}"
                                            class="hover:underline">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td class="py-3.5 px-4 font-medium">{{ $order->first_name }} {{ $order->last_name }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">
                                        {{ count(json_decode($order->items_json, true) ?? []) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 uppercase font-medium">QRIS</td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $status = $order->status;
                                        @endphp
                                        @if ($status == 'Awaiting payment' || $status == 'Processing')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ $status }}
                                            </span>
                                        @elseif($status == 'Paid')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-black text-white">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span> Paid
                                            </span>
                                        @elseif($status == 'Shipped')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Shipped
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                {{ $status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('admin.orders.detail', $order->id) }}"
                                            class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <!-- DATA STATIS CADANGAN JIKA VARIABLE $orders BELUM DIKIRIM CONTROLLER -->
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 font-bold text-gray-900"><a href="#"
                                        class="hover:underline">#FN-10433</a></td>
                                <td class="py-3.5 px-4 text-gray-500">12 May 2026, 14:41</td>
                                <td class="py-3.5 px-4 font-medium">Rani Pratama</td>
                                <td class="py-3.5 px-4 text-gray-600">1</td>
                                <td class="py-3.5 px-4 font-semibold">Rp90.000</td>
                                <td class="py-3.5 px-4 text-gray-600 uppercase font-medium">QRIS</td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Awaiting payment
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition">···</button>
                                </td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINATION -->
            <div
                class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                <p class="text-xs text-gray-500">Showing orders data</p>
                <div class="flex items-center gap-1">
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-400 bg-gray-50 cursor-not-allowed"
                        disabled>&lt;</button>
                    <button class="px-3 py-1.5 bg-black text-white rounded-lg text-xs font-semibold">1</button>
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">2</button>
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">3</button>
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">&gt;</button>
                </div>
            </div>
        </div>
    </div>
@endsection
