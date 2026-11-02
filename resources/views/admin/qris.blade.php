@extends('layouts.admin', ['title' => 'Admin - QRIS Transactions'])

@section('content')
    <!-- Tanpa max-w-5xl, lebarnya bakal otomatis full dan sama persis kayak halaman Orders -->
    <di class="space-y-6 pb-12">

        <!-- PAGE HEADER & EXPORT CSV -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">QRIS Transactions</h1>
                <p class="text-xs text-gray-500 mt-0.5">Every QR code generated at checkout. Statuses update automatically.</p>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Received Today -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
                <p class="text-xs font-semibold text-gray-500">Received today</p>
                <h3 class="text-2xl font-bold tracking-tight text-gray-900">Rp18.420.000</h3>
                <p class="text-[11px] text-gray-400">7 payments</p>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
                <p class="text-xs font-semibold text-gray-500">Pending</p>
                <h3 class="text-2xl font-bold tracking-tight text-gray-900">Rp2.950.000</h3>
                <p class="text-[11px] text-gray-400">1 QR waiting for payment</p>
            </div>

            <!-- Expired Today -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-1">
                <p class="text-xs font-semibold text-gray-500">Expired today</p>
                <h3 class="text-2xl font-bold tracking-tight text-gray-900">2</h3>
                <p class="text-[11px] text-gray-400">Not paid within 15 minutes</p>
            </div>
        </div>

        <!-- FILTER TABS & SEARCH / DATE BAR -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <!-- Tab Navigasi Status (All, Paid, Pending, Expired) -->
            <div
                class="px-6 pt-4 border-b border-gray-100 flex items-center gap-6 text-xs font-medium text-gray-500 overflow-x-auto">
                <button type="button"
                    class="pb-3 border-b-2 border-black text-black font-semibold flex items-center gap-1.5">
                    All <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">214</span>
                </button>
                <button type="button"
                    class="pb-3 border-b-2 border-transparent hover:text-gray-900 flex items-center gap-1.5">
                    Paid <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">198</span>
                </button>
                <button type="button"
                    class="pb-3 border-b-2 border-transparent hover:text-gray-900 flex items-center gap-1.5">
                    Pending <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">1</span>
                </button>
                <button type="button"
                    class="pb-3 border-b-2 border-transparent hover:text-gray-900 flex items-center gap-1.5">
                    Expired <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">15</span>
                </button>
            </div>

            <!-- Search & Date Filter Input -->
            <div
                class="p-4 sm:p-6 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="w-full sm:w-80 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Search reference or order ID"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                </div>

                <div class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-auto">
                        <select
                            class="w-full sm:w-auto px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:border-black appearance-none pr-8">
                            <option>Date: Last 7 days</option>
                            <option>Date: Today</option>
                            <option>Date: Last 30 days</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

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
                        @forelse($qrisTransactions ?? [] as $trx)
                            <!-- Loop data dinamis dari backend nantinya -->
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', $trx->id ?? 1) }}"
                                        class="text-black hover:underline font-semibold">
                                        {{ $trx->reference ?? 'QR-20260512-8846' }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">{{ $trx->order_number ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-gray-600">{{ $trx->customer_name ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-semibold">Rp{{ number_format($trx->amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-500">{{ $trx->created_at ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-gray-500">{{ $trx->paid_or_expiry ?? '-' }}</td>
                                <td class="py-3.5 px-6">
                                    @if (strtolower($trx->status) == 'paid')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                        </span>
                                    @elseif(strtolower($trx->status) == 'pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Expired
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <!-- DATA STATIS CADANGAN SESUAI FIGMA / SCREENSHOT -->
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 1) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260512-8846
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10433</td>
                                <td class="py-3.5 px-4 text-gray-600">Rani Pratama</td>
                                <td class="py-3.5 px-4 font-semibold">Rp2.950.000</td>
                                <td class="py-3.5 px-4 text-gray-500">12 May, 14:41</td>
                                <td class="py-3.5 px-4 text-gray-500">Expires 14:56</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 2) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260512-8841
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10432</td>
                                <td class="py-3.5 px-4 text-gray-600">Ines Moreau</td>
                                <td class="py-3.5 px-4 font-semibold">Rp5.080.000</td>
                                <td class="py-3.5 px-4 text-gray-500">12 May, 14:20</td>
                                <td class="py-3.5 px-4 text-gray-500">Paid 14:32</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 3) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260512-8838
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10430</td>
                                <td class="py-3.5 px-4 text-gray-600">Dimas Saputra</td>
                                <td class="py-3.5 px-4 font-semibold">Rp6.580.000</td>
                                <td class="py-3.5 px-4 text-gray-500">12 May, 10:41</td>
                                <td class="py-3.5 px-4 text-gray-500">Paid 10:44</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 4) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260511-8829
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10429</td>
                                <td class="py-3.5 px-4 text-gray-600">Sarah Wijaya</td>
                                <td class="py-3.5 px-4 font-semibold">Rp1.890.000</td>
                                <td class="py-3.5 px-4 text-gray-500">11 May, 19:12</td>
                                <td class="py-3.5 px-4 text-gray-500">Paid 19:15</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 5) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260511-8826
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10428</td>
                                <td class="py-3.5 px-4 text-gray-600">Bayu Nugroho</td>
                                <td class="py-3.5 px-4 font-semibold">Rp3.940.000</td>
                                <td class="py-3.5 px-4 text-gray-500">11 May, 18:20</td>
                                <td class="py-3.5 px-4 text-gray-500">Paid 18:22</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <a href="{{ route('admin.qris.detail', 6) }}"
                                        class="text-black hover:underline font-semibold">
                                        QR-20260511-8819
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900">#FN-10427</td>
                                <td class="py-3.5 px-4 text-gray-600">Laras Anindya</td>
                                <td class="py-3.5 px-4 font-semibold">Rp2.590.000</td>
                                <td class="py-3.5 px-4 text-gray-500">11 May, 09:58</td>
                                <td class="py-3.5 px-4 text-gray-500">Paid 10:01</td>
                                <td class="py-3.5 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINATION -->
            <div
                class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                <p class="text-xs text-gray-500">Showing 1–8 of 214 transactions</p>
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
