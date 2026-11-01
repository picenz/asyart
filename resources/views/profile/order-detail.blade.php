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
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 space-y-3 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Timeline Pengiriman</h3>
            <div class="space-y-2 text-xs divide-y divide-gray-100">
                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium text-gray-900">
                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span> Pesanan Dibuat
                    </span>
                    <span class="text-[11px] text-gray-400 shrink-0">Selesai</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium text-gray-900">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shrink-0"></span> Diproses Toko
                    </span>
                    <span class="text-[11px] text-gray-400 shrink-0">Sedang dikemas</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span> Dalam Pengiriman
                    </span>
                    <span class="text-[11px] text-gray-400 shrink-0">Menunggu kurir</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="flex items-center gap-2 font-medium text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span> Selesai
                    </span>
                    <span class="text-[11px] text-gray-400 shrink-0">-</span>
                </div>
            </div>
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

                <span class="text-gray-400">Kurir / Layanan</span>
                <span class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_method }}</span>

                <span class="text-gray-400">Alamat Lengkap</span>
                <span class="font-medium text-gray-900 sm:text-right leading-relaxed">{{ $order->address }},
                    {{ $order->city }}, {{ $order->province }} - {{ $order->postal_code }}</span>
            </div>
        </div>

        <!-- Rincian Item Pesanan -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 space-y-4 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Isi Pesanan</h3>

            <div class="space-y-3 divide-y divide-gray-100">
                @php
                    $items = json_decode($order->items_json ?? '[]', true);
                    $subtotalProduk = 0;
                    foreach ($items as $item) {
                        $subtotalProduk += (int) preg_replace('/[^0-9]/', '', $item['harga']) * ($item['qty'] ?? 1);
                    }
                    // Deteksi ongkir otomatis berdasarkan metode pengiriman
                    $ongkir = strtolower($order->delivery_method) === 'express delivery' ? 20000 : 0;
                @endphp

                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-3 pt-3 first:pt-0 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            @if (isset($item['gambar']))
                                <img src="{{ $item['gambar'] }}"
                                    class="w-12 h-12 object-cover rounded border border-gray-200 shrink-0 bg-gray-50">
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $item['nama'] ?? 'Produk' }}</p>
                                <p class="text-[10px] text-gray-400 truncate">Color: {{ $item['warna'] ?? '-' }} |
                                    Size: {{ $item['ukuran'] ?? '-' }} | Qty: {{ $item['qty'] ?? 1 }}</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900 shrink-0">{{ $item['harga'] ?? 'Rp0' }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Total Harga & Ongkir Sinkron (Responsive & Rapi) -->
            <div class="border-t border-gray-200 pt-4 space-y-2 text-xs">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal / Produk</span>
                    <span class="font-medium text-gray-900">Rp{{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Ongkos Kirim ({{ $order->delivery_method }})</span>
                    <span class="font-medium text-gray-900">Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>
                <div
                    class="flex justify-between items-center pt-3 border-t border-gray-100 text-sm font-bold text-gray-900 bg-amber-50 p-3.5 rounded-xl border border-amber-200 shadow-sm mt-3 w-full">
                    <span class="tracking-wider">TOTAL PEMBAYARAN</span>
                    <span
                        class="text-amber-700 text-base sm:text-lg font-extrabold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

</body>

</html>
