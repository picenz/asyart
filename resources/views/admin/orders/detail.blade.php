@extends('layouts.admin', ['title' => 'Admin Order Detail - Shipped'])

@section('content')
    <div x-data="{ openEditModal: false }">
        <!-- BREADCRUMB & HEADER -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="hover:underline">Orders</a>
                    <span>></span>
                    <span class="text-gray-900 font-medium">{{ $order->order_number ?? '#FN-10432' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight">Order {{ $order->order_number ?? '#FN-10432' }}</h1>

                    <!-- Badge Status Shipped -->
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Shipped
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Placed {{ $order->created_at ?? '12 May 2026, 14:20' }} · Shipped
                    {{ $order->shipped_at ?? '12 May 2026, 16:05' }}</p>
            </div>

            <!-- Tombol Aksi Mark as Completed -->
            <div>
                <form action="{{ route('admin.orders.complete', $order->id ?? 1) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 shadow-sm transition">
                        Mark as completed
                    </button>
                </form>
            </div>
        </div>

        <!-- GRID UTAMA (KIRI: Items & Timeline, KANAN: Customer, Address, Shipment, Payment) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items Table Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h2 class="text-base font-bold mb-4">Items (2)</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-gray-100 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="pb-3 font-medium">Product</th>
                                    <th class="pb-3 font-medium text-center">Qty</th>
                                    <th class="pb-3 font-medium text-right">Price</th>
                                    <th class="pb-3 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-xs text-gray-800">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3.5 flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600">
                                            👕</div>
                                        <div>
                                            <p class="font-medium text-gray-900">Longsleeve Fish Dishes</p>
                                            <p class="text-[11px] text-gray-500">Black - M · SKU: FN-TWB-BK-M</p>
                                        </div>
                                    </td>
                                    <td class="py-3.5 text-center text-gray-600">1</td>
                                    <td class="py-3.5 text-right font-medium">Rp90.000</td>
                                    <td class="py-3.5 text-right font-semibold">Rp90.000</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3.5 flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600">
                                            👕</div>
                                        <div>
                                            <p class="font-medium text-gray-900">Pinky Fish Baby tee</p>
                                            <p class="text-[11px] text-gray-500">White - S · SKU: FN-BNB-WT-S</p>
                                        </div>
                                    </td>
                                    <td class="py-3.5 text-center text-gray-600">1</td>
                                    <td class="py-3.5 text-right font-medium">Rp70.000</td>
                                    <td class="py-3.5 text-right font-semibold">Rp70.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-600"><span>Subtotal</span><span
                                class="font-medium text-gray-900">Rp160.000</span></div>
                        <div class="flex justify-between text-gray-600"><span>Shipping - Standard (3–5 days)</span><span
                                class="font-medium text-emerald-600">Free</span></div>
                        <div class="flex justify-between text-gray-600"><span>VAT (PPN 11%)</span><span
                                class="font-medium text-gray-900">Included</span></div>
                        <div class="flex justify-between pt-3 border-t border-gray-100 text-sm font-bold text-gray-900">
                            <span>Total paid</span><span>Rp160.000</span></div>
                    </div>
                </div>

                <!-- Timeline Card (Updated dengan Riwayat Shipped) -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h2 class="text-base font-bold mb-4">Timeline</h2>
                    <div
                        class="space-y-4 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-gray-100">

                        <div class="relative flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                🚚</div>
                            <div class="text-xs">
                                <p class="font-semibold text-gray-900">Shipped with JNE REG - JNE0123456789</p>
                                <p class="text-gray-500">12 May 2026, 16:05</p>
                            </div>
                        </div>

                        <div class="relative flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                ✓</div>
                            <div class="text-xs">
                                <p class="font-semibold text-gray-900">Payment received via QRIS</p>
                                <p class="text-gray-500">12 May 2026, 14:32</p>
                            </div>
                        </div>

                        <div class="relative flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                📦</div>
                            <div class="text-xs">
                                <p class="font-semibold text-gray-900">Order placed</p>
                                <p class="text-gray-500">12 May 2026, 14:20</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="space-y-6">
                <!-- Customer Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Customer</h3>
                    <div class="text-xs space-y-1">
                        <p class="font-semibold text-gray-900">Ines Moreau</p>
                        <p class="text-gray-500">ines@finness.com</p>
                        <p class="text-gray-600 pt-1">+62 812 3456 7890</p>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Shipping address</h3>
                    <div class="text-xs space-y-1 text-gray-600 leading-relaxed">
                        <p class="font-semibold text-gray-900">Ines Moreau</p>
                        <p>Jl. Senopati No. 24</p>
                        <p>Kebayoran Baru, Jakarta Selatan 12190</p>
                        <p>Indonesia</p>
                        <div class="pt-2"><span
                                class="inline-block px-2.5 py-1 bg-gray-100 text-gray-800 rounded-md text-[11px] font-medium">Standard
                                delivery · 3–5 days</span></div>
                    </div>
                </div>

                <!-- Shipment Card (BARU) -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Shipment</h3>
                    <div class="text-xs space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Courier</span>
                            <span class="font-semibold text-gray-900">JNE REG</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Tracking no.</span>
                            <span class="font-mono font-semibold text-gray-900 flex items-center gap-1">
                                JNE0123456789
                                <button class="text-gray-400 hover:text-black" title="Copy resi">📋</button>
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipped on</span>
                            <span class="text-gray-900">12 May 2026, 16:05</span>
                        </div>
                        <div class="pt-2 border-t border-gray-100">
                            <button @click="openEditModal = true" type="button"
                                class="text-xs font-semibold text-gray-900 hover:underline">
                                Edit shipment
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Payment Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Payment</h3>
                    <div class="text-xs space-y-2">
                        <div class="flex justify-between text-gray-600"><span>Method</span><span
                                class="font-semibold text-gray-900">QRIS</span></div>
                        <div class="flex justify-between text-gray-600"><span>Reference</span><span
                                class="font-mono text-gray-900">QR-20260512-8841</span></div>
                        <div class="flex justify-between text-gray-600"><span>Paid at</span><span class="text-gray-900">12
                                May 2026, 14:32</span></div>
                        <div class="flex justify-between pt-2 border-t border-gray-100 font-bold text-gray-900">
                            <span>Amount</span><span>Rp160.000</span></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL EDIT SHIPMENT (Opsional jika tombol Edit shipment diklik) -->
        <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display: none;">
            <div @click.away="openEditModal = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Edit shipment</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-xs text-gray-500">Update courier and tracking details for this order.</p>
                    <!-- Form edit bisa disesuaikan nanti -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button @click="openEditModal = false"
                            class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-semibold text-gray-700">Cancel</button>
                        <button @click="openEditModal = false"
                            class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
