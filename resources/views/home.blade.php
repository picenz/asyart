<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYART.MERCH - Apparel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative overflow-x-hidden">

    <!-- SEARCH BACKDROP -->
    <div id="search-backdrop" onclick="closeSearchOverlay()"
        class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity"></div>

    <!-- 1. NAVBAR & SEARCH SECTION -->
    <div class="sticky top-0 z-50 bg-white">
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
                    <form action="{{ route('search') }}" method="GET" class="relative flex items-center">
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
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </a>
                    @endguest
                </div>

            </div>
        </nav>

        <!-- SEARCH BAR KHUSUS MOBILE -->
        <div class="px-4 py-2 md:hidden bg-white border-b border-gray-100 relative">
            <form action="{{ route('search') }}" method="GET" class="w-full relative">
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
                            <button type="button" onclick="clearAllRecentSearches()"
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
                        <a href="javascript:void(0)" onclick="viewAllResults()"
                            class="text-[10px] text-black font-semibold underline">View all <span
                                id="search-result-count">0</span> results</a>
                    </div>
                    <div id="search-products-preview" class="space-y-2 max-h-36 md:max-h-48 overflow-y-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTAINER UTAMA HALAMAN -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8">

        <!-- HEADER SECTION -->
        <div id="main-header-section">
            <div class="flex justify-between items-start mb-6">
                <div class="max-w-xl">
                    <h1 id="page-heading" class="text-3xl md:text-4xl font-serif mb-2">Apparel</h1>
                    <p id="page-subheading" class="text-xs text-gray-500 leading-relaxed">
                        Your favorite tees, your own kind of vibe from cute graphics to fun prints, find a tee that
                        feels just like you!
                    </p>
                </div>
                <span id="header-item-count" class="text-xs text-gray-400">{{ count($katalog) }} Items</span>
            </div>

            <!-- CHIPS FILTER AKTIF -->
            <div id="active-filters-bar" class="hidden items-center flex-wrap gap-2 mb-6">
                <div id="active-tags-list" class="flex flex-wrap items-center gap-2"></div>
                <button type="button" onclick="clearAllFilters()"
                    class="text-xs font-medium text-gray-900 underline hover:text-red-600 transition ml-2">
                    Clear all
                </button>
            </div>

            <!-- FILTER & SORT BAR -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-2 text-xs">
                    <button type="button" onclick="filterKategori('All', this)"
                        class="btn-kategori bg-black text-white px-4 py-2 rounded-md active:scale-95 transition duration-150">
                        All
                    </button>
                    <button type="button" onclick="filterKategori('Long Sleeve', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Long Sleeve
                    </button>
                    <button type="button" onclick="filterKategori('Baby Tee', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Baby Tee
                    </button>
                    <button type="button" onclick="filterKategori('Short Sleeve', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Short Sleeve
                    </button>
                </div>

                <div
                    class="flex items-center gap-6 text-xs text-gray-700 w-full sm:w-auto justify-between sm:justify-end">
                    <button type="button" onclick="openFilterDrawer()"
                        class="flex items-center gap-1.5 hover:text-black active:scale-95 transition duration-150 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filters <span id="filters-btn-count" class="font-normal text-gray-500">(0)</span>
                    </button>

                    <div class="flex items-center gap-1">
                        <span class="text-gray-400">Sort:</span>
                        <select id="sort-select" onchange="applyFiltersAndSort()"
                            class="bg-transparent font-medium cursor-pointer focus:outline-none">
                            <option value="Newest">Newest</option>
                            <option value="Price: Low to High">Price: Low to High</option>
                            <option value="Price: High to Low">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Cards Container -->
        <div id="katalog-grid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">

            @foreach ($katalog as $item)
                @php
                    $hargaAngka = (int) preg_replace('/[^0-9]/', '', $item['harga']);
                @endphp

                <div class="group produk-item" data-kategori="{{ $item['kategori'] }}"
                    data-nama="{{ $item['nama'] }}" data-harga="{{ $hargaAngka }}"
                    data-index="{{ $item['id'] }}"
                    data-gambar-hitam="{{ asset('images/' . $item['gambar_hitam']) }}"
                    data-gambar-putih="{{ asset('images/' . $item['gambar_putih']) }}">

                    <!-- Box Gambar -->
                    <a href="{{ route('product.show', $item['id']) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block cursor-pointer">
                        <img id="produk-img-{{ $item['id'] }}"
                            src="{{ asset('images/' . $item['gambar_hitam']) }}" alt="{{ $item['nama'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>

                    <!-- Tombol Warna -->
                    <div class="flex items-center gap-1.5 mb-2">
                        <button type="button"
                            onclick="pilihWarna('{{ $item['id'] }}', '{{ asset('images/' . $item['gambar_hitam']) }}', this)"
                            class="color-dot w-4 h-4 rounded-full bg-black ring-2 ring-black ring-offset-1 hover:scale-110 active:scale-95 transition cursor-pointer focus:outline-none"
                            title="Hitam">
                        </button>
                        <button type="button"
                            onclick="pilihWarna('{{ $item['id'] }}', '{{ asset('images/' . $item['gambar_putih']) }}', this)"
                            class="color-dot w-4 h-4 rounded-full bg-white border border-gray-300 hover:scale-110 active:scale-95 transition cursor-pointer focus:outline-none"
                            title="Putih">
                        </button>
                    </div>

                    <!-- Informasi Produk -->
                    <a href="{{ route('product.show', $item['id']) }}" class="block">
                        <h3 class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline cursor-pointer">
                            {{ $item['nama'] }}
                        </h3>
                    </a>

                    <p class="text-[11px] text-gray-400">{{ $item['kategori'] }}</p>
                    <p class="text-xs font-semibold mt-1">{{ $item['harga'] }}</p>
                </div>
            @endforeach

        </div>

        <!-- LOAD MORE BUTTON -->
        <div id="load-more-section" class="text-center space-y-3 pt-4 border-t border-gray-100">
            <p id="showing-text" class="text-[11px] text-gray-400">Showing {{ count($katalog) }} of {{ count($katalog) }} items</p>
            <button type="button"
                class="border border-gray-400 text-xs px-8 py-2.5 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200">
                Load more
            </button>
        </div>

    </main>

    <!-- 3. SIDEBAR / BOTTOM SHEET FILTER DRAWER PANEL -->
    <div id="filter-overlay" onclick="closeFilterDrawer()"
        class="fixed inset-0 bg-black/40 z-50 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="filter-drawer"
        class="fixed bottom-0 sm:bottom-auto sm:top-0 right-0 sm:left-auto left-0 w-full sm:w-[380px] h-[80vh] sm:h-full rounded-t-2xl sm:rounded-none bg-white z-50 shadow-2xl transform translate-y-full sm:translate-y-0 sm:translate-x-full transition-transform duration-300 flex flex-col justify-between">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-serif font-medium">Filters <span id="drawer-filter-count"
                    class="text-sm text-gray-400 font-sans">(0)</span></h2>
            <button type="button" onclick="closeFilterDrawer()"
                class="p-1 text-gray-400 hover:text-black transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-6 text-xs text-gray-800">
            <!-- Category -->
            <div class="border-b border-gray-100 pb-5">
                <div class="flex items-center justify-between mb-3 font-semibold tracking-wider text-[11px] uppercase">
                    <span>CATEGORY</span>
                    <span class="text-gray-400 font-normal">—</span>
                </div>
                <div class="space-y-2.5">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Long Sleeve" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Long Sleeve</span>
                        </div>
                        <span class="cat-count text-gray-400 text-[11px]" data-cat="Long Sleeve">0</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Short Sleeve" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Short Sleeve</span>
                        </div>
                        <span class="cat-count text-gray-400 text-[11px]" data-cat="Short Sleeve">0</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Baby Tee" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Baby Tee</span>
                        </div>
                        <span class="cat-count text-gray-400 text-[11px]" data-cat="Baby Tee">0</span>
                    </label>
                </div>
            </div>

            <!-- Size -->
            <div class="border-b border-gray-100 pb-5">
                <div class="flex items-center justify-between mb-3 font-semibold tracking-wider text-[11px] uppercase">
                    <span>SIZE</span>
                    <span class="text-gray-400 font-normal">—</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button type="button" onclick="toggleSizeBtn(this)" data-size="{{ $size }}"
                            class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">{{ $size }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Colour -->
            <div class="border-b border-gray-100 pb-5">
                <div class="flex items-center justify-between mb-3 font-semibold tracking-wider text-[11px] uppercase">
                    <span>COLOUR</span>
                    <span class="text-gray-400 font-normal">—</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" onclick="toggleColorSwatch(this, 'Black')" data-color="Black"
                        class="color-swatch-btn text-center space-y-1 focus:outline-none opacity-60">
                        <div class="w-8 h-8 rounded bg-black border border-gray-300 mx-auto"></div>
                        <span class="block text-[10px] text-gray-600 font-medium">Black</span>
                    </button>
                    <button type="button" onclick="toggleColorSwatch(this, 'White')" data-color="White"
                        class="color-swatch-btn text-center space-y-1 focus:outline-none opacity-60">
                        <div class="w-8 h-8 rounded bg-white border border-gray-300 mx-auto"></div>
                        <span class="block text-[10px] text-gray-500">White</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-100 bg-white flex items-center gap-3">
            <button type="button" onclick="clearAllFilters()"
                class="w-1/2 border border-gray-300 py-3 text-xs font-medium rounded hover:bg-gray-50 active:scale-95 transition text-gray-700">
                Clear all
            </button>
            <button type="button" onclick="closeFilterDrawer()"
                class="w-1/2 bg-black text-white py-3 text-xs font-medium rounded hover:bg-black active:scale-95 transition">
                Show <span id="results-count">{{ count($katalog) }}</span> results
            </button>
        </div>
    </div>

    <script>
        let currentKategori = 'All';
        let recentSearches = ['Long Sleeve', 'Baby Tee', 'Baby Tee Bang Windah'];
        let activeColorFilter = null;
        let scrollPosition = 0;
        let filterOpen = false;
        let searchOpen = false;

        const RING = ['ring-2', 'ring-black', 'ring-offset-1'];
        const BTN_KATEGORI_ON = 'btn-kategori bg-black text-white px-4 py-2 rounded-md active:scale-95 transition duration-150';
        const BTN_KATEGORI_OFF = 'btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200';
        const SIZE_BTN_BASE = 'size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition';

        // ---------- Helpers ----------
        function esc(str) {
            return String(str).replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        // Aman dipakai di dalam atribut onclick="...('...')"
        function enc(str) {
            return encodeURIComponent(str).replace(/'/g, '%27');
        }

        // Samakan kategori: abaikan huruf besar-kecil, dan petakan alias lama
        const KATEGORI_ALIAS = { 'Short Sleeve': 'short sleeve', 'crop top': 'baby tee', 'croptop': 'baby tee' };

        function normKategori(k) {
            const v = String(k || '').trim().toLowerCase();
            return KATEGORI_ALIAS[v] || v;
        }

        function updateCategoryCounts() {
            const items = getItems();
            document.querySelectorAll('.cat-count').forEach(el => {
                const cat = normKategori(el.getAttribute('data-cat'));
                el.innerText = items.filter(i => normKategori(i.getAttribute('data-kategori')) === cat).length;
            });
        }

        function getItems() {
            return Array.from(document.querySelectorAll('.produk-item'));
        }

        function getSearchValue() {
            const d = document.getElementById('search-input');
            const m = document.getElementById('search-input-mobile');
            return document.activeElement === m ? m.value : (d ? d.value : '');
        }

        // ---------- Scroll lock ----------
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

        // Scroll terkunci selama salah satu panel (search / filter) terbuka
        function syncScrollLock() {
            if (filterOpen || searchOpen) lockScroll();
            else unlockScroll();
        }

        // ---------- Init ----------
        document.addEventListener('DOMContentLoaded', () => {
            renderRecentSearches();
            renderSuggestions('');
            updateCategoryCounts();
            applyFiltersAndSort();
            updateCartCountUI();

            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            if (desktopInput) desktopInput.addEventListener('focus', openSearchOverlay);
            if (mobileInput) mobileInput.addEventListener('focus', openSearchOverlay);
        });

        // ---------- Filter drawer ----------
        function openFilterDrawer() {
            if (searchOpen) closeSearchOverlay();

            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            filterOpen = true;
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                if (window.innerWidth < 640) {
                    drawer.classList.remove('translate-y-full');
                } else {
                    drawer.classList.remove('sm:translate-x-full');
                }
            }, 10);

            syncScrollLock();
        }

        function closeFilterDrawer() {
            if (!filterOpen) return;

            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            filterOpen = false;
            drawer.classList.add('translate-y-full', 'sm:translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                // Jangan sembunyikan kalau drawer sudah dibuka lagi saat animasi berjalan
                if (!filterOpen) overlay.classList.add('hidden');
                syncScrollLock();
            }, 300);
        }

        // ---------- Filter logic ----------
        function toggleSizeBtn(btn) {
            btn.classList.toggle('bg-black');
            btn.classList.toggle('text-white');
            btn.classList.toggle('border-black');
            btn.classList.toggle('border-gray-200');
            btn.classList.toggle('active-size-filter');
            applyFiltersAndSort();
        }

        // Ganti gambar + ring tombol warna pada satu kartu produk
        function setItemColor(item, color) {
            const imgEl = document.getElementById('produk-img-' + item.getAttribute('data-index'));
            const dots = item.querySelectorAll('.color-dot');
            const btnHitam = dots[0];
            const btnPutih = dots[1];
            const isWhite = color === 'White';

            if (imgEl) {
                imgEl.src = item.getAttribute(isWhite ? 'data-gambar-putih' : 'data-gambar-hitam');
            }
            if (btnHitam && btnPutih) {
                (isWhite ? btnPutih : btnHitam).classList.add(...RING);
                (isWhite ? btnHitam : btnPutih).classList.remove(...RING);
            }
        }

        function toggleColorSwatch(btn, colorName) {
            if (activeColorFilter === colorName) {
                activeColorFilter = null;
                btn.classList.add('opacity-60');
            } else {
                activeColorFilter = colorName;
                document.querySelectorAll('.color-swatch-btn').forEach(s => s.classList.add('opacity-60'));
                btn.classList.remove('opacity-60');
            }

            getItems().forEach(item => setItemColor(item, activeColorFilter));
            applyFiltersAndSort();
        }

        function clearAllFilters() {
            const firstPill = document.querySelector('.btn-kategori');
            if (firstPill) filterKategori('All', firstPill);

            document.querySelectorAll('.drawer-cat-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.size-btn').forEach(btn => btn.className = SIZE_BTN_BASE);

            activeColorFilter = null;
            document.querySelectorAll('.color-swatch-btn').forEach(s => s.classList.add('opacity-60'));
            getItems().forEach(item => setItemColor(item, null));

            applyFiltersAndSort();
        }

        function pilihWarna(index, gambarUrl, element) {
            const imgElement = document.getElementById('produk-img-' + index);
            if (imgElement) imgElement.src = gambarUrl;

            const parent = element.parentElement;
            if (parent) {
                parent.querySelectorAll('button').forEach(btn => btn.classList.remove(...RING));
                element.classList.add(...RING);
            }
        }

        function filterKategori(kategori, element) {
            currentKategori = kategori;
            document.querySelectorAll('.btn-kategori').forEach(btn => btn.className = BTN_KATEGORI_OFF);
            element.className = BTN_KATEGORI_ON;
            applyFiltersAndSort();
        }

        function applyFiltersAndSort() {
            const sortVal = document.getElementById('sort-select').value;
            const container = document.getElementById('katalog-grid');
            const items = getItems();

            const selectedDrawerCats = Array.from(document.querySelectorAll('.drawer-cat-checkbox:checked'))
                .map(cb => normKategori(cb.value));
            const selectedSizes = Array.from(document.querySelectorAll('.active-size-filter'))
                .map(btn => btn.getAttribute('data-size'));

            let visibleCount = 0;

            items.forEach(item => {
                const itemKategori = normKategori(item.getAttribute('data-kategori'));
                const matchesPill = currentKategori === 'All' || itemKategori === normKategori(currentKategori);
                const matchesDrawer = selectedDrawerCats.length === 0 || selectedDrawerCats.includes(itemKategori);

                if (matchesPill && matchesDrawer) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            let totalActiveFilters = 0;
            if (currentKategori !== 'All') totalActiveFilters++;
            totalActiveFilters += selectedDrawerCats.length;
            totalActiveFilters += selectedSizes.length;
            if (activeColorFilter) totalActiveFilters++;

            document.getElementById('filters-btn-count').innerText = `(${totalActiveFilters})`;
            document.getElementById('drawer-filter-count').innerText = `(${totalActiveFilters})`;
            document.getElementById('results-count').innerText = visibleCount;
            document.getElementById('showing-text').innerText = `Showing ${visibleCount} of ${items.length} items`;
            document.getElementById('header-item-count').innerText = `${visibleCount} Items`;

            items.sort((a, b) => {
                const hargaA = parseInt(a.getAttribute('data-harga'));
                const hargaB = parseInt(b.getAttribute('data-harga'));
                const indexA = parseInt(a.getAttribute('data-index'));
                const indexB = parseInt(b.getAttribute('data-index'));

                if (sortVal === 'Price: Low to High') return hargaA - hargaB;
                if (sortVal === 'Price: High to Low') return hargaB - hargaA;
                return indexA - indexB;
            });

            items.forEach(item => container.appendChild(item));
        }

        // ---------- Search overlay ----------
        function openSearchOverlay() {
            if (filterOpen) closeFilterDrawer();

            searchOpen = true;
            document.getElementById('search-overlay-box').classList.remove('hidden');
            document.getElementById('search-backdrop').classList.remove('hidden');
            syncScrollLock();

            renderSuggestions(getSearchValue());
        }

        function closeSearchOverlay() {
            searchOpen = false;
            const overlayBox = document.getElementById('search-overlay-box');
            const backdrop = document.getElementById('search-backdrop');

            if (overlayBox) overlayBox.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');

            syncScrollLock();
        }

        function handleSearchInput() {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            const clearBtnDesktop = document.getElementById('clear-search-btn');
            const clearBtnMobile = document.getElementById('clear-search-btn-mobile');

            const query = getSearchValue();

            // Sinkronkan dua input
            if (desktopInput && mobileInput) {
                if (document.activeElement === mobileInput) desktopInput.value = mobileInput.value;
                if (document.activeElement === desktopInput) mobileInput.value = desktopInput.value;
            }

            const display = query.length > 0 ? 'flex' : 'none';
            if (clearBtnDesktop) clearBtnDesktop.style.display = display;
            if (clearBtnMobile) clearBtnMobile.style.display = display;

            renderSuggestions(query);
        }

        function clearSearchInput() {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            const clearBtnDesktop = document.getElementById('clear-search-btn');
            const clearBtnMobile = document.getElementById('clear-search-btn-mobile');

            if (desktopInput) desktopInput.value = '';
            if (mobileInput) mobileInput.value = '';
            if (clearBtnDesktop) clearBtnDesktop.style.display = 'none';
            if (clearBtnMobile) clearBtnMobile.style.display = 'none';

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
                    <span onclick="applySearchTag(decodeURIComponent('${enc(item)}'))" class="cursor-pointer flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ${esc(item)}
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

        function suggestionItem(text) {
            return `
                <li onclick="applySearchTag(decodeURIComponent('${enc(text)}'))" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                    <span>🔍 ${esc(text)}</span><span class="text-gray-400">↗</span>
                </li>`;
        }

        function renderSuggestions(query) {
            const sugContainer = document.getElementById('suggestions-list');
            const prevContainer = document.getElementById('search-products-preview');
            const resultCountEl = document.getElementById('search-result-count');
            if (!sugContainer || !prevContainer) return;

            const q = (query || '').toLowerCase().trim();

            const matchingItems = getItems().filter(item => {
                const nama = item.getAttribute('data-nama').toLowerCase();
                const kategori = item.getAttribute('data-kategori').toLowerCase();
                return nama.includes(q) || kategori.includes(q);
            });

            if (q === '') {
                sugContainer.innerHTML = ['Longsleeve Bang Windah', 'Baby Tee Snoopy', 'Baby Tee']
                    .map(suggestionItem).join('');
            } else {
                sugContainer.innerHTML = matchingItems.slice(0, 4)
                    .map(item => suggestionItem(item.getAttribute('data-nama'))).join('') ||
                    '<li class="text-gray-400">No suggestions found</li>';
            }

            if (resultCountEl) resultCountEl.innerText = matchingItems.length;

            prevContainer.innerHTML = matchingItems.slice(0, 3).map(item => `
                <a href="/product/${encodeURIComponent(item.getAttribute('data-index'))}" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer">
                    <img src="${esc(activeColorFilter === 'White' ? item.getAttribute('data-gambar-putih') : item.getAttribute('data-gambar-hitam'))}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${esc(item.getAttribute('data-nama'))}</h5>
                        <p class="text-[10px] text-gray-400">${esc(item.getAttribute('data-kategori'))}</p>
                        <p class="text-[11px] font-semibold text-gray-800">Rp${parseInt(item.getAttribute('data-harga')).toLocaleString('id-ID')}</p>
                    </div>
                </a>
            `).join('') || '<p class="text-gray-400 text-xs">No matching products</p>';
        }

        function applySearchTag(tagText) {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');

            if (desktopInput) desktopInput.value = tagText;
            if (mobileInput) mobileInput.value = tagText;

            if (!recentSearches.includes(tagText)) {
                recentSearches.unshift(tagText);
                if (recentSearches.length > 5) recentSearches.pop();
                renderRecentSearches();
            }

            window.location.href = "{{ route('search') }}?q=" + encodeURIComponent(tagText);
        }

        function viewAllResults() {
            const q = getSearchValue().trim();
            window.location.href = "{{ route('search') }}?q=" + encodeURIComponent(q);
        }

        // ---------- Cart badge ----------
        async function updateCartCountUI() {
            const badgeDesktop = document.getElementById('cart-count-desktop');
            const badgeMobile = document.getElementById('cart-count-mobile');

            try {
                const response = await fetch("{{ route('cart.count') }}", {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('Gagal mengambil jumlah keranjang');

                const data = await response.json();
                const count = Number(data.count) || 0;

                if (badgeDesktop) badgeDesktop.innerText = count;
                if (badgeMobile) badgeMobile.innerText = count;
            } catch (error) {
                console.error(error);
            }
        }
    </script>

</body>

</html>
