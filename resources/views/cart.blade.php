<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Bag - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 font-sans relative min-h-screen flex flex-col justify-between">

    <!-- NAVBAR SEDERHANA -->
    <nav class="border-b border-gray-200 px-4 md:px-12 py-4 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition inline-block">ASYART.MERCH</a>
            <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-black">← Continue Shopping</a>
        </div>
    </nav>

    <!-- CONTAINER UTAMA SHOPPING BAG -->
    <main class="max-w-xl mx-auto px-4 py-10 w-full flex-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 md:p-8 space-y-6">

            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h1 class="text-xl font-serif font-medium">Your Bag</h1>
                <span id="bag-item-count" class="text-xs text-gray-400">0 items</span>
            </div>

            <!-- KONTEN DAFTAR BELANJA / EMPTY STATE -->
            <div id="cart-content" class="space-y-4 max-h-[450px] overflow-y-auto pr-1">
                <!-- Dirender via JavaScript -->
            </div>

            <!-- BAGIAN RINGKASAN & TOMBOL CHECKOUT -->
            <div id="cart-summary" class="border-t border-gray-100 pt-5 space-y-4 hidden">
                <div class="flex justify-between items-center text-sm font-semibold">
                    <span>Subtotal</span>
                    <span id="cart-subtotal" class="text-base font-bold text-gray-900">Rp0</span>
                </div>
                <button type="button" onclick="proceedCheckout()"
                    class="w-full bg-black text-white text-center py-3.5 rounded text-xs font-medium hover:bg-gray-800 transition active:scale-95">
                    Checkout
                </button>
            </div>

        </div>
    </main>

    <!-- FOOTER SEDERHANA -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

    <!-- SCRIPT RENDER KERANJANG LOCALSTORAGE & KONFIRMASI NOTIFIKASI -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            renderCartPage();
        });

        function renderCartPage() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('cart-content');
            const summaryBox = document.getElementById('cart-summary');
            const subtotalEl = document.getElementById('cart-subtotal');
            const itemCountEl = document.getElementById('bag-item-count');

            let totalJenisProduk = cart.length;

            if (itemCountEl) {
                itemCountEl.innerText = totalJenisProduk + (totalJenisProduk === 1 ? ' item' : ' items');
            }

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="py-16 text-center space-y-4">
                        <div class="w-16 h-16 bg-gray-50 border border-gray-200 rounded-full flex items-center justify-center mx-auto text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm font-semibold text-gray-900">Your bag is empty</h3>
                            <p class="text-xs text-gray-400 max-w-xs mx-auto">Looks like you haven't added anything to your bag yet. Let's explore our latest collections!</p>
                        </div>
                        <a href="{{ route('home') }}" class="inline-block bg-black text-white text-xs font-medium px-6 py-3 rounded hover:bg-gray-800 transition active:scale-95">
                            Start shopping
                        </a>
                    </div>
                `;
                summaryBox.classList.add('hidden');
                return;
            }

            summaryBox.classList.remove('hidden');
            let subtotal = 0;

            const colors = ['Black', 'White', 'Navy', 'Maroon'];
            const sizes = ['S', 'M', 'L', 'XL', 'XXL'];

            container.innerHTML = cart.map((item, index) => {
                let hargaSatuan = parseInt(item.harga.replace(/[^0-9]/g, ''));
                let totalItemHarga = hargaSatuan * item.qty;
                subtotal += totalItemHarga;

                let colorOptions = colors.map(c =>
                    `<option value="${c}" ${item.warna === c ? 'selected' : ''}>${c}</option>`).join('');
                let sizeOptions = sizes.map(s =>
                    `<option value="${s}" ${item.ukuran === s ? 'selected' : ''}>${s}</option>`).join('');

                return `
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-lg border border-gray-100 hover:border-gray-200 transition bg-gray-50/50">
                        <div class="flex items-start sm:items-center gap-3 w-full sm:w-auto">
                            <img src="${item.gambar}" class="w-16 h-16 object-cover rounded bg-gray-100 border border-gray-200 flex-shrink-0">
                            <div class="space-y-1 w-full">
                                <h4 class="text-xs font-semibold text-gray-900">${item.nama}</h4>
                                
                                <!-- Dropdown Warna & Ukuran Responsif -->
                                <div class="flex flex-wrap items-center gap-2 text-[10px] text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <span>Color:</span>
                                        <select onchange="updateVariant(${index}, 'warna', this.value)" class="border border-gray-200 rounded px-1.5 py-0.5 bg-white text-gray-700 font-medium focus:outline-none focus:border-black">
                                            ${colorOptions}
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>Size:</span>
                                        <select onchange="updateVariant(${index}, 'ukuran', this.value)" class="border border-gray-200 rounded px-1.5 py-0.5 bg-white text-gray-700 font-medium focus:outline-none focus:border-black">
                                            ${sizeOptions}
                                        </select>
                                    </div>
                                </div>

                                <p class="text-xs font-medium text-gray-800">${item.harga}</p>
                            </div>
                        </div>
                        
                        <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100 gap-2">
                            <span class="text-xs font-bold text-gray-900">Rp${totalItemHarga.toLocaleString('id-ID')}</span>
                            
                            <div class="flex items-center gap-3">
                                <!-- Tombol Remove dipindah ke sebelah kiri -->
                                <button type="button" onclick="removeItem(${index})" class="text-[10px] text-red-600 hover:underline">Remove</button>

                                <!-- Kontrol Kuantitas Plus / Minus dipindah ke sebelah kanan -->
                                <div class="flex items-center border border-gray-200 bg-white rounded text-xs">
                                    <button type="button" onclick="updateQty(${index}, -1)" class="px-2 py-1 text-gray-500 hover:text-black">-</button>
                                    <span class="px-2 py-1 font-medium">${item.qty}</span>
                                    <button type="button" onclick="updateQty(${index}, 1)" class="px-2 py-1 text-gray-500 hover:text-black">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            subtotalEl.innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
        }

        function updateQty(index, delta) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart[index].qty += delta;

            if (cart[index].qty <= 0) {
                let namaProduk = cart[index].nama;
                if (confirm(`Yakin nih mau ngapus "${namaProduk}" dari shopping bag lu, cuk?`)) {
                    cart.splice(index, 1);
                } else {
                    cart[index].qty = 1;
                }
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartPage();
        }

        function updateVariant(index, field, value) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart[index][field] = value;
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let namaProduk = cart[index].nama;

            if (confirm(`Yakin nih mau ngapus "${namaProduk}" dari shopping bag lu, cuk?`)) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartPage();
            }
        }

        function proceedCheckout() {
            @guest
            alert('Silakan login terlebih dahulu untuk melanjutkan proses checkout, cuk!');
            window.location.href = "{{ route('login') }}";
        @else
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                alert('Keranjang belanja lu masih kosong, bro!');
                return;
            }

            // Langsung arahkan ke halaman checkout
            window.location.href = "{{ route('checkout') }}";
        @endguest
        }
    </script>

</body>

</html>
