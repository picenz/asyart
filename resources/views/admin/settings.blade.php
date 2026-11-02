@extends('layouts.admin', ['title' => 'Admin - QRIS Transactions'])

@section('content')
    <!-- Hapus max-w-5xl mx-auto, sisain class space-y-6 pb-12 aja biar lebarnya full kayak Orders -->
    <div class="space-y-6 pb-12">

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
        <!-- FORM UTAMA SETTINGS -->
        <form id="settingsForm" action="#" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: STORE DETAILS -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Store details</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Shown on order emails and invoices.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Store name</label>
                        <input type="text" name="store_name" value="Asyart Merch"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Contact email</label>
                        <input type="email" name="contact_email" value="hello@Asyart.com"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">WhatsApp / phone</label>
                        <input type="text" name="phone" value="+62 21 5090 1234"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Instagram</label>
                        <input type="text" name="instagram" value="@Asyart_merch"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Store address</label>
                    <input type="text" name="address" value="Jl. Kemang Raya No. 12, Jakarta Selatan 12730"
                        class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                </div>
            </div>

            <!-- SECTION 2: SHIPPING -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Shipping</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Rates shown at checkout. Tracking numbers are entered
                        manually when an order ships.</p>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr
                                class="bg-gray-50/80 border-b border-gray-200 text-gray-400 uppercase text-[10px] font-semibold">
                                <th class="py-3 px-4">Method</th>
                                <th class="py-3 px-4">Estimate</th>
                                <th class="py-3 px-4">Rate</th>
                                <th class="py-3 px-4 text-right">Active</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- Standard Delivery -->
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="py-3.5 px-4 font-semibold text-gray-900">Standard delivery</td>
                                <td class="py-3.5 px-4 text-gray-500">3–5 business days</td>
                                <td class="py-3.5 px-4">
                                    <input type="text" name="shipping[standard][rate]" value="Free"
                                        class="w-28 px-2 py-1.5 bg-white border border-gray-200 rounded-md text-xs font-medium focus:outline-none focus:border-black">
                                </td>
                                <!-- Tombol Toggle Interaktif Standard Delivery -->
                                <td class="py-3.5 px-4 text-right">
                                    <div x-data="{ active: false }" class="inline-block">
                                        <input type="hidden" name="shipping[standard][active]" :value="active ? 1 : 0">
                                        <button type="button" @click="active = !active"
                                            :class="active ? 'bg-black' : 'bg-gray-200'"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none">
                                            <span :class="active ? 'translate-x-3' : 'translate-x-1'"
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm">
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Express Delivery -->
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="py-3.5 px-4 font-semibold text-gray-900">Express delivery</td>
                                <td class="py-3.5 px-4 text-gray-500">1–2 business days</td>
                                <td class="py-3.5 px-4">
                                    <input type="text" name="shipping[express][rate]" value="Rp35.000"
                                        class="w-28 px-2 py-1.5 bg-white border border-gray-200 rounded-md text-xs font-medium focus:outline-none focus:border-black">
                                </td>
                                <!-- Tombol Toggle Interaktif Express Delivery (Default Active) -->
                                <td class="py-3.5 px-4 text-right">
                                    <div x-data="{ active: true }" class="inline-block">
                                        <input type="hidden" name="shipping[express][active]" :value="active ? 1 : 0">
                                        <button type="button" @click="active = !active"
                                            :class="active ? 'bg-black' : 'bg-gray-200'"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none">
                                            <span :class="active ? 'translate-x-3' : 'translate-x-1'"
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm">
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Free Standard Shipping Box -->
                <div
                    class="p-4 bg-gray-50/50 border border-gray-200 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Free standard shipping</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Applies when the order subtotal reaches the minimum.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <input type="text" name="free_shipping_minimum" value="Rp100.000"
                            class="w-32 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:outline-none focus:border-black">
                        <!-- Tombol Toggle Interaktif Free Standard Shipping (Default Active) -->
                        <div x-data="{ active: true }" class="inline-block">
                            <input type="hidden" name="free_shipping_active" :value="active ? 1 : 0">
                            <button type="button" @click="active = !active" :class="active ? 'bg-black' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none">
                                <span :class="active ? 'translate-x-3' : 'translate-x-1'"
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm">
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PAYMENTS -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Payments</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">QRIS is the only payment method at checkout.</p>
                </div>

                <!-- QRIS Card Connected Box -->
                <div
                    class="p-4 border border-gray-200 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs text-gray-700 border border-gray-200">
                            📱
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-gray-900">QRIS</h3>
                            <p class="text-[11px] text-gray-500">NMID ID1026XXXXXX432 · Asyart Merch Official</p>
                        </div>
                    </div>
                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Connected
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">QR code expires after</label>
                        <div class="relative">
                            <select name="qr_expiry"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black appearance-none">
                                <option value="15" selected>15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                            </select>
                            <span
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Payment notification email</label>
                        <input type="email" name="notification_email" value="orders@Asyart.com"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
