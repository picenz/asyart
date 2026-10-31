<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Bag - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative">

    <!-- NAVBAR -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <nav class="max-w-7xl mx-auto px-4 md:px-12 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider font-serif">ASYART.MERCH</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('cart.page') }}" class="relative hover:opacity-75 transition duration-150 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <!-- COUNTER JUMLAH BARIS ITEM UNIK -->
                    <span id="cart-count-desktop"
                        class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                </a>
                <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-black">← Continue Shopping</a>
            </div>
        </nav>
    </div>

    <!-- CONTAINER UTAMA KERANJANG -->
    <main class="max-w-4xl mx-auto px-4 md:px-12 py-8">
        <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm space-y-6">

            <!-- HEADER & TOMBOL CLEAR CART DENGAN IKON -->
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-serif font-medium">Your Bag</h1>
                    <span id="bag-item-count" class="text-xs text-gray-400">0 items</span>
                </div>

                <!-- Tombol Kosongkan Keranjang (Clear All) -->
                <button type="button" id="clear-cart-trigger-btn" onclick="openClearCartModal()"
                    class="hidden items-center gap-1.5 text-xs text-red-500 hover:text-red-700 font-medium transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Cart
                </button>
            </div>

            <!-- List Item di Keranjang -->
            <div id="cart-items-container" class="divide-y divide-gray-100 space-y-4">
                <!-- Dirender dinamis via JavaScript -->
            </div>

            <!-- Bagian Subtotal & Checkout -->
            <div id="cart-summary-section" class="pt-4 border-t border-gray-100 space-y-4 hidden">
                <div class="flex justify-between items-center text-sm font-semibold">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">Rp0</span>
                </div>
                <button type="button" onclick="checkout()"
                    class="w-full bg-black text-white py-3.5 rounded text-xs font-medium hover:bg-gray-800 active:scale-95 transition cursor-pointer">
                    Checkout
                </button>
            </div>

        </div>
    </main>

    <!-- MODAL KONFIRMASI HAPUS SATU ITEM -->
    <div id="delete-modal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center px-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full space-y-4 shadow-2xl text-center">
            <h3 class="text-sm font-semibold text-gray-900">Hapus Produk?</h3>
            <p class="text-xs text-gray-500">Yakin nih mau ngapus produk ini dari keranjang lu? Ntar kehabisan lho.</p>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-1/2 border border-gray-300 py-2.5 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="button" id="confirm-delete-btn"
                    class="w-1/2 bg-red-600 text-white py-2.5 rounded text-xs font-medium hover:bg-red-700 transition">Hapus</button>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI KOSONGKAN SEMUA KERANJANG (CLEAR CART) -->
    <div id="clear-cart-modal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center px-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full space-y-4 shadow-2xl text-center">
            <h3 class="text-sm font-semibold text-gray-900">Kosongkan Keranjang?</h3>
            <p class="text-xs text-gray-500">Yakin ingin mengosongkan keranjang belanjaan? Semua produk yang udah
                dimasukin bakal kehapus lho.</p>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeClearCartModal()"
                    class="w-1/2 border border-gray-300 py-2.5 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="button" id="confirm-clear-cart-btn" onclick="clearEntireCart()"
                    class="w-1/2 bg-red-600 text-white py-2.5 rounded text-xs font-medium hover:bg-red-700 transition">Kosongkan</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT MASTER SINKRONISASI -->
    <script>
        // Database Stok Master Lengkap & Presisi Per Produk (Siap Config Backend)
        const productStockData = {
            "Baby Tee Snoopy": {
                "Black": {
                    "XS": 5,
                    "S": 12,
                    "M": 0,
                    "L": 8,
                    "XL": 3
                },
                "White": {
                    "XS": 2,
                    "S": 0,
                    "M": 15,
                    "L": 4,
                    "XL": 10
                }
            },
            "Baby Tee Bang Windah": {
                "Black": {
                    "XS": 4,
                    "S": 10,
                    "M": 5,
                    "L": 2,
                    "XL": 1
                },
                "White": {
                    "XS": 3,
                    "S": 5,
                    "M": 8,
                    "L": 6,
                    "XL": 2
                }
            },
            "Short Sleeve Bocil Bang Windah": {
                "Black": {
                    "XS": 2,
                    "S": 8,
                    "M": 10,
                    "L": 5,
                    "XL": 4
                },
                "White": {
                    "XS": 1,
                    "S": 4,
                    "M": 7,
                    "L": 3,
                    "XL": 2
                }
            },
            "Longsleeve Bang Windah": {
                "Black": {
                    "XS": 3,
                    "S": 6,
                    "M": 9,
                    "L": 4,
                    "XL": 2
                },
                "White": {
                    "XS": 2,
                    "S": 5,
                    "M": 6,
                    "L": 3,
                    "XL": 1
                }
            }
        };

        const availableSizes = ["XS", "S", "M", "L", "XL"];
        const availableColors = ["Black", "White"];
        let itemIndexToDelete = null;

        document.addEventListener('DOMContentLoaded', () => {
            cleanAndSyncCartData();
            renderCart();
            updateCartCountUI();
        });

        // Auto-correct nama lama (Crop Top -> Baby Tee) di localStorage
        function cleanAndSyncCartData() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let updated = false;

            cart.forEach(item => {
                if (item.nama.includes("Crop Top")) {
                    item.nama = item.nama.replace("Crop Top", "Baby Tee");
                    updated = true;
                }
            });

            if (updated) {
                localStorage.setItem('cart', JSON.stringify(cart));
            }
        }

        // Fungsi Helper Pencocokan Objek Stok Sesuai Nama Produk
        function getProductStockObject(productName) {
            let cleanName = productName.trim();
            if (productStockData[cleanName]) return productStockData[cleanName];

            let matchedKey = Object.keys(productStockData).find(key => cleanName.includes(key) || key.includes(cleanName));
            return matchedKey ? productStockData[matchedKey] : productStockData["Baby Tee Snoopy"];
        }

        function getProductStock(productName, color, size) {
            let stockObj = getProductStockObject(productName);
            let colorObj = stockObj[color] || stockObj["Black"] || {};
            return colorObj[size] !== undefined ? colorObj[size] : 0;
        }

        function renderCart() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('cart-items-container');
            const summarySection = document.getElementById('cart-summary-section');
            const itemCountEl = document.getElementById('bag-item-count');
            const subtotalEl = document.getElementById('cart-subtotal');
            const clearCartBtn = document.getElementById('clear-cart-trigger-btn');

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="py-12 text-center space-y-3">
                        <p class="text-xs text-gray-400">Keranjang belanjaan lu masih kosong nih cuk.</p>
                        <a href="{{ route('home') }}" class="inline-block bg-black text-white text-xs px-6 py-2.5 rounded hover:bg-gray-800 transition">Mulai Belanja</a>
                    </div>
                `;
                summarySection.classList.add('hidden');
                if (clearCartBtn) clearCartBtn.classList.add('hidden');
                itemCountEl.innerText = '0 items';
                updateCartCountUI();
                return;
            }

            summarySection.classList.remove('hidden');
            if (clearCartBtn) {
                clearCartBtn.classList.remove('hidden');
                clearCartBtn.classList.add('flex');
            }

            let totalUniqueItems = cart.length;
            itemCountEl.innerText = `${totalUniqueItems} item${totalUniqueItems > 1 ? 's' : ''}`;

            let subtotal = 0;

            container.innerHTML = cart.map((item, index) => {
                let hargaAngka = parseInt(item.harga.replace(/[^0-9]/g, '')) || 0;

                let productStockObj = getProductStockObject(item.nama);
                let colorStock = productStockObj[item.warna] || {};
                let maxStock = colorStock[item.ukuran] !== undefined ? colorStock[item.ukuran] : 0;

                if (maxStock === 0) {
                    let availableSize = availableSizes.find(s => (colorStock[s] ?? 0) > 0);
                    if (availableSize) {
                        item.ukuran = availableSize;
                        maxStock = colorStock[availableSize];
                    }
                }

                if (item.qty > maxStock) {
                    item.qty = maxStock > 0 ? maxStock : 1;
                }

                let itemTotal = hargaAngka * item.qty;
                subtotal += itemTotal;

                return `
                    <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <img src="${item.gambar}" class="w-16 h-16 object-cover rounded bg-gray-100 border border-gray-200 shrink-0">
                            <div class="space-y-1.5 w-full">
                                <h3 class="text-xs font-semibold text-gray-900">${item.nama}</h3>
                                
                                <!-- DROPDOWN WARNA & UKURAN TERINTEGRASI STOK MERATA & LOCK KOSONG -->
                                <div class="flex flex-wrap items-center gap-3 text-[11px] text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <span>Color:</span>
                                        <select onchange="updateItemVariant(${index}, 'warna', this.value)" class="border border-gray-200 rounded px-1.5 py-0.5 text-xs bg-white font-medium cursor-pointer focus:outline-none focus:border-black">
                                            ${availableColors.map(c => `<option value="${c}" ${c === item.warna ? 'selected' : ''}>${c}</option>`).join('')}
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>Size:</span>
                                        <select onchange="updateItemVariant(${index}, 'ukuran', this.value)" class="border border-gray-200 rounded px-1.5 py-0.5 text-xs bg-white font-medium cursor-pointer focus:outline-none focus:border-black">
                                            ${availableSizes.map(s => {
                                                let stockCheck = colorStock[s] ?? 0;
                                                let isOutOfStock = stockCheck === 0;
                                                let disabledAttr = isOutOfStock ? 'disabled class="text-gray-300 bg-gray-100"' : '';
                                                return `<option value="${s}" ${s === item.ukuran ? 'selected' : ''} ${disabledAttr}>${s} ${isOutOfStock ? '(Habis)' : ''}</option>`;
                                            }).join('')}
                                        </select>
                                    </div>
                                </div>

                                <p class="text-xs font-medium text-gray-800">${item.harga}</p>
                                <p class="text-[10px] text-gray-400">Sisa stok: <span class="font-semibold text-gray-700">${maxStock} pcs</span></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full sm:w-auto gap-4 sm:gap-6 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                            <!-- Kontrol Qty -->
                            <div class="flex items-center border border-gray-200 rounded text-sm">
                                <button type="button" onclick="updateQty(${index}, -1)" class="px-2.5 py-1.5 text-gray-500 hover:text-black">-</button>
                                <span class="px-3 py-1.5 font-medium text-xs">${item.qty}</span>
                                <button type="button" onclick="updateQty(${index}, 1)" class="px-2.5 py-1.5 text-gray-500 hover:text-black">+</button>
                            </div>

                            <span class="text-xs font-semibold w-20 text-right">Rp${itemTotal.toLocaleString('id-ID')}</span>

                            <button type="button" onclick="openDeleteModal(${index})" class="text-xs text-red-500 hover:underline cursor-pointer">Remove</button>
                        </div>
                    </div>
                `;
            }).join('');

            subtotalEl.innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCountUI();
        }

        // Sinkronisasi Varian & Gambar Presisi Masing-Masing Produk
        function updateItemVariant(index, field, value) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let item = cart[index];

            item[field] = value;

            if (field === 'warna') {
                if (item.nama.includes('Snoopy')) {
                    item.gambar = value === 'Black' ? "{{ asset('images/snpcrop-htm.jpg') }}" :
                        "{{ asset('images/snpcrop-pth.jpg') }}";
                } else if (item.nama.includes('Baby Tee') || item.nama.includes('Windah') && item.nama.includes('Baby')) {
                    item.gambar = value === 'Black' ? "{{ asset('images/windahcrop-htm.jpg') }}" :
                        "{{ asset('images/windahcrop-pth.jpg') }}";
                } else if (item.nama.includes('Short Sleeve') || item.nama.includes('Regular')) {
                    item.gambar = value === 'Black' ? "{{ asset('images/windahreg-htm.jpg') }}" :
                        "{{ asset('images/windahreg-pth.jpg') }}";
                } else if (item.nama.includes('Longsleeve')) {
                    item.gambar = value === 'Black' ? "{{ asset('images/windahpnjg-htm.jpg') }}" :
                        "{{ asset('images/windahpnjg-pth.jpg') }}";
                }
            }

            let maxStock = getProductStock(item.nama, item.warna, item.ukuran);

            if (maxStock === 0) {
                alert(`Maaf, varian warna ${item.warna} ukuran ${item.ukuran} stoknya kosong!`);
                let productStockObj = getProductStockObject(item.nama);
                let colorStock = productStockObj[item.warna] || {};
                let availableSize = availableSizes.find(s => (colorStock[s] ?? 0) > 0);
                if (availableSize) {
                    item.ukuran = availableSize;
                }
            } else if (item.qty > maxStock) {
                item.qty = maxStock;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        function updateQty(index, delta) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let item = cart[index];

            let maxStock = getProductStock(item.nama, item.warna, item.ukuran);
            let newQty = item.qty + delta;

            if (newQty > maxStock) {
                alert(`Stok maksimal untuk ukuran ${item.ukuran} warna ${item.warna} tinggal ${maxStock} pcs cuk!`);
                return;
            }

            if (newQty < 1) {
                openDeleteModal(index);
                return;
            }

            item.qty = newQty;
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        // Kontrol Modal Konfirmasi Hapus Per Item
        function openDeleteModal(index) {
            itemIndexToDelete = index;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            itemIndexToDelete = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', () => {
            if (itemIndexToDelete !== null) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart.splice(itemIndexToDelete, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                closeDeleteModal();
                renderCart();
            }
        });

        // Kontrol Modal Konfirmasi Kosongkan Seluruh Keranjang (Clear Cart)
        function openClearCartModal() {
            document.getElementById('clear-cart-modal').classList.remove('hidden');
        }

        function closeClearCartModal() {
            document.getElementById('clear-cart-modal').classList.add('hidden');
        }

        function clearEntireCart() {
            localStorage.removeItem('cart');
            closeClearCartModal();
            renderCart();
        }

        // Update Badge Counter Pojok Kanan Atas
        function updateCartCountUI() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let uniqueItemsCount = cart.length;
            const badgeDesktop = document.getElementById('cart-count-desktop');
            if (badgeDesktop) badgeDesktop.innerText = uniqueItemsCount;
        }

        function checkout() {
            // Langsung arahkan ke route checkout yang bener
            window.location.href = "{{ route('checkout') }}";
        }
    </script>

</body>

</html>
