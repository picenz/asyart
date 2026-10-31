<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col justify-between">

    <!-- NAVBAR CHECKOUT -->
    <nav class="border-b border-gray-200 px-6 md:px-16 py-4 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition">ASYART.MERCH</a>
            <a href="{{ route('cart.page') }}"
                class="text-xs text-gray-500 hover:text-black flex items-center gap-1 transition">
                <span>← Return to bag</span>
            </a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-6xl mx-auto px-4 md:px-6 py-10 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 flex-1">

        <!-- KOLOM KIRI (FORM PENGISIAN / SIGN IN) -->
        <div class="lg:col-span-7 space-y-8 order-2 lg:order-1">

            @guest
                <!-- 1. TAMPILAN JIKA BELUM LOGIN (SIGN IN / GUEST) -->
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <a href="{{ route('cart.page') }}" class="hover:underline">Bag</a>
                    <span>›</span>
                    <span class="text-gray-900 font-medium">Information</span>
                    <span>›</span>
                    <span>Payment</span>
                </div>

                <div class="space-y-2">
                    <h1 class="text-2xl font-serif font-medium">How would you like to check out?</h1>
                    <p class="text-xs text-gray-500">Sign in for faster checkout with your saved addresses, or continue as a
                        guest.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                    <!-- Returning Customer -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-white space-y-4 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Returning customer</h3>
                        <p class="text-[11px] text-gray-500">Sign in to use your saved details.</p>

                        <form method="POST" action="{{ route('login') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] uppercase font-semibold text-gray-500 mb-1">Email</label>
                                <input type="email" name="email" required placeholder="dawadzaki@gmail.com"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-xs bg-white focus:outline-none focus:border-black">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-semibold text-gray-500 mb-1">Password</label>
                                <input type="password" name="password" required placeholder="••••••••"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-xs bg-white focus:outline-none focus:border-black">
                            </div>
                            <button type="submit"
                                class="w-full bg-black text-white text-center py-3 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95">
                                Sign in & continue
                            </button>
                        </form>
                    </div>

                    <!-- Guest Checkout -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-white space-y-4 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Guest checkout</h3>
                        <p class="text-[11px] text-gray-500">No account needed — you can create one after your order.</p>

                        <ul class="text-[11px] text-gray-600 space-y-2">
                            <li class="flex items-center gap-2">✓ Fast, one-page checkout</li>
                            <li class="flex items-center gap-2">✓ Order updates sent to your email</li>
                            <li class="flex items-center gap-2">✓ Save your details later in one click</li>
                        </ul>

                        <div class="space-y-3 pt-2">
                            <div>
                                <label class="block text-[10px] uppercase font-semibold text-gray-500 mb-1">Email</label>
                                <input type="email" id="guest-email" placeholder="you@example.com"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-xs bg-white focus:outline-none focus:border-black">
                            </div>
                            <button type="button" onclick="proceedAsGuest()"
                                class="w-full bg-black text-white text-center py-3 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95">
                                Continue as guest
                            </button>
                        </div>
                    </div>

                </div>
            @else
                <!-- 2. TAMPILAN JIKA SUDAH LOGIN (FORM ALAMAT, VALIDASI WAJIB & QRIS) -->
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-8"
                    onsubmit="prepareCartPayload(event)">
                    @csrf

                    <!-- HIDDEN INPUT UNTUK MENGIRIM DATA KERANJANG & ONGKIR KE BACKEND -->
                    <input type="hidden" name="cart_data" id="input-cart-data">
                    <input type="hidden" name="shipping_cost" id="input-shipping-cost" value="0">

                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <a href="{{ route('cart.page') }}" class="hover:underline">Bag</a>
                        <span>›</span>
                        <span class="text-gray-900 font-medium">Information</span>
                        <span>›</span>
                        <span>Payment</span>
                    </div>

                    <!-- Contact (Wajib) -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Contact</h2>
                        <input type="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}"
                            placeholder="email@example.com" required
                            class="w-full border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                    </div>

                    <!-- Delivery Address -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Delivery</h2>
                        <div class="space-y-3">
                            <select name="country"
                                class="w-full border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                                <option value="Indonesia" selected>Indonesia</option>
                            </select>

                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="first_name" placeholder="First name" required
                                    class="border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                                <input type="text" name="last_name" placeholder="Last name" required
                                    class="border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                            </div>

                            <input type="text" name="address" placeholder="Address (Street name, house number)" required
                                class="w-full border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">

                            <input type="text" name="apartment" placeholder="Apartment, suite, etc. (Optional)"
                                class="w-full border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">

                            <div class="grid grid-cols-3 gap-3">
                                <input type="text" name="city" placeholder="City" required
                                    class="border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                                <input type="text" name="postal_code" placeholder="Postal code" required
                                    class="border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                                <input type="text" name="province" placeholder="Province" required
                                    class="border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                            </div>

                            <input type="text" name="phone" placeholder="Phone number" required
                                class="w-full border border-gray-300 rounded px-3 py-2.5 text-xs bg-white focus:outline-none focus:border-black">
                        </div>
                    </div>

                    <!-- Delivery Method -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Delivery method</h2>
                        <div class="space-y-2">
                            <label
                                class="flex items-center justify-between border border-gray-300 p-3 rounded cursor-pointer hover:border-black transition bg-white">
                                <div class="flex items-center gap-3 text-xs">
                                    <input type="radio" name="delivery_method" value="Standard delivery" checked
                                        onchange="updateShipping(0, 'Free')" class="accent-black">
                                    <div>
                                        <p class="font-medium">Standard delivery</p>
                                        <p class="text-[10px] text-gray-400">3-5 business days</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold">Free</span>
                            </label>

                            <label
                                class="flex items-center justify-between border border-gray-300 p-3 rounded cursor-pointer hover:border-black transition bg-white">
                                <div class="flex items-center gap-3 text-xs">
                                    <input type="radio" name="delivery_method" value="Express delivery"
                                        onchange="updateShipping(20000, 'Rp20.000')" class="accent-black">
                                    <div>
                                        <p class="font-medium">Express delivery</p>
                                        <p class="text-[10px] text-gray-400">1-2 business days</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold">Rp20.000</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Payment</h2>
                        <p class="text-[11px] text-gray-400">All transactions are secure and encrypted.</p>

                        <div class="border border-black rounded-lg bg-white p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" checked class="accent-black w-4 h-4">
                                    <span class="text-xs font-bold text-gray-900">QRIS</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 6.75h.008v.008H6.75V6.75zm0 9.75h.008v.008H6.75v-.008zm9.75-9.75h.008v.008H16.5V6.75zM13.5 13.5h2.25v2.25H13.5v-2.25zm0 3h.75v.75h-.75v-.75zm3-3h.75v.75h-.75v-.75zm0 3h.75v3h-3v-.75h2.25v-2.25z" />
                                </svg>
                            </div>
                            <p class="text-[11px] text-gray-600 pl-7">
                                Scan one QR code with any e-wallet or mobile banking app — GoPay, OVO, DANA, ShopeePay, or
                                your bank's app.
                            </p>
                            <div class="bg-gray-100 rounded p-3 flex items-start gap-2.5 ml-7">
                                <svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-[10px] text-gray-600 leading-relaxed">
                                    Your QR code appears after you place the order and is valid for 15 minutes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit Form -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-black text-white py-4 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95 text-center shadow-md cursor-pointer">
                            Pay with QRIS — <span id="btn-total-price">Rp0</span>
                        </button>
                    </div>
                </form>
            @endguest

        </div>

        <!-- KOLOM KANAN: ORDER SUMMARY -->
        <div class="lg:col-span-5 order-1 lg:order-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-6 space-y-6 shadow-sm">
                <h3 class="text-sm font-semibold border-b border-gray-100 pb-3">Order Summary</h3>

                <div id="checkout-items-list" class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                    <!-- Javascript will populate this -->
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="summary-subtotal" class="font-medium text-gray-900">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Shipping</span>
                        <span id="summary-shipping" class="font-medium text-gray-900">Free</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>VAT (11% PPN)</span>
                        <span class="font-medium text-gray-900">Included</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-900">Total</span>
                    <span id="summary-total" class="text-base font-bold text-gray-900">Rp0</span>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

    <!-- SCRIPT -->
    <script>
        let currentSubtotal = 0;
        let currentShippingCost = 0;

        document.addEventListener('DOMContentLoaded', () => {
            renderCheckoutSummary();
        });

        function proceedAsGuest() {
            let email = document.getElementById('guest-email').value;
            if (!email) {
                alert('Masukkan email terlebih dahulu, bro!');
                return;
            }
            alert('Melanjutkan sebagai guest dengan email: ' + email);
            window.location.reload();
        }

        // Fungsi untuk membungkus data keranjang ke hidden input sebelum form dikirim ke BE
        function prepareCartPayload(event) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            document.getElementById('input-cart-data').value = JSON.stringify(cart);

            // Catatan buat temen BE: setelah order sukses diproses di controller, 
            // pastikan backend nge-redirect ke route('payment.success')
        }

        function renderCheckoutSummary() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('checkout-items-list');
            const subtotalEl = document.getElementById('summary-subtotal');

            if (!container) return;

            if (cart.length === 0) {
                container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Keranjang kosong.</p>`;
                return;
            }

            currentSubtotal = 0;

            container.innerHTML = cart.map(item => {
                let hargaSatuan = parseInt(item.harga.replace(/[^0-9]/g, ''));
                let totalItemHarga = hargaSatuan * item.qty;
                currentSubtotal += totalItemHarga;

                return `
                    <div class="flex items-center justify-between gap-3 text-xs border-b border-gray-50 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="${item.gambar}" class="w-12 h-12 object-cover rounded border border-gray-200">
                            <div>
                                <p class="font-semibold text-gray-900">${item.nama}</p>
                                <p class="text-[10px] text-gray-400">Color: ${item.warna} | Size: ${item.ukuran} | Qty: ${item.qty}</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">Rp${totalItemHarga.toLocaleString('id-ID')}</span>
                    </div>
                `;
            }).join('');

            if (subtotalEl) subtotalEl.innerText = `Rp${currentSubtotal.toLocaleString('id-ID')}`;
            updateTotalDisplay();
        }

        function updateShipping(cost, labelText) {
            currentShippingCost = cost;

            const shippingEl = document.getElementById('summary-shipping');
            if (shippingEl) shippingEl.innerText = labelText;

            const hiddenInput = document.getElementById('input-shipping-cost');
            if (hiddenInput) hiddenInput.value = cost;

            updateTotalDisplay();
        }

        function updateTotalDisplay() {
            let grandTotal = currentSubtotal + currentShippingCost;

            const totalEl = document.getElementById('summary-total');
            const btnTotalEl = document.getElementById('btn-total-price');

            if (totalEl) totalEl.innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
            if (btnTotalEl) btnTotalEl.innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
        }
    </script>

</body>

</html>
