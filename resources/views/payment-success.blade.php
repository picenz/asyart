<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col justify-between">

    <!-- NAVBAR BERSIH TANPA SEARCH -->
    <nav class="border-b border-gray-200 px-6 md:px-16 py-4 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-center sm:justify-start">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition">ASYART.MERCH</a>
        </div>
    </nav>

    <!-- KONTEN UTAMA RESPONSIF -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 md:py-12 w-full flex-1 space-y-8 md:space-y-10">

        <!-- HEADER SUKSES -->
        <div class="text-center space-y-3">
            <div
                class="w-12 h-12 mx-auto rounded-full border border-gray-300 bg-white flex items-center justify-center text-gray-800 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="space-y-1">
                <p class="text-xs text-gray-400 font-mono tracking-wider">ORDER #RN-10432</p>
                <h1 id="success-username" class="text-2xl sm:text-3xl md:text-4xl font-serif font-medium text-gray-900">
                    Thank you,
                    User</h1>
                <p class="text-xs text-gray-500">Paid with QRIS. Your receipt is on its way to your email.</p>
            </div>
        </div>

        <!-- CARD DETAIL PESANAN & PENGIRIMAN -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 sm:p-8 space-y-8 shadow-sm">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Kolom Kiri: Ringkasan Produk -->
                <div class="md:col-span-7 space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 border-b border-gray-100 pb-3">
                        Order summary</h3>

                    <div id="success-items-list" class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                        <!-- Javascript akan mengisi list produk -->
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span id="success-subtotal" class="font-medium text-gray-900">Rp0</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Shipping</span>
                            <span class="font-medium text-gray-900">
                                {{ session('order_summary.shipping_cost', 0) > 0 ? 'Rp ' . number_format(session('order_summary.shipping_cost'), 0, ',', '.') : 'Free' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>VAT (11% PPN)</span>
                            <span class="font-medium text-gray-900">Included</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-900">Total</span>
                        <span id="success-total" class="text-base font-bold text-gray-900">
                            Rp{{ number_format(session('order_summary.total', 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Kolom Kanan: Alamat & Info Pengiriman -->
                <div
                    class="md:col-span-5 space-y-6 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-6 text-xs text-gray-600">
                    <div class="space-y-2">
                        <h4 class="font-bold uppercase tracking-wider text-gray-900 text-[11px]">Delivery address</h4>
                        <p class="font-semibold text-gray-900">Dawa Dzaki</p>
                        <p class="leading-relaxed">Jl. Koja Raya No. 45<br>Koja, Jakarta Utara 14220<br>Indonesia</p>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <h4 class="font-bold uppercase tracking-wider text-gray-900 text-[11px]">Delivery method</h4>
                        <p>
                            {{ session('checkout_data.delivery_method', 'Standard delivery') }}<br>
                            <span class="text-gray-400">
                                {{ session('checkout_data.delivery_method') == 'Express delivery' ? '1–2 business days' : '3–5 business days' }}
                            </span>
                        </p>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <h4 class="font-bold uppercase tracking-wider text-gray-900 text-[11px]">Payment</h4>
                        <p>QRIS · Paid Today<br><span
                                class="font-semibold text-gray-900">Rp{{ number_format(session('order_summary.total', 0), 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- TOMBOL AKSI BAWAH -->
        <div class="max-w-sm mx-auto space-y-3 pt-2 px-4 sm:px-0">
            <a href="{{ route('home') }}" onclick="localStorage.removeItem('cart');"
                class="block w-full bg-black text-white text-center py-3.5 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95 shadow-md">
                Continue shopping
            </a>
            <div class="text-center">
                <a href="#" onclick="alert('Nomor resi pengiriman akan dikirim via email.')"
                    class="text-xs text-gray-500 hover:text-black underline">Track your order</a>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

    <!-- SCRIPT RENDER DATA & USER -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            renderSuccessSummary();
        });

        function renderSuccessSummary() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('success-items-list');
            const subtotalEl = document.getElementById('success-subtotal');
            const usernameEl = document.getElementById('success-username');

            @auth
            if (usernameEl) usernameEl.innerText = "Thank you, {{ auth()->user()->name }}";
        @endauth

        if (!container) return;

        if (cart.length === 0) {
            container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Tidak ada produk dalam pesanan.</p>`;
            return;
        }

        let subtotal = 0;

        container.innerHTML = cart.map(item => {
            let hargaSatuan = parseInt(item.harga.replace(/[^0-9]/g, ''));
            let totalItemHarga = hargaSatuan * item.qty;
            subtotal += totalItemHarga;

            return `
                    <div class="flex items-center justify-between gap-3 text-xs border-b border-gray-50 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="${item.gambar}" class="w-12 h-12 object-cover rounded border border-gray-200 flex-shrink-0">
                            <div>
                                <p class="font-semibold text-gray-900">${item.nama}</p>
                                <p class="text-[10px] text-gray-400">Color: ${item.warna} | Size: ${item.ukuran} | Qty: ${item.qty}</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900 flex-shrink-0">Rp${totalItemHarga.toLocaleString('id-ID')}</span>
                    </div>
                `;
        }).join('');

        if (subtotalEl) subtotalEl.innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
        }
    </script>

</body>

</html>
