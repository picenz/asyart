@extends('layouts.admin', ['title' => 'Admin Order Detail'])

@section('content')
    <div x-data="{ openEditModal: false }">

        @php
            $orderNumber = '#FN-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $status = strtolower($order->status ?? 'pending');
            $statusLabel = match ($status) {
                'pending', 'awaiting payment' => 'Awaiting payment',
                'processing' => 'Processing',
                'paid' => 'Paid',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                default => ucfirst($status),
            };

            $totalItems = $orderItems->sum('quantity');
            $subtotal = $orderItems->sum('subtotal');
            $total = $order->total_price ?? $subtotal;
            $shippingCost = $order->delivery_method === 'Express delivery' ? 20000 : 0;
            $tax = max(0, $total - $subtotal - $shippingCost);
            $canComplete = $status === 'shipped';

        @endphp

        <!-- BREADCRUMB & HEADER -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="hover:underline">Orders</a>
                    <span>&gt;</span>
                    <span class="text-gray-900 font-medium">{{ $orderNumber }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight">Order {{ $orderNumber }}</h1>

                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                        {{ $statusLabel }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Placed {{ $order->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            @if ($canComplete)
                <div>
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 shadow-sm transition">
                            Mark as completed
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- GRID UTAMA -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Items Table Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h2 class="text-base font-bold mb-4">Items ({{ $totalItems }})</h2>

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
                                @forelse ($orderItems as $item)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600">
                                                    👕
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">
                                                        {{ $item->product_name ?? ($item->product?->name ?? 'Product') }}
                                                    </p>
                                                    <p class="text-[11px] text-gray-500">
                                                        {{ $item->color ?? '-' }} - {{ $item->size ?? '-' }}
                                                        @if ($item->variant?->sku)
                                                            · SKU: {{ $item->variant->sku }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 text-center text-gray-600">{{ $item->quantity }}</td>
                                        <td class="py-3.5 text-right font-medium">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 text-right font-semibold">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No items found for this order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900">
                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-gray-600">
    <span>Shipping - {{ $order->delivery_method ?? 'Standard' }}</span>
    <span class="font-medium text-gray-900">
        {{ $shippingCost > 0 ? 'Rp' . number_format($shippingCost, 0, ',', '.') : 'Free' }}
    </span>
</div>
<div class="flex justify-between text-gray-600">
    <span>VAT (PPN 11%)</span>
    <span class="font-medium text-gray-900">
        Rp{{ number_format($tax, 0, ',', '.') }}
    </span>
</div>

                        <div class="flex justify-between pt-3 border-t border-gray-100 text-sm font-bold text-gray-900">
                            <span>Total</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h2 class="text-base font-bold mb-4">Timeline</h2>

                    <div
                        class="space-y-4 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-gray-100">

                        @if (in_array($status, ['shipped', 'completed']))
                            <div class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                    🚚
                                </div>
                                <div class="text-xs">
                                    <p class="font-semibold text-gray-900">Order shipped</p>
                                    <p class="text-gray-500">Shipment date not available</p>
                                </div>
                            </div>
                        @endif

                        @if (in_array($status, ['paid', 'shipped', 'completed']))
                            <div class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                    ✓
                                </div>
                                <div class="text-xs">
                                    <p class="font-semibold text-gray-900">Payment received</p>
                                    <p class="text-gray-500">Payment date not available</p>
                                </div>
                            </div>
                        @endif

                        <div class="relative flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center z-10 text-[10px] font-bold text-gray-600">
                                📦
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-gray-900">Order placed</p>
                                <p class="text-gray-500">{{ $order->created_at?->format('d M Y, H:i') ?? '-' }}</p>
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
                        <p class="font-semibold text-gray-900">
                            {{ trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')) ?: '-' }}
                        </p>
                        <p class="text-gray-500">{{ $order->email ?? '-' }}</p>
                        <p class="text-gray-600 pt-1">{{ $order->phone ?? '-' }}</p>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Shipping address</h3>
                    <div class="text-xs space-y-1 text-gray-600 leading-relaxed">
                        <p class="font-semibold text-gray-900">
                            {{ trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')) ?: '-' }}
                        </p>
                        <p>{{ $order->address ?? '-' }}</p>

                        @if ($order->apartment)
                            <p>{{ $order->apartment }}</p>
                        @endif

                        <p>
                            {{ collect([$order->city, $order->province, $order->postal_code])->filter()->implode(', ') ?:'-' }}
                        </p>

                        <div class="pt-2">
                            <span
                                class="inline-block px-2.5 py-1 bg-gray-100 text-gray-800 rounded-md text-[11px] font-medium">
                                {{ $order->delivery_method ?? 'Delivery method unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>

             <!-- Shipment Card -->
<div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
    <h3 class="text-sm font-bold text-gray-900">Shipment</h3>

    <div class="text-xs space-y-2">
        <div class="flex justify-between text-gray-600">
            <span>Courier</span>
            <span class="font-semibold text-gray-900">
                {{ $order->courier ?? 'Not available' }}
            </span>
        </div>

        <div class="flex justify-between items-center text-gray-600">
            <span>Tracking no.</span>
            <span class="font-mono font-semibold text-gray-900">
                {{ $order->tracking_number ?? 'Not available' }}
            </span>
        </div>

        <div class="flex justify-between text-gray-600">
            <span>Shipped on</span>
            <span class="text-gray-900">
                {{ $order->shipped_at?->format('d M Y, H:i') ?? 'Not available' }}
            </span>
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
                        <div class="flex justify-between text-gray-600">
                            <span>Method</span>
                            <span class="font-semibold text-gray-900">Not available</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Reference</span>
                            <span class="font-mono text-gray-900">Not available</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Paid at</span>
                            <span class="text-gray-900">Not available</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-100 font-bold text-gray-900">
                            <span>Amount</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT SHIPMENT MODAL -->
        <div x-show="openEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display: none;">

            <div @click.away="openEditModal = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-200">

                <form action="{{ route('admin.orders.shipment.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Edit shipment</h3>
                        <button type="button" @click="openEditModal = false"
                            class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    <div class="p-6 space-y-4">
                        <p class="text-xs text-gray-500">
                            Update courier and tracking details for this order.
                        </p>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Courier</label>
                            <input type="text" name="courier"
                                value="{{ old('courier', $order->courier) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:outline-none">
                            @error('courier')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tracking number</label>
                            <input type="text" name="tracking_number"
                                value="{{ old('tracking_number', $order->tracking_number) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:outline-none">
                            @error('tracking_number')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Shipped date</label>
                            <input type="datetime-local" name="shipped_at"
                                value="{{ old('shipped_at', $order->shipped_at?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:outline-none">
                            @error('shipped_at')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="openEditModal = false"
                                class="px-4 py-2 rounded-lg border border-gray-300 text-xs font-semibold hover:bg-gray-50">
                                Cancel
                            </button>

                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-black text-white text-xs font-semibold hover:bg-gray-800">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>


@endsection
