<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col justify-between">


    <!-- NAVBAR PAYMENT -->
    <nav class="border-b border-gray-200 px-6 md:px-16 py-4 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition">ASYART.MERCH</a>
            <a href="{{ route('checkout') }}"
                class="text-xs text-gray-500 hover:text-black flex items-center gap-1 transition">
                <span>← Return to information</span>
            </a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-6xl mx-auto px-4 md:px-6 py-10 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 flex-1">

        <!-- KOLOM KIRI: QRIS PAYMENT DETAIL -->
        <div class="lg:col-span-7 space-y-8 order-2 lg:order-1">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('cart.page') }}" class="hover:underline">Bag</a>
                <span>›</span>
                <a href="{{ route('checkout') }}" class="hover:underline">Information</a>
                <span>›</span>
                <span class="text-gray-900 font-medium">Payment</span>
            </div>

            <!-- Status Badge & Judul -->
            <div class="space-y-3">
                <div
                    class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1 rounded text-amber-700 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Waiting for payment <span
                        class="text-gray-400 font-normal">|ASY-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="text-3xl font-serif font-medium">Scan to pay with QRIS</h1>
                <p class="text-xs text-gray-500">Use any e-wallet or mobile banking app. The amount is filled in
                    automatically.</p>
            </div>

            <!-- Card Utama QRIS -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 space-y-6 shadow-sm">

                <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
                    <!-- Sisi Kiri: Gambar QR Code Asli -->
                    <div
                        class="border border-gray-200 p-4 rounded-lg bg-white text-center space-y-2 flex-shrink-0 mx-auto sm:mx-0">
                        <div
                            class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest px-1">
                            <span>QRIS</span>
                            <span>ASYART.MERCH</span>
                        </div>
                        <div class="w-32 h-32 sm:w-36 sm:h-36 bg-white p-1 flex items-center justify-center mx-auto">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS"
                                class="w-full h-full object-contain">
                        </div>
                        <p class="text-[9px] text-gray-400">ASYART.MERCH<br>NMID: ID1026033331432</p>
                    </div>

                    <!-- Sisi Kanan: Total Payment & Countdown Timer -->
                    <div class="space-y-4 w-full text-center sm:text-left">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total payment</p>
                            <h2 id="qris-total-price" class="text-2xl font-bold text-gray-900">
                                Rp{{ number_format($total, 0, ',', '.') }} </h2>
                        </div>

                        <!-- Bar Timer Hitung Mundur -->
                        <div class="bg-orange-50 border border-orange-200 rounded p-3 space-y-1 text-left">
                            <div class="flex justify-between text-[11px] font-medium text-amber-800">
                                <span class="flex items-center gap-1">⏱ Pay before the code expires</span>
                                <span id="countdown-timer" class="font-bold">15:00</span>
                            </div>
                            <div class="w-full bg-orange-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-orange-500 h-full w-full" id="timer-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi QRIS -->
                <div class="space-y-3 pt-2">
                    <!-- Demo UI: belum terhubung ke payment gateway QRIS -->
                    <button type="button"
                        onclick="alert('QRIS belum terhubung ke payment gateway. Pesanan masih berstatus pending.')"
                        class="block w-full bg-black text-white text-center py-3.5 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95 shadow-md">
                        Check payment status
                    </button>
                    <button type="button" onclick="alert('QR code berhasil diunduh!')"
                        class="w-full border border-gray-300 bg-white text-gray-800 text-center py-3 rounded text-xs font-medium hover:bg-gray-50 transition">
                        Download QR code
                    </button>
                </div>

                <p class="text-[10px] text-gray-400 text-center">Payment is confirmed automatically — no need to refresh
                    this page.</p>
            </div>

            <!-- Panduan How to Pay -->
            <div class="space-y-3 pt-4 border-t border-gray-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">How to pay</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-600">
                    <div class="flex items-start gap-2.5">
                        <span
                            class="w-5 h-5 rounded bg-gray-100 border border-gray-300 flex items-center justify-center font-bold text-[10px] text-gray-800 flex-shrink-0">1</span>
                        <p class="text-[11px]">Open any e-wallet or mobile banking app that supports QRIS.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span
                            class="w-5 h-5 rounded bg-gray-100 border border-gray-300 flex items-center justify-center font-bold text-[10px] text-gray-800 flex-shrink-0">2</span>
                        <p class="text-[11px]">Choose "Scan QR" and point your camera at the code.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span
                            class="w-5 h-5 rounded bg-gray-100 border border-gray-300 flex items-center justify-center font-bold text-[10px] text-gray-800 flex-shrink-0">3</span>
                        <p class="text-[11px]">Check that the merchant is ASYART.MERCH and the amount is correct.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span
                            class="w-5 h-5 rounded bg-gray-100 border border-gray-300 flex items-center justify-center font-bold text-[10px] text-gray-800 flex-shrink-0">4</span>
                        <p class="text-[11px]">Confirm the payment — this page updates automatically.</p>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="#" onclick="alert('Kembali ke metode pembayaran')"
                        class="text-[11px] text-gray-500 hover:text-black underline">Change payment method</a>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: ORDER SUMMARY -->
        <div class="lg:col-span-5 order-1 lg:order-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-6 space-y-6 shadow-sm">
                <h3 class="text-sm font-semibold border-b border-gray-100 pb-3">Order Summary</h3>

                <div id="payment-items-list" class="space-y-4 max-h-[320px] overflow-y-auto pr-1">

                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-3 text-xs border-b border-gray-50 pb-3">

                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $item->product_name }}
                                </p>

                                <p class="text-[10px] text-gray-400">
                                    Color: {{ $item->color ?? '-' }}
                                    | Size: {{ $item->size ?? '-' }}
                                    | Qty: {{ $item->quantity }}
                                </p>
                            </div>

                            <span class="font-semibold text-gray-900">
                                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>

                        </div>
                    @endforeach

                </div>

                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900">
                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between text-gray-500">
                    <span>Shipping</span>
                    <span class="font-medium text-gray-900">
                        Rp{{ number_format($shippingCost, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>PPN (11%)</span>
                    <span class="font-medium text-gray-900">
                        Rp{{ number_format($tax, 0, ',', '.') }}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-900">Total</span>
                    <span id="payment-total" class="text-base font-bold text-gray-900">
                        Rp{{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

    <!-- SCRIPT RENDER ORDER SUMMARY & TIMER 1 MENIT (15:00 -> 14:00) -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const display = document.getElementById('countdown-timer');
    const timerBar = document.getElementById('timer-bar');

    if (!display) return;

    // Batas waktu dihitung dari waktu order dibuat di server.
    const orderCreatedAt = {{ $order->created_at->timestamp * 1000 }};
    const expiryTime = orderCreatedAt + (15 * 60 * 1000);

    function updateCountdown() {
        const remaining = Math.max(
            0,
            Math.ceil((expiryTime - Date.now()) / 1000)
        );

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        display.textContent =
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        if (timerBar) {
            timerBar.style.width = ((remaining / 900) * 100) + '%';
        }

        if (remaining <= 0) {
            display.textContent = 'EXPIRED';

            const message = document.getElementById('payment-expired-message');

            if (message) {
                message.classList.remove('hidden');
            }

            const buttons = document.querySelectorAll(
                '[data-payment-action]'
            );

            buttons.forEach(button => {
                button.setAttribute('aria-disabled', 'true');
                button.classList.add('opacity-50', 'pointer-events-none');
            });

            return;
        }

        setTimeout(updateCountdown, 1000);
    }

    updateCountdown();
});
</script>

<p id="payment-expired-message"
   class="hidden text-xs text-red-600 mt-2">
    Waktu pembayaran telah berakhir. Silakan hubungi admin untuk langkah selanjutnya.
</p>
</body>

</html>
