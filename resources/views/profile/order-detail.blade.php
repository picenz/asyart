<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail #{{ $order->order_number }} - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col justify-between antialiased">

    <!-- NAVBAR -->
    <nav class="border-b border-gray-200 px-4 sm:px-6 md:px-16 py-4 bg-white sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="{{ route('profile.orders') }}"
                class="text-xs text-gray-500 hover:text-black flex items-center gap-1 transition font-medium">
                <span>← Back to order history</span>
            </a>
            <span class="text-sm sm:text-base font-bold tracking-widest font-serif">ASYART.MERCH</span>
        </div>
    </nav>

    <!-- KONTEN UTAMA DETAIL PESANAN -->
    <main class="max-w-2xl mx-auto px-4 py-6 sm:py-8 w-full space-y-5 sm:space-y-6 flex-1">

        <!-- Header Status Pesanan (Glassmorphic Blur Rapi & Aman) -->
        <div class="bg-white/70 backdrop-blur-md border border-gray-200/80 p-6 sm:p-7 rounded-2xl space-y-2 shadow-sm">
            <p class="text-[11px] text-gray-500 uppercase tracking-wider font-semibold">Status Pesanan</p>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 tracking-wide">
                {{ $order->status ?? 'Processing' }}
            </h1>
            <p class="text-xs text-gray-500 pt-1 flex flex-col sm:flex-row sm:gap-2">
                <span>Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                <span class="hidden sm:inline">&bull;</span>
                <span class="font-mono font-medium text-gray-700">Ref: RN-{{ $order->id }}</span>
            </p>
        </div>

        <!-- Timeline Status / Tahapan Pesanan -->
        @php
            $orderStatus = strtolower($order->status ?? 'pending');
            $paymentStatus = strtolower($order->payment?->status ?? 'pending');

            $isPaid = $paymentStatus === 'paid' || in_array($orderStatus, ['paid', 'shipped', 'completed']);

            $isShipped = in_array($orderStatus, ['shipped', 'completed']);
            $isCompleted = $orderStatus === 'completed';
        @endphp

        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 space-y-3 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">
                Timeline Pesanan
            </h3>

            <div class="space-y-2 text-xs divide-y divide-gray-100">

                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium text-gray-900">
                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                        Pesanan Dibuat
                    </span>
                    <span class="text-[11px] text-gray-400">Selesai</span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium {{ $isPaid ? 'text-gray-900' : 'text-gray-400' }}">
                        <span
                            class="w-2 h-2 rounded-full {{ $isPaid ? 'bg-green-500' : 'bg-gray-300' }} shrink-0"></span>
                        Pembayaran Dikonfirmasi
                    </span>
                    <span class="text-[11px] text-gray-400">
                        {{ $isPaid ? $order->payment?->paid_at?->format('d M Y, H:i') ?? 'Selesai' : 'Menunggu pembayaran' }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium {{ $isPaid ? 'text-gray-900' : 'text-gray-400' }}">
                        <span
                            class="w-2 h-2 rounded-full {{ $isPaid ? 'bg-green-500' : 'bg-gray-300' }} shrink-0"></span>
                        Diproses Toko
                    </span>
                    <span class="text-[11px] text-gray-400">
                        {{ $isShipped ? 'Selesai' : ($isPaid ? 'Sedang diproses' : 'Menunggu pembayaran') }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span
                        class="flex items-center gap-2 font-medium {{ $isShipped ? 'text-gray-900' : 'text-gray-400' }}">
                        <span
                            class="w-2 h-2 rounded-full {{ $isShipped ? 'bg-green-500' : 'bg-gray-300' }} shrink-0"></span>
                        Dalam Pengiriman
                    </span>
                    <span class="text-[11px] text-gray-400">
                        {{ $isShipped ? $order->shipped_at?->format('d M Y, H:i') ?? 'Dikirim' : 'Menunggu kurir' }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span
                        class="flex items-center gap-2 font-medium {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">
                        <span
                            class="w-2 h-2 rounded-full {{ $isCompleted ? 'bg-green-500' : 'bg-gray-300' }} shrink-0"></span>
                        Selesai
                    </span>
                    <span class="text-[11px] text-gray-400">
                        {{ $isCompleted ? 'Pesanan selesai' : 'Belum selesai' }}
                    </span>
                </div>

            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 space-y-3 shadow-sm text-xs">
            <h3 class="font-bold uppercase tracking-wider text-gray-900">
                Informasi Pembayaran
            </h3>



            @if ($paymentStatus === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 space-y-2">
                    <p class="font-bold text-red-700">Pembayaran Ditolak</p>

                    <p class="text-red-600">
                        Bukti pembayaran kamu belum dapat diverifikasi.
                    </p>

                    @if ($order->payment?->rejection_note)
                        <p class="text-gray-700">
                            <strong>Alasan:</strong>
                            {{ $order->payment->rejection_note }}
                        </p>
                    @endif

                    <p class="text-gray-600">
                        Silakan kembali ke halaman pembayaran untuk mengunggah bukti pembayaran yang baru.
                    </p>
                </div>

                <a href="{{ route('payment.order', $order->id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    Upload Ulang Bukti Pembayaran
                </a>
            @elseif ($paymentStatus === 'paid')
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="font-bold text-green-700">Pembayaran Berhasil Diverifikasi</p>
                    <p class="text-gray-600 mt-1">
                        {{ $order->payment?->paid_at?->format('d M Y, H:i') ?? '-' }} WIB
                    </p>
                </div>
            @elseif ($paymentStatus === 'expired')
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <p class="font-bold text-gray-700">Pembayaran Kedaluwarsa</p>
                    <p class="text-gray-600 mt-1">
                        Waktu pembayaran telah berakhir.
                    </p>
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="font-bold text-amber-700">Menunggu Verifikasi Pembayaran</p>
                    <p class="text-gray-600 mt-1">
                        Pembayaran kamu sedang menunggu pemeriksaan admin.
                    </p>
                </div>
            @endif
        </div>
        <!-- Informasi Penerima & Alamat (Struk Data) -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 space-y-3 shadow-sm text-xs">
            <h3 class="font-bold uppercase tracking-wider text-gray-900">Informasi Pengiriman</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 sm:gap-y-2 text-gray-600 pt-1">
                <span class="text-gray-400">Penerima</span>
                <span class="font-medium text-gray-900 sm:text-right">{{ $order->first_name }}
                    {{ $order->last_name }}</span>

                <span class="text-gray-400">Email & Telepon</span>
                <span class="font-medium text-gray-900 sm:text-right break-all">{{ $order->email }} /
                    {{ $order->phone }}</span>

                <span class="text-gray-400">Metode Pengiriman</span> <span
                    class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_method }}</span>

                <span class="text-gray-400">Kurir</span>
                <span class="font-medium text-gray-900 sm:text-right">
                    {{ $order->courier ?? 'Belum ditentukan' }}
                </span>

                <span class="text-gray-400">Nomor Resi</span>
                <span class="font-medium text-gray-900 sm:text-right break-all">
                    {{ $order->tracking_number ?? 'Belum tersedia' }}
                </span>

                <span class="text-gray-400">Tanggal Dikirim</span>
                <span class="font-medium text-gray-900 sm:text-right">
                    {{ $order->shipped_at?->format('d M Y, H:i') ?? '-' }}
                </span>

                <span class="text-gray-400">Alamat Lengkap</span>
                <span class="font-medium text-gray-900 sm:text-right leading-relaxed">{{ $order->address }},
                    {{ $order->city }}, {{ $order->province }} - {{ $order->postal_code }}</span>
            </div>
        </div>

        <!-- Rincian Item Pesanan -->

@php
    $subtotalProduk = $order->items->sum('subtotal');
    $ongkir = strtolower($order->delivery_method) === 'express delivery' ? 20000 : 0;
@endphp

<div class="space-y-3 divide-y divide-gray-100">
    @forelse ($order->items as $item)
        <div class="flex items-center justify-between gap-3 pt-3 first:pt-0 text-xs">
            <div class="flex items-center gap-3 min-w-0">
                @php
                    $image = $item->product?->images->first();
                @endphp

                @if ($image)
                    <img src="{{ asset($image->image_path) }}"
                        alt="{{ $item->product_name }}"
                        class="w-12 h-12 object-cover rounded border border-gray-200 shrink-0 bg-gray-50">
                @else
                    <div class="w-12 h-12 bg-gray-100 rounded border border-gray-200 shrink-0"></div>
                @endif

                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 truncate">
                        {{ $item->product_name }}
                    </p>
                    <p class="text-[10px] text-gray-400">
                        Color: {{ $item->color ?? '-' }} |
                        Size: {{ $item->size ?? '-' }} |
                        Qty: {{ $item->quantity }}
                    </p>
                </div>
            </div>

            <span class="font-semibold text-gray-900 shrink-0">
                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
            </span>
        </div>
    @empty
        <p class="text-xs text-gray-500 py-3">
            Tidak ada item dalam pesanan ini.
        </p>
    @endforelse
</div>


    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

</body>

</html>
