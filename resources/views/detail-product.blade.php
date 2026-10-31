<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product['nama'] }} - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative overflow-x-hidden">

    <!-- SEARCH BACKDROP (Z-INDEX 40 SUPAYA MENAHAN KLIK LAYAR BELAKANG) -->
    <div id="search-backdrop" onclick="closeSearchOverlay()"
        class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity"></div>

    <!-- 1. NAVBAR & SEARCH SECTION -->
    <div class="sticky top-0 z-50 bg-white">
        <!-- NAVBAR UTAMA -->
        <nav
            class="border-b border-gray-200 px-4 md:px-12 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 w-full">

                <!-- LOGO KIRI & MOBILE MENU -->
                <div class="flex items-center justify-between w-full md:w-auto">
                    <a href="{{ route('home') }}"
                        class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition active:scale-95 inline-block">ASYART.MERCH</a>

                    <!-- Ikon & Menu Auth Mobile -->
                    <div class="flex items-center gap-2 md:hidden">
                        <a href="{{ route('cart.page') }}"
                            class="relative hover:opacity-75 active:scale-90 transition duration-150 mr-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span id="cart-count-mobile"
                                class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                        </a>

                        @guest
                            <a href="{{ route('login') }}"
                                class="text-xs font-medium border border-black text-gray-900 bg-transparent px-2.5 py-1 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200 inline-block">
                                Login
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}"
                                class="text-xs font-semibold text-gray-900 hover:underline active:scale-90 transition duration-150 flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </a>
                        @endguest
                    </div>
                </div>

                <!-- SEARCH BAR DESKTOP -->
                <div class="hidden md:block w-[450px] relative">
                    <form action="{{ route('search.page') }}" method="GET" class="relative flex items-center">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" id="search-input" name="q" placeholder="Search products"
                            autocomplete="off" oninput="handleSearchInput()"
                            class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">
                        <button type="button" id="clear-search-btn" onclick="clearSearchInput()"
                            class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-sm font-bold cursor-pointer">×</button>
                    </form>
                </div>

                <!-- Auth Navbar Desktop -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('cart.page') }}"
                        class="relative hover:opacity-75 active:scale-90 transition duration-150 mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-count-desktop"
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                    </a>

                    @guest
                        <a href="{{ route('login') }}"
                            class="text-xs font-medium border border-black text-gray-900 bg-transparent px-3 py-1.5 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200 inline-block">
                            Login
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}"
                            class="text-xs font-semibold text-gray-900 hover:underline active:scale-95 transition duration-150 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ Auth::user()->name }}
                        </a>
                    @endguest
                </div>

            </div>
        </nav>

        <!-- SEARCH BAR KHUSUS MOBILE -->
        <div class="px-4 py-2 md:hidden bg-white border-b border-gray-100 relative">
            <form action="{{ route('search.page') }}" method="GET" class="w-full relative">
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input-mobile" name="q" placeholder="Search products"
                        autocomplete="off" oninput="handleSearchInput()"
                        class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">
                    <button type="button" id="clear-search-btn-mobile" onclick="clearSearchInput()"
                        class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-sm font-bold cursor-pointer">×</button>
                </div>
            </form>
        </div>

        <!-- SHARED SEARCH OVERLAY DROPDOWN -->
        <div id="search-overlay-box"
            class="absolute top-full left-4 right-4 md:left-1/2 md:-translate-x-1/2 md:w-[700px] mt-2 bg-white border border-gray-200 shadow-2xl rounded-lg p-4 md:p-5 z-50 hidden max-h-[380px] overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 text-xs text-gray-800">
                <div class="space-y-2 md:space-y-3">
                    <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">SUGGESTIONS</h4>
                    <ul id="suggestions-list" class="space-y-1.5 md:space-y-2"></ul>
                </div>

                <div class="space-y-3 md:space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5 md:mb-2">
                            <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">RECENT
                                SEARCHES</h4>
                            <button onclick="clearAllRecentSearches()"
                                class="text-[10px] text-gray-400 hover:text-black underline cursor-pointer">Clear</button>
                        </div>
                        <ul id="recent-searches-list" class="space-y-1 md:space-y-1.5"></ul>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 md:mb-2">
                            POPULAR</h4>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" onclick="applySearchTag('Long Sleeve')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Long
                                Sleeve</button>
                            <button type="button" onclick="applySearchTag('Short Sleeve')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Short
                                Sleeve</button>
                            <button type="button" onclick="applySearchTag('Baby Tee')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Baby
                                Tee</button>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 md:space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">PRODUCTS</h4>
                        <a href="javascript:void(0)" onclick="closeSearchOverlay()"
                            class="text-[10px] text-black font-semibold underline">View all <span
                                id="search-result-count">0</span> results</a>
                    </div>
                    <div id="search-products-preview" class="space-y-2 max-h-36 md:max-h-48 overflow-y-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTAINER UTAMA DETAIL PRODUK -->
    <main class="max-w-6xl mx-auto px-4 md:px-12 py-8 space-y-16">
        <!-- Breadcrumb / Back -->
        <div>
            <a href="{{ route('home') }}"
                class="text-xs text-gray-500 hover:text-black mb-6 inline-flex items-center gap-1">
                ← Back to Catalog
            </a>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-start">

                <!-- KIRI: KOLOM GAMBAR & THUMBNAIL -->
                <div class="space-y-4">
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative border border-gray-100">
                        <img id="main-product-img" src="{{ asset('images/' . $product['gambar_hitam']) }}"
                            alt="{{ $product['nama'] }}" class="w-full h-full object-cover transition duration-300">
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" id="thumb-black"
                            onclick="changeImage('{{ asset('images/' . $product['gambar_hitam']) }}', 'Black', this)"
                            class="thumb-btn w-16 h-16 rounded-md border-2 border-black overflow-hidden focus:outline-none bg-gray-50">
                            <img src="{{ asset('images/' . $product['gambar_hitam']) }}"
                                class="w-full h-full object-cover">
                        </button>
                        <button type="button" id="thumb-white"
                            onclick="changeImage('{{ asset('images/' . $product['gambar_putih']) }}', 'White', this)"
                            class="thumb-btn w-16 h-16 rounded-md border border-gray-200 opacity-60 overflow-hidden focus:outline-none bg-gray-50">
                            <img src="{{ asset('images/' . $product['gambar_putih']) }}"
                                class="w-full h-full object-cover">
                        </button>
                    </div>
                </div>

                <!-- KANAN: INFORMASI & AKSI PRODUK -->
                <div class="space-y-6 text-xs text-gray-800">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-serif font-medium text-gray-900 mb-1">
                            {{ $product['nama'] }}
                        </h1>
                        <p class="text-gray-400 uppercase tracking-wider text-[10px] mb-2">{{ $product['kategori'] }}
                        </p>
                        <p class="text-xl font-semibold text-gray-900">{{ $product['harga'] }}</p>
                    </div>

                    <!-- Rating Review -->
                    <div class="flex items-center gap-2 pb-4 border-b border-gray-100">
                        <div class="flex text-black">★★★★★</div>
                        <span class="font-semibold text-gray-900 text-xs">4.9</span>
                        <span class="text-gray-400 text-[11px]">(128 reviews)</span>
                    </div>

                    <!-- Pilih Warna (Colour) -->
                    <div class="space-y-2">
                        <span class="font-semibold text-gray-900 uppercase tracking-wider text-[10px] block">
                            COLOUR: <span id="selected-color-label" class="font-normal text-gray-600">Black</span>
                        </span>
                        <div class="flex items-center gap-3">
                            <button type="button" id="color-btn-black"
                                onclick="selectColor('Black', '{{ asset('images/' . $product['gambar_hitam']) }}', this)"
                                class="color-btn w-7 h-7 rounded-full bg-black ring-2 ring-black ring-offset-1 focus:outline-none"
                                title="Black"></button>
                            <button type="button" id="color-btn-white"
                                onclick="selectColor('White', '{{ asset('images/' . $product['gambar_putih']) }}', this)"
                                class="color-btn w-7 h-7 rounded-full bg-white border border-gray-300 focus:outline-none opacity-60"
                                title="White"></button>
                        </div>
                    </div>

                    <!-- Pilih Ukuran (Size) & Stok Dinamis -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-[10px] uppercase font-semibold">
                            <span>SELECT SIZE</span>
                            <a href="javascript:void(0)" class="text-gray-400 underline">Size Guide</a>
                        </div>
                        <div class="flex flex-wrap gap-2" id="size-buttons-container">
                            <!-- Dirender dinamis lewat JS berdasarkan stok -->
                        </div>
                    </div>

                    <!-- Informasi Stok Real-Time -->
                    <div class="bg-gray-50 border border-gray-100 p-3 rounded-md">
                        <p class="text-xs text-gray-600 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500" id="stock-dot"></span>
                            <span id="stock-status-text">Pilih ukuran untuk melihat ketersediaan stok.</span>
                        </p>
                    </div>

                    <!-- Kuantitas & Tombol Add to Bag -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-gray-200 rounded text-sm">
                                <button type="button" onclick="changeQty(-1)"
                                    class="px-3 py-2 text-gray-500 hover:text-black">-</button>
                                <span id="qty-val" class="px-3 py-2 font-medium">1</span>
                                <button type="button" onclick="changeQty(1)"
                                    class="px-3 py-2 text-gray-500 hover:text-black">+</button>
                            </div>
                            <button type="button" id="add-to-bag-btn" onclick="addToBag()" disabled
                                class="flex-1 bg-gray-300 text-gray-500 py-3.5 rounded font-medium text-xs transition cursor-not-allowed">
                                Select Size First
                            </button>
                        </div>
                    </div>

                    <!-- Deskripsi Accordion -->
                    <div class="border-t border-gray-100 pt-4 space-y-3 text-xs text-gray-600 leading-relaxed">
                        <details class="group border-b border-gray-100 pb-2" open>
                            <summary
                                class="flex justify-between items-center cursor-pointer font-medium text-gray-900 list-none">
                                <span>Description & Details</span>
                                <span class="transition group-open:rotate-180">+</span>
                            </summary>
                            <p class="mt-2 text-gray-500 text-[11px] leading-relaxed">
                                Terbuat dari 100% premium cotton combed 24s. Nyaman dipakai, awet, dan berkualitas.
                                Dibuat untuk rakyat yang setiap hari harus bekerja keras, membayar pajak, menghadapi
                                kenaikan harga, dan tetap diminta memahami keadaan.
                            </p>
                        </details>
                        <details class="group border-b border-gray-100 pb-2">
                            <summary
                                class="flex justify-between items-center cursor-pointer font-medium text-gray-900 list-none">
                                <span>Shipping & Returns</span>
                                <span class="transition group-open:rotate-180">+</span>
                            </summary>
                            <p class="mt-2 text-gray-500 text-[11px] leading-relaxed">
                                Gratis ongkir kalau lu pakai jasa pengiriman reguler cuk! pengiriman Instant hanya
                                20.000 aja.
                                Barang bisa dikembalikan dalam 7 hari setelah diterima, asal masih dalam kondisi baru,
                                belum dicuci.
                            </p>
                        </details>
                    </div>

                </div>
            </div>
        </div>

        <!-- SECTION 1: RATINGS & REVIEWS (ACAK DINAMIS) -->
        <div class="border-t border-gray-200 pt-12 space-y-8">
            <h2 class="text-xl md:text-2xl font-serif font-medium">Ratings & reviews</h2>

            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start bg-gray-50 p-6 rounded-xl border border-gray-100">
                <div class="space-y-3">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-bold font-serif">4.9</span>
                        <div class="text-black">★★★★★</div>
                    </div>
                    <p class="text-xs text-gray-500">Based on 128 verified customer reviews</p>
                    <button type="button" onclick="alert('Form tulis ulasan akan segera dibuka!')"
                        class="border border-black bg-black text-white px-5 py-2.5 rounded text-xs font-medium hover:bg-gray-800 transition">
                        Write a review
                    </button>
                </div>

                <div id="dynamic-reviews-container" class="md:col-span-2 space-y-6"></div>
            </div>
        </div>

        <!-- SECTION 2: YOU MAY ALSO LIKE (ACAK DINAMIS) -->
        <div class="border-t border-gray-200 pt-12 space-y-6">
            <h2 class="text-xl md:text-2xl font-serif font-medium">You may also like</h2>
            <div id="dynamic-recommendations" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6"></div>
        </div>
    </main>

    <!-- SCRIPT JAVASCRIPT MASTER UTAMA -->
    <script>
        // Data Stok Dummy (Struktur siap konfigurasi Backend Laravel)
        const productStockData = {
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
        };

        let selectedColor = 'Black';
        let selectedSize = null;
        let quantity = 1;
        let maxStock = 0;
        let scrollPosition = 0;
        let recentSearches = ['Long Sleeve', 'Baby Tee', 'Baby Tee Bang Windah'];

        // Pool Data Ulasan & Rekomendasi
        const poolReviews = [{
                nama: "Rizky Pratama",
                waktu: "2 days ago",
                bintang: "★★★★★",
                teks: "Bahan kaosnya tebel tapi adem banget dipake seharian, sablonannya juga rapi parah!"
            },
            {
                nama: "Dinda Kirana",
                waktu: "1 week ago",
                bintang: "★★★★★",
                teks: "Pengiriman cepet banget, ukuran M pas di badan, warnanya cakep ga luntur pas dicuci."
            },
            {
                nama: "Fajar Nugraha",
                waktu: "3 days ago",
                bintang: "★★★★★",
                teks: "Mantap jiwa, cutting-an streetwear-nya pas banget di badan. Bakal order warna putihnya juga nih."
            },
            {
                nama: "Siti Rahma",
                waktu: "5 days ago",
                bintang: "★★★★☆",
                teks: "Kualitas sablon mantap, cuma kemarin sempat agak lama di kurir pengiriman."
            },
            {
                nama: "Gilang Ramadhan",
                waktu: "2 weeks ago",
                bintang: "★★★★★",
                teks: "Gokil sih merch-nya Bang Windah, bahan cotton combed-nya beneran premium."
            }
        ];

        const poolProducts = [{
                id: 0,
                nama: "Baby Tee Bang Windah",
                kategori: "Baby Tee",
                harga: "Rp90.000",
                gambar: "{{ asset('images/windahcrop-htm.jpg') }}"
            },
            {
                id: 1,
                nama: "Short Sleeve Bocil Bang Windah",
                kategori: "Short Sleeve",
                harga: "Rp85.000",
                gambar: "{{ asset('images/windahreg-htm.jpg') }}"
            },
            {
                id: 2,
                nama: "Longsleeve Bang Windah",
                kategori: "Long Sleeve",
                harga: "Rp110.000",
                gambar: "{{ asset('images/windahpnjg-htm.jpg') }}"
            },
            {
                id: 3,
                nama: "Baby Tee Snoopy",
                kategori: "Baby Tee",
                harga: "Rp90.000",
                gambar: "{{ asset('images/snpcrop-htm.jpg') }}"
            },
            {
                id: 4,
                nama: "Baby Tee Radio Head",
                kategori: "Baby Tee",
                harga: "Rp85.000",
                gambar: "{{ asset('images/radiocrop-htm.jpg') }}"
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCountUI();
            renderSizeOptions();
            updateStockUI();
            generateRandomContent();
            renderRecentSearches();
            renderSuggestions('');

            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');

            if (desktopInput) desktopInput.addEventListener('focus', openSearchOverlay);
            if (mobileInput) mobileInput.addEventListener('focus', openSearchOverlay);
        });

        // Fungsi Kunci Total Body Scroll (Fixed Position Trik)
        function lockScroll() {
            if (document.body.style.position === 'fixed') return;
            scrollPosition = window.pageYOffset;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
        }

        function unlockScroll() {
            if (document.body.style.position !== 'fixed') return;
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('position');
            document.body.style.removeProperty('top');
            document.body.style.removeProperty('width');
            window.scrollTo(0, scrollPosition);
        }

        function openSearchOverlay() {
            document.getElementById('search-overlay-box').classList.remove('hidden');
            document.getElementById('search-backdrop').classList.remove('hidden');
            lockScroll();

            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            const activeVal = document.activeElement === mobileInput ? mobileInput.value : (desktopInput ? desktopInput
                .value : '');
            renderSuggestions(activeVal);
        }

        function closeSearchOverlay() {
            const overlayBox = document.getElementById('search-overlay-box');
            const backdrop = document.getElementById('search-backdrop');

            if (overlayBox) overlayBox.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');
            unlockScroll();
        }

        function handleSearchInput() {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            const clearBtnDesktop = document.getElementById('clear-search-btn');
            const clearBtnMobile = document.getElementById('clear-search-btn-mobile');

            const query = (document.activeElement === mobileInput) ? mobileInput.value : (desktopInput ? desktopInput
                .value : '');

            if (desktopInput && mobileInput) {
                if (document.activeElement === mobileInput) desktopInput.value = mobileInput.value;
                if (document.activeElement === desktopInput) mobileInput.value = desktopInput.value;
            }

            if (query.length > 0) {
                if (clearBtnDesktop) clearBtnDesktop.style.display = 'flex';
                if (clearBtnMobile) clearBtnMobile.style.display = 'flex';
            } else {
                if (clearBtnDesktop) clearBtnDesktop.style.display = 'none';
                if (clearBtnMobile) clearBtnMobile.style.display = 'none';
            }

            renderSuggestions(query);
        }

        function clearSearchInput() {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            if (desktopInput) desktopInput.value = '';
            if (mobileInput) mobileInput.value = '';
            renderSuggestions('');
        }

        function renderRecentSearches() {
            const container = document.getElementById('recent-searches-list');
            if (!container) return;

            if (recentSearches.length === 0) {
                container.innerHTML = '<li class="text-gray-400 text-[11px]">No recent searches</li>';
                return;
            }

            container.innerHTML = recentSearches.map((item, idx) => `
                <li class="flex items-center justify-between text-gray-600 hover:text-black group">
                    <span onclick="applySearchTag('${item}')" class="cursor-pointer flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ${item}
                    </span>
                    <button type="button" onclick="removeRecentSearch(${idx})" class="text-gray-300 hover:text-black text-xs font-bold cursor-pointer">×</button>
                </li>
            `).join('');
        }

        function removeRecentSearch(idx) {
            recentSearches.splice(idx, 1);
            renderRecentSearches();
        }

        function clearAllRecentSearches() {
            recentSearches = [];
            renderRecentSearches();
        }

        function renderSuggestions(query) {
            const sugContainer = document.getElementById('suggestions-list');
            const prevContainer = document.getElementById('search-products-preview');
            const resultCountEl = document.getElementById('search-result-count');

            if (!sugContainer || !prevContainer) return;

            const q = (query || '').toLowerCase().trim();
            const matchingProducts = poolProducts.filter(p => p.nama.toLowerCase().includes(q) || p.kategori.toLowerCase()
                .includes(q));

            if (q === '') {
                sugContainer.innerHTML = `
                    <li onclick="applySearchTag('Longsleeve Bang Windah')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Longsleeve Bang Windah</span><span class="text-gray-400">↗</span>
                    </li>
                    <li onclick="applySearchTag('Baby Tee Snoopy')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Baby Tee Snoopy</span><span class="text-gray-400">↗</span>
                    </li>
                `;
            } else {
                sugContainer.innerHTML = matchingProducts.slice(0, 4).map(p => `
                    <li onclick="applySearchTag('${p.nama}')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 ${p.nama}</span><span class="text-gray-400">↗</span>
                    </li>
                `).join('') || '<li class="text-gray-400">No suggestions found</li>';
            }

            if (resultCountEl) resultCountEl.innerText = matchingProducts.length;

            prevContainer.innerHTML = matchingProducts.slice(0, 3).map(p => `
                <a href="/product/${p.id}" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer block">
                    <img src="${p.gambar}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${p.nama}</h5>
                        <p class="text-[10px] text-gray-400">${p.kategori}</p>
                        <p class="text-[11px] font-semibold text-gray-800">${p.harga}</p>
                    </div>
                </a>
            `).join('') || '<p class="text-gray-400 text-xs">No matching products</p>';
        }

        function applySearchTag(tagText) {
            window.location.href = "{{ route('search.page') }}?q=" + encodeURIComponent(tagText);
        }

        // Render Size & Stok Berdasarkan Warna Aktif
        function renderSizeOptions() {
            const container = document.getElementById('size-buttons-container');
            const availableSizes = ["XS", "S", "M", "L", "XL"];
            const colorStock = productStockData[selectedColor] || {};

            container.innerHTML = availableSizes.map(size => {
                const stock = colorStock[size] ?? 0;
                const isOutOfStock = stock === 0;
                const isSelected = selectedSize === size;

                let btnClasses = "size-btn border px-3.5 py-2 text-center text-xs rounded transition font-medium ";

                if (isOutOfStock) {
                    btnClasses += "border-gray-100 bg-gray-50 text-gray-300 cursor-not-allowed line-through";
                } else if (isSelected) {
                    btnClasses += "border-black bg-black text-white";
                } else {
                    btnClasses += "border-gray-200 text-gray-800 hover:border-black cursor-pointer";
                }

                return `
                    <button type="button" onclick="${isOutOfStock ? '' : `selectSize('${size}')`}" 
                        class="${btnClasses}">${size}</button>
                `;
            }).join('');
        }

        function selectSize(sizeName) {
            selectedSize = sizeName;
            renderSizeOptions();
            updateStockUI();
        }

        function updateStockUI() {
            const stockStatusText = document.getElementById('stock-status-text');
            const stockDot = document.getElementById('stock-dot');
            const addToBagBtn = document.getElementById('add-to-bag-btn');
            const qtyValEl = document.getElementById('qty-val');

            quantity = 1;
            qtyValEl.innerText = quantity;

            if (!selectedSize) {
                stockStatusText.innerText = `Pilih ukuran untuk melihat ketersediaan stok (${selectedColor}).`;
                stockDot.className = "w-2 h-2 rounded-full bg-yellow-500";
                addToBagBtn.disabled = true;
                addToBagBtn.className =
                    "flex-1 bg-gray-300 text-gray-500 py-3.5 rounded font-medium text-xs transition cursor-not-allowed";
                addToBagBtn.innerText = "Select Size First";
                return;
            }

            maxStock = productStockData[selectedColor][selectedSize] || 0;

            if (maxStock > 0) {
                stockStatusText.innerHTML =
                    `Stok tersedia: <span class="font-semibold text-gray-900">${maxStock} pcs</span> untuk ukuran ${selectedSize} (${selectedColor})`;
                stockDot.className = "w-2 h-2 rounded-full bg-green-500";
                addToBagBtn.disabled = false;
                addToBagBtn.className =
                    "flex-1 bg-black text-white py-3.5 rounded font-medium text-xs hover:bg-gray-800 active:scale-95 transition cursor-pointer";
                addToBagBtn.innerText = "Add to Bag";
            } else {
                stockStatusText.innerText = `Maaf, stok ukuran ${selectedSize} warna ${selectedColor} habis.`;
                stockDot.className = "w-2 h-2 rounded-full bg-red-500";
                addToBagBtn.disabled = true;
                addToBagBtn.className =
                    "flex-1 bg-gray-300 text-gray-500 py-3.5 rounded font-medium text-xs transition cursor-not-allowed";
                addToBagBtn.innerText = "Out of Stock";
            }
        }

        // Sinkronisasi Thumbnail & Warna
        function changeImage(imgUrl, colorName, element) {
            document.getElementById('main-product-img').src = imgUrl;

            const thumbBtns = document.querySelectorAll('.thumb-btn');
            thumbBtns.forEach(btn => {
                btn.classList.remove('border-2', 'border-black');
                btn.classList.add('border', 'border-gray-200', 'opacity-60');
            });
            if (element) {
                element.classList.add('border-2', 'border-black');
                element.classList.remove('border-gray-200', 'opacity-60');
            }

            if (colorName) {
                selectedColor = colorName;
                document.getElementById('selected-color-label').innerText = colorName;

                const btnBlack = document.getElementById('color-btn-black');
                const btnWhite = document.getElementById('color-btn-white');

                if (colorName === 'Black') {
                    if (btnBlack) {
                        btnBlack.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                        btnBlack.classList.remove('opacity-60');
                    }
                    if (btnWhite) {
                        btnWhite.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        btnWhite.classList.add('opacity-60');
                    }
                } else {
                    if (btnWhite) {
                        btnWhite.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                        btnWhite.classList.remove('opacity-60');
                    }
                    if (btnBlack) {
                        btnBlack.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        btnBlack.classList.add('opacity-60');
                    }
                }

                selectedSize = null;
                renderSizeOptions();
                updateStockUI();
            }
        }

        function selectColor(colorName, imgUrl, element) {
            selectedColor = colorName;
            document.getElementById('selected-color-label').innerText = colorName;

            const colorBtns = document.querySelectorAll('.color-btn');
            colorBtns.forEach(btn => {
                btn.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                btn.classList.add('opacity-60');
            });
            if (element) {
                element.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                element.classList.remove('opacity-60');
            }

            const targetThumbId = colorName === 'Black' ? 'thumb-black' : 'thumb-white';
            const thumbEl = document.getElementById(targetThumbId);
            if (thumbEl) {
                document.getElementById('main-product-img').src = imgUrl;

                const thumbBtns = document.querySelectorAll('.thumb-btn');
                thumbBtns.forEach(btn => {
                    btn.classList.remove('border-2', 'border-black');
                    btn.classList.add('border', 'border-gray-200', 'opacity-60');
                });
                thumbEl.classList.add('border-2', 'border-black');
                thumbEl.classList.remove('border-gray-200', 'opacity-60');
            }

            selectedSize = null;
            renderSizeOptions();
            updateStockUI();
        }

        function changeQty(delta) {
            if (!selectedSize || maxStock <= 0) return;
            quantity = Math.min(maxStock, Math.max(1, quantity + delta));
            document.getElementById('qty-val').innerText = quantity;
        }

        function generateRandomContent() {
            const shuffledReviews = [...poolReviews].sort(() => 0.5 - Math.random()).slice(0, 2);
            const reviewContainer = document.getElementById('dynamic-reviews-container');
            if (reviewContainer) {
                reviewContainer.innerHTML = shuffledReviews.map(rev => `
                    <div class="border-b border-gray-200 pb-4 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">${rev.nama}</span>
                            <span class="text-[10px] text-gray-400">${rev.waktu}</span>
                        </div>
                        <div class="text-black text-xs">${rev.bintang}</div>
                        <p class="text-xs text-gray-600">${rev.teks}</p>
                    </div>
                `).join('');
            }

            const currentProductId = {{ request()->route('id') ?? 0 }};
            const filteredProducts = poolProducts.filter(p => p.id !== currentProductId);
            const shuffledProducts = [...filteredProducts].sort(() => 0.5 - Math.random()).slice(0, 4);
            const recommendationContainer = document.getElementById('dynamic-recommendations');

            if (recommendationContainer) {
                recommendationContainer.innerHTML = shuffledProducts.map(prod => `
                    <a href="/product/${prod.id}" class="group block space-y-2">
                        <div class="aspect-square bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                            <img src="${prod.gambar}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div>
                            <h4 class="text-xs font-medium text-gray-900 group-hover:underline">${prod.nama}</h4>
                            <p class="text-[10px] text-gray-400">${prod.kategori}</p>
                            <p class="text-xs font-semibold text-gray-900 mt-0.5">${prod.harga}</p>
                        </div>
                    </a>
                `).join('');
            }
        }

        function updateCartCountUI() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            const badgeDesktop = document.getElementById('cart-count-desktop');
            const badgeMobile = document.getElementById('cart-count-mobile');
            if (badgeDesktop) badgeDesktop.innerText = totalItems;
            if (badgeMobile) badgeMobile.innerText = totalItems;
        }

        function addToBag() {
            if (!selectedSize || maxStock <= 0) return;

            const productName = "{{ $product['nama'] }}";
            const productCat = "{{ $product['kategori'] }}";
            const productPrice = "{{ $product['harga'] }}";
            const productImg = selectedColor === 'Black' ? "{{ asset('images/' . $product['gambar_hitam']) }}" :
                "{{ asset('images/' . $product['gambar_putih']) }}";

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let existingItem = cart.find(item => item.nama === productName && item.warna === selectedColor && item
                .ukuran === selectedSize);

            if (existingItem) {
                existingItem.qty = Math.min(maxStock, existingItem.qty + quantity);
            } else {
                cart.push({
                    nama: productName,
                    kategori: productCat,
                    harga: productPrice,
                    gambar: productImg,
                    warna: selectedColor,
                    ukuran: selectedSize,
                    qty: quantity
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCountUI();
            showToast();
        }

        function showToast() {
            let toast = document.getElementById('cart-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'cart-toast';
                toast.className =
                    "fixed top-20 right-12 bg-white border border-gray-200 text-xs p-4 rounded-lg shadow-2xl z-50 w-80 transition-all duration-300 transform translate-y-[-10px] opacity-0";
                document.body.appendChild(toast);
            }

            const productName = "{{ $product['nama'] }}";
            const productPrice = "{{ $product['harga'] }}";
            const productImg = selectedColor === 'Black' ? "{{ asset('images/' . $product['gambar_hitam']) }}" :
                "{{ asset('images/' . $product['gambar_putih']) }}";

            toast.innerHTML = `
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                    <span class="font-semibold text-gray-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Added to your bag
                    </span>
                    <button type="button" onclick="document.getElementById('cart-toast').classList.add('opacity-0', 'translate-y-[-10px]')" class="text-gray-400 hover:text-black font-bold">×</button>
                </div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="${productImg}" class="w-12 h-12 object-cover rounded bg-gray-100 border border-gray-200">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 leading-tight">${productName}</h4>
                        <p class="text-[10px] text-gray-400">Colour: ${selectedColor} | Size: ${selectedSize}</p>
                        <p class="font-semibold text-gray-800 mt-0.5">${productPrice}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1">
                    <a href="{{ route('cart.page') }}" class="bg-black text-white py-2.5 rounded text-center font-medium hover:bg-gray-800 transition">Checkout</a>
                </div>
            `;

            setTimeout(() => {
                toast.classList.remove('translate-y-[-10px]', 'opacity-0');
            }, 10);
        }
    </script>

</body>
    
</html>
