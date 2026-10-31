<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Expired - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col justify-between">

    <!-- NAVBAR -->
    <nav class="border-b border-gray-200 px-6 md:px-16 py-4 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition">ASYART.MERCH</a>
            <a href="{{ route('home') }}"
                class="text-xs text-gray-500 hover:text-black flex items-center gap-1 transition">
                <span>← Back to home</span>
            </a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-6xl mx-auto px-4 md:px-6 py-10 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 flex-1">

        <!-- KOLOM KIRI: PAYMENT EXPIRED DETAIL -->
        <div class="lg:col-span-7 space-y-8 order-2 lg:order-1">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('cart.page') }}" class="hover:underline">Bag</a>
                <span>›</span>
                <a href="{{ route('checkout') }}" class="hover:underline">Information</a>
                <span>›</span>
                <span class="text-gray-900 font-medium">Payment</span>
            </div>

            <!-- Header Status & Pesan Expired -->
            <div class="space-y-4">
                <div
                    class="w-10 h-10 rounded border border-red-200 bg-red-50 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div
                        class="inline-flex items-center gap-2 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded text-red-700 text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Payment expired <span class="text-gray-400 font-normal">| Order #RN-10432</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-medium text-gray-900">Your QR code has expired</h1>
                    <p class="text-xs text-gray-500 leading-relaxed pt-1">
                        We didn't receive your payment within the time limit, so the code expired. Nothing has been
                        charged and your items are still in your bag.
                    </p>
                </div>
            </div>

            <!-- Kotak Info Abu-abu -->
            <div class="bg-gray-100 rounded-lg p-4 flex items-start gap-3 border border-gray-200/60">
                <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                    stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Already paid? It can take a few minutes to show up. We'll email <span
                        class="font-medium text-gray-900">support@asyartmerch.com</span> as soon as the payment is
                    confirmed.
                </p>
            </div>

            <!-- Tombol Aksi Responsif -->
            <div class="space-y-3 pt-2 w-full">
                <a href="{{ route('payment.page') }}"
                    class="block w-full bg-black text-white text-center py-3.5 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95 shadow-md">
                    Generate new QR code
                </a>
                <a href="{{ route('cart.page') }}"
                    class="block w-full border border-gray-300 bg-white text-gray-800 text-center py-3 rounded text-xs font-medium hover:bg-gray-50 transition">
                    Back to bag
                </a>
            </div>

            <!-- Bantuan Kontak -->
            <div class="text-[11px] text-gray-500 pt-2">
                Need help? <a href="#" onclick="alert('Silakan hubungi admin di WhatsApp / email support.')"
                    class="text-gray-900 underline font-medium">Contact support</a>
            </div>

        </div>

        <!-- KOLOM KANAN: ORDER SUMMARY -->
        <div class="lg:col-span-5 order-1 lg:order-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-6 space-y-6 shadow-sm">
                <h3 class="text-sm font-semibold border-b border-gray-100 pb-3">Order Summary</h3>

                <div id="expired-items-list" class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                    <!-- Javascript will populate this -->
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="expired-subtotal" class="font-medium text-gray-900">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Shipping</span>
                        <span id="expired-shipping" class="font-medium text-gray-900">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>VAT (11% PPN)</span>
                        <span class="font-medium text-gray-900">Included</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-900">Total</span>
                    <span id="expired-total" class="text-base font-bold text-gray-900">Rp0</span>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

    <!-- SCRIPT RENDER ORDER SUMMARY -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            renderExpiredSummary();
        });

        function renderExpiredSummary() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            // Ambil biaya pengiriman dari localStorage (sesuaikan key-nya jika sebelumnya pakai nama lain, default 20000)
            let shippingCost = parseInt(localStorage.getItem('shippingCost')) || 20000;

            const container = document.getElementById('expired-items-list');
            const subtotalEl = document.getElementById('expired-subtotal');
            const shippingEl = document.getElementById('expired-shipping');
            const totalEl = document.getElementById('expired-total');

            if (!container) return;

            if (cart.length === 0) {
                container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Keranjang kosong.</p>`;
                if (subtotalEl) subtotalEl.innerText = 'Rp0';
                if (shippingEl) shippingEl.innerText = 'Rp0';
                if (totalEl) totalEl.innerText = 'Rp0';
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

            let grandTotal = subtotal + shippingCost;

            if (subtotalEl) subtotalEl.innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            if (shippingEl) {
                shippingEl.innerText = shippingCost === 0 ? 'Free' : `Rp${shippingCost.toLocaleString('id-ID')}`;
            }
            if (totalEl) totalEl.innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
        }
    </script>

</body>

</html>
