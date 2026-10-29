<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYART.MERCH - Apparel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative overflow-x-hidden">

    <!-- 1. NAVBAR -->
    <nav class="border-b border-gray-200 px-4 md:px-12 py-4 relative z-40 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

            <!-- LOGO KIRI & MOBILE MENU -->
            <div class="flex items-center justify-between w-full md:w-auto">
                <a href="{{ route('home') }}"
                    class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition active:scale-95 inline-block">ASYART.MERCH</a>

                <!-- Ikon & Menu Auth Mobile (Tampilan HP) -->
                <div class="flex items-center gap-2 md:hidden">
                    <!-- Cart Icon Mobile -->
                    <a href="{{ route('cart.page') }}"
                        class="relative hover:opacity-75 active:scale-90 transition duration-150 mr-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-count-mobile"
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                    </a>

                    <!-- Auth Links Mobile -->
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

            <!-- SEARCH BAR TENGAH (LIVE SEARCH INTERAKTIF) -->
            <div class="hidden md:block w-[450px] relative">
                <form action="{{ route('search.page') }}" method="GET" class="relative flex items-center">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" name="q" placeholder="Search products"
                        autocomplete="off" onfocus="openSearchOverlay()" oninput="handleSearchInput()"
                        class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">
                    <button type="button" id="clear-search-btn" onclick="clearSearchInput()"
                        class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-xs font-bold">×</button>
                </form>

                <!-- OVERLAY DROPDOWN PENCARIAN INTERAKTIF -->
                <div id="search-overlay-box"
                    class="absolute top-full left-0 right-0 -left-40 -right-40 mt-2 bg-white border border-gray-200 shadow-2xl rounded-lg p-5 z-50 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-gray-800">
                        <!-- Suggestions -->
                        <div class="space-y-3">
                            <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">SUGGESTIONS
                            </h4>
                            <ul id="suggestions-list" class="space-y-2"></ul>
                        </div>

                        <!-- Recent & Popular -->
                        <div class="space-y-5">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">RECENT
                                        SEARCHES</h4>
                                    <button onclick="clearAllRecentSearches()"
                                        class="text-[10px] text-gray-400 hover:text-black underline">Clear</button>
                                </div>
                                <ul id="recent-searches-list" class="space-y-1.5"></ul>
                            </div>

                            <div>
                                <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    POPULAR</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" onclick="applySearchTag('Long Sleeve')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Long
                                        Sleeve</button>
                                    <button type="button" onclick="applySearchTag('Short Sleeve')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Short
                                        T-shirt</button>
                                    <button type="button" onclick="applySearchTag('Baby Tee')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Baby
                                        Tee</button>
                                    <button type="button" onclick="applySearchTag('Crop Top')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Crop
                                        Top</button>
                                </div>
                            </div>
                        </div>

                        <!-- Products Preview -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">PRODUCTS
                                </h4>
                                <a href="javascript:void(0)" onclick="closeSearchOverlay()"
                                    class="text-[10px] text-black font-semibold underline">View all <span
                                        id="search-result-count">0</span> results</a>
                            </div>
                            <div id="search-products-preview" class="space-y-2.5 max-h-56 overflow-y-auto"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth Navbar Desktop & Cart Icon -->
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

    <!-- SEARCH BAR KHUSUS MOBILE DI BAWAH NAVBAR -->
    <div class="px-4 py-2 md:hidden bg-white border-b border-gray-100">
        <form action="{{ route('search.page') }}" method="GET" class="w-full relative">
            <div class="relative flex items-center">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="q" placeholder="Search products" autocomplete="off"
                    class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:border-black transition duration-200">
            </div>
        </form>
    </div>

    <!-- SEARCH BACKDROP -->
    <div id="search-backdrop" onclick="closeSearchOverlay()" class="fixed inset-0 bg-black/20 z-30 hidden"></div>

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
                <span id="header-item-count" class="text-xs text-gray-400">12 Items</span>
            </div>

            <!-- CHIPS FILTER AKTIF -->
            <div id="active-filters-bar" class="hidden items-center flex-wrap gap-2 mb-6">
                <div id="active-tags-list" class="flex flex-wrap items-center gap-2"></div>
                <button onclick="clearAllFilters()"
                    class="text-xs font-medium text-gray-900 underline hover:text-red-600 transition ml-2">
                    Clear all
                </button>
            </div>

            <!-- FILTER & SORT BAR -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-2 text-xs">
                    <button onclick="filterKategori('All', this)"
                        class="btn-kategori bg-black text-white px-4 py-2 rounded-md active:scale-95 transition duration-150">
                        All
                    </button>
                    <button onclick="filterKategori('Long Sleeve', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Long Sleeve
                    </button>
                    <button onclick="filterKategori('Crop Top', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Crop Top
                    </button>
                    <button onclick="filterKategori('Regular Tee', this)"
                        class="btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200">
                        Regular Tee
                    </button>
                </div>

                <div
                    class="flex items-center gap-6 text-xs text-gray-700 w-full sm:w-auto justify-between sm:justify-end">
                    <button onclick="openFilterDrawer()"
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

        <!-- 2. GRID KATALOG PRODUK INTERAKTIF -->
        @php
            $katalog = [
                [
                    'nama' => 'Crop Top Bang Windah',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'windahcrop-htm.jpg',
                    'gambar_putih' => 'windahcrop-pth.jpg',
                ],
                [
                    'nama' => 'Regular Tee Bocil Bang Windah',
                    'kategori' => 'Short Sleeve',
                    'harga' => 'Rp85.000',
                    'gambar_hitam' => 'windahreg-htm.jpg',
                    'gambar_putih' => 'windahreg-pth.jpg',
                ],
                [
                    'nama' => 'Longsleeve Bang Windah',
                    'kategori' => 'Long Sleeve',
                    'harga' => 'Rp110.000',
                    'gambar_hitam' => 'windahpnjg-htm.jpg',
                    'gambar_putih' => 'windahpnjg-pth.jpg',
                ],
                [
                    'nama' => 'Crop Top Snoopy',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'snpcrop-htm.jpg',
                    'gambar_putih' => 'snpcrop-pth.jpg',
                ],
                [
                    'nama' => 'Crop Top Radio Head',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp85.000',
                    'gambar_hitam' => 'radiocrop-htm.jpg',
                    'gambar_putih' => 'radiocrop-pth.jpg',
                ],
                [
                    'nama' => 'Crop Top Cake',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp110.000',
                    'gambar_hitam' => 'cakecrop-htm.jpg',
                    'gambar_putih' => 'cakecrop-pth.jpg',
                ],
                [
                    'nama' => 'Crop Top Blue Star Fish',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp95.000',
                    'gambar_hitam' => 'ikanbirucrop-htm.jpg',
                    'gambar_putih' => 'ikanbirucrop-pth.jpg',
                ],
                [
                    'nama' => 'Regular Tee Circle Fish',
                    'kategori' => 'Short Sleeve',
                    'harga' => 'Rp85.000',
                    'gambar_hitam' => 'ikanbulatreg-htm.jpg',
                    'gambar_putih' => 'ikanbulatreg-pth.jpg',
                ],
                [
                    'nama' => 'Longsleeve Puffer Fish',
                    'kategori' => 'Long Sleeve',
                    'harga' => 'Rp115.000',
                    'gambar_hitam' => 'ikanbuntalpnjg-htm.jpg',
                    'gambar_putih' => 'ikanbuntalpnjg-pth.jpg',
                ],
                [
                    'nama' => 'Crop Top Snoopy & Bang Windah',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp75.000',
                    'gambar_hitam' => 'npcrop-htm.jpg',
                    'gambar_putih' => 'npcrop-pth.jpg',
                ],
                [
                    'nama' => 'Regular Tee Bocil Sigma',
                    'kategori' => 'Regular Tee',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'bclreg-htm.jpg',
                    'gambar_putih' => 'bclreg-pth.jpg',
                ],
                [
                    'nama' => 'Longsleeve Orange Fish',
                    'kategori' => 'Long Sleeve',
                    'harga' => 'Rp80.000',
                    'gambar_hitam' => 'ikanorenpnjg-htm.jpg',
                    'gambar_putih' => 'ikanorenpnjg-pth.jpg',
                ],
            ];
        @endphp

        <!-- Grid Cards Container -->
        <div id="katalog-grid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
            @foreach ($katalog as $index => $item)
                @php
                    $hargaAngka = (int) preg_replace('/[^0-9]/', '', $item['harga']);
                @endphp
                <div class="group produk-item" data-kategori="{{ $item['kategori'] }}"
                    data-nama="{{ $item['nama'] }}" data-harga="{{ $hargaAngka }}"
                    data-index="{{ $index }}"
                    data-gambar-hitam="{{ asset('images/' . $item['gambar_hitam']) }}"
                    data-gambar-putih="{{ asset('images/' . $item['gambar_putih']) }}">

                    <!-- Box Gambar -->
                    <a href="{{ route('product.detail', $index) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block cursor-pointer">
                        <img id="produk-img-{{ $index }}"
                            src="{{ asset('images/' . $item['gambar_hitam']) }}" alt="{{ $item['nama'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>

                    <!-- Tombol Buletan Warna -->
                    <div class="flex items-center gap-1.5 mb-2">
                        <button type="button"
                            onclick="pilihWarna('{{ $index }}', '{{ asset('images/' . $item['gambar_hitam']) }}', this)"
                            class="w-4 h-4 rounded-full bg-black ring-2 ring-black ring-offset-1 hover:scale-110 active:scale-95 transition cursor-pointer focus:outline-none"
                            title="Hitam">
                        </button>

                        <button type="button"
                            onclick="pilihWarna('{{ $index }}', '{{ asset('images/' . $item['gambar_putih']) }}', this)"
                            class="w-4 h-4 rounded-full bg-white border border-gray-300 hover:scale-110 active:scale-95 transition cursor-pointer focus:outline-none"
                            title="Putih">
                        </button>
                    </div>

                    <!-- Teks Info Produk -->
                    <a href="{{ route('product.detail', $index) }}" class="block">
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
            <p id="showing-text" class="text-[11px] text-gray-400">Showing 12 of 12 items</p>
            <button
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
            <button onclick="closeFilterDrawer()" class="p-1 text-gray-400 hover:text-black transition">
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
                        <span class="text-gray-400 text-[11px]">3</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Short Sleeve" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Short Sleeve</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">2</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Regular Tee" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Regular Tee</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">1</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Crop Top" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Crop Top</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">6</span>
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
                    <button type="button" onclick="toggleSizeBtn(this)" data-size="XS"
                        class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">XS</button>
                    <button type="button" onclick="toggleSizeBtn(this)" data-size="S"
                        class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">S</button>
                    <button type="button" onclick="toggleSizeBtn(this)" data-size="M"
                        class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">M</button>
                    <button type="button" onclick="toggleSizeBtn(this)" data-size="L"
                        class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">L</button>
                    <button type="button" onclick="toggleSizeBtn(this)" data-size="XL"
                        class="size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition">XL</button>
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
                class="w-1/2 bg-black text-white py-3 text-xs font-medium rounded hover:bg-gray-800 active:scale-95 transition">
                Show <span id="results-count">12</span> results
            </button>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT MASTER UTAMA -->
    <script>
        let currentKategori = 'All';
        let recentSearches = ['Long Sleeve', 'Baby Tee', 'Crop Top Bang Windah'];
        let activeColorFilter = null;

        // document.addEventListener('DOMContentLoaded', () => {
        //     renderRecentSearches();
        //     renderSuggestions('');
        //     applyFiltersAndSort();
        //     updateCartCountUI(); // <-- Ini wajib ada biar fungsi ngitung cart-nya langsung jalan pas halaman dibuka
        // });
        document.addEventListener('DOMContentLoaded', () => {
    fetchProductsFromBackend();
    renderRecentSearches();
    renderSuggestions('');
    updateCartCountUI();
});

// Fungsi buat ngambil data produk dari API Backend
function fetchProductsFromBackend() {
    fetch('/api/products') // Endpoint dari anak BE nanti
        .then(response => response.json())
        .then(katalog => {
            renderProductGrid(katalog);
        })
        .catch(error => {
            console.error('Gagal ngambil data produk dari BE, cuk:', error);
        });
}


function renderProductGrid(katalog) {
    const container = document.getElementById('katalog-grid');
    if (!container) return;

    container.innerHTML = katalog.map((item, index) => `
        <div class="group produk-item" data-kategori="${item.kategori}"
            data-nama="${item.nama}" data-harga="${item.harga_angka}"
            data-index="${item.id}"
            data-gambar-hitam="${item.gambar_hitam}"
            data-gambar-putih="${item.gambar_putih}">

            <a href="/product/${item.id}" class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block cursor-pointer">
                <img id="produk-img-${item.id}" src="${item.gambar_hitam}" alt="${item.nama}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </a>

            <div class="flex items-center gap-1.5 mb-2">
                <button type="button" onclick="pilihWarna('${item.id}', '${item.gambar_hitam}', this)" class="w-4 h-4 rounded-full bg-black ring-2 ring-black ring-offset-1 hover:scale-110 transition cursor-pointer"></button>
                <button type="button" onclick="pilihWarna('${item.id}', '${item.gambar_putih}', this)" class="w-4 h-4 rounded-full bg-white border border-gray-300 hover:scale-110 transition cursor-pointer"></button>
            </div>

            <a href="/product/${item.id}" class="block">
                <h3 class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline">${item.nama}</h3>
            </a>
            <p class="text-[11px] text-gray-400">${item.kategori}</p>
            <p class="text-xs font-semibold mt-1">${item.harga}</p>
        </div>
    `).join('');

    applyFiltersAndSort();
}

        // 1. FILTER DRAWER FUNCTIONS (BOTTOM SHEET MOBILE / SIDEBAR DESKTOP)
        function openFilterDrawer() {
            closeSearchOverlay();
            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                if (window.innerWidth < 640) {
                    drawer.classList.remove('translate-y-full');
                } else {
                    drawer.classList.remove('sm:translate-x-full');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeFilterDrawer() {
            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            drawer.classList.add('translate-y-full', 'sm:translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function toggleSizeBtn(btn) {
            btn.classList.toggle('bg-black');
            btn.classList.toggle('text-white');
            btn.classList.toggle('border-black');
            btn.classList.toggle('border-gray-200');
            btn.classList.toggle('active-size-filter');
            applyFiltersAndSort();
        }

        // 2. FUNGSI FILTER WARNA (HITAM / PUTIH)
        function toggleColorSwatch(btn, colorName) {
            if (activeColorFilter === colorName) {
                activeColorFilter = null;
                btn.classList.add('opacity-60');
            } else {
                activeColorFilter = colorName;
                document.querySelectorAll('.color-swatch-btn').forEach(s => s.classList.add('opacity-60'));
                btn.classList.remove('opacity-60');
            }

            const items = document.querySelectorAll('.produk-item');
            items.forEach(item => {
                const index = item.getAttribute('data-index');
                const imgHitam = item.getAttribute('data-gambar-hitam');
                const imgPutih = item.getAttribute('data-gambar-putih');
                const imgElement = document.getElementById('produk-img-' + index);

                const parentBox = item.querySelector('.flex.items-center.gap-1\\.5');
                const btnHitam = parentBox ? parentBox.querySelectorAll('button')[0] : null;
                const btnPutih = parentBox ? parentBox.querySelectorAll('button')[1] : null;

                if (imgElement) {
                    if (activeColorFilter === 'White') {
                        imgElement.src = imgPutih;
                        if (btnPutih && btnHitam) {
                            btnPutih.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                            btnHitam.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        }
                    } else {
                        imgElement.src = imgHitam;
                        if (btnPutih && btnHitam) {
                            btnHitam.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                            btnPutih.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        }
                    }
                }
            });

            applyFiltersAndSort();
        }

        function clearAllFilters() {
            const firstPill = document.querySelector('.btn-kategori');
            if (firstPill) filterKategori('All', firstPill);

            document.querySelectorAll('.drawer-cat-checkbox').forEach(cb => cb.checked = false);

            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.className =
                    'size-btn border border-gray-200 min-w-[38px] py-2 text-center text-xs rounded hover:border-black transition';
                btn.classList.remove('active-size-filter');
            });

            activeColorFilter = null;
            document.querySelectorAll('.color-swatch-btn').forEach(s => s.classList.add('opacity-60'));

            const items = document.querySelectorAll('.produk-item');
            items.forEach(item => {
                const index = item.getAttribute('data-index');
                const imgHitam = item.getAttribute('data-gambar-hitam');
                const imgElement = document.getElementById('produk-img-' + index);

                const parentBox = item.querySelector('.flex.items-center.gap-1\\.5');
                const btnHitam = parentBox ? parentBox.querySelectorAll('button')[0] : null;
                const btnPutih = parentBox ? parentBox.querySelectorAll('button')[1] : null;

                if (imgElement) imgElement.src = imgHitam;
                if (btnHitam && btnPutih) {
                    btnHitam.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                    btnPutih.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                }
            });

            applyFiltersAndSort();
        }

        function pilihWarna(index, gambarUrl, element) {
            const imgElement = document.getElementById('produk-img-' + index);
            if (imgElement) {
                imgElement.src = gambarUrl;
            }

            const parent = element.parentElement;
            if (parent) {
                const buttons = parent.querySelectorAll('button');
                buttons.forEach(btn => btn.classList.remove('ring-2', 'ring-black', 'ring-offset-1'));
                element.classList.add('ring-2', 'ring-black', 'ring-offset-1');
            }
        }

        function filterKategori(kategori, element) {
            currentKategori = kategori;
            const buttons = document.querySelectorAll('.btn-kategori');
            buttons.forEach(btn => btn.className =
                'btn-kategori bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-black hover:text-white active:scale-95 transition duration-200'
            );
            element.className =
                'btn-kategori bg-black text-white px-4 py-2 rounded-md active:scale-95 transition duration-150';
            applyFiltersAndSort();
        }

        // 3. SEARCH OVERLAY INTERAKTIF
        function openSearchOverlay() {
            closeFilterDrawer();
            document.getElementById('search-overlay-box').classList.remove('hidden');
            document.getElementById('search-backdrop').classList.remove('hidden');
            renderSuggestions(document.getElementById('search-input').value);
        }

        function closeSearchOverlay() {
            const overlay = document.getElementById('search-overlay-box');
            const backdrop = document.getElementById('search-backdrop');
            if (overlay) overlay.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');
        }

        function handleSearchInput() {
            const query = document.getElementById('search-input').value;
            const clearBtn = document.getElementById('clear-search-btn');

            if (query.trim() !== '') {
                clearBtn.classList.remove('flex');
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            renderSuggestions(query);
        }

        function clearSearchInput() {
            const input = document.getElementById('search-input');
            input.value = '';
            document.getElementById('clear-search-btn').classList.add('hidden');
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
                    <button type="button" onclick="removeRecentSearch(${idx})" class="text-gray-300 hover:text-black text-xs font-bold">×</button>
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

            const q = query.toLowerCase().trim();
            const items = Array.from(document.querySelectorAll('.produk-item'));

            const matchingItems = items.filter(item => {
                const nama = item.getAttribute('data-nama').toLowerCase();
                const kategori = item.getAttribute('data-kategori').toLowerCase();
                return nama.includes(q) || kategori.includes(q);
            });

            if (q === '') {
                sugContainer.innerHTML = `
                    <li onclick="applySearchTag('Longsleeve Bang Windah')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Longsleeve Bang Windah</span><span class="text-gray-400">↗</span>
                    </li>
                    <li onclick="applySearchTag('Crop Top Snoopy')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Crop Top Snoopy</span><span class="text-gray-400">↗</span>
                    </li>
                    <li onclick="applySearchTag('Regular Tee')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Regular Tee</span><span class="text-gray-400">↗</span>
                    </li>
                `;
            } else {
                sugContainer.innerHTML = matchingItems.slice(0, 4).map(item => `
                    <li onclick="applySearchTag('${item.getAttribute('data-nama')}')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 ${item.getAttribute('data-nama')}</span><span class="text-gray-400">↗</span>
                    </li>
                `).join('') || '<li class="text-gray-400">No suggestions found</li>';
            }

            if (resultCountEl) resultCountEl.innerText = matchingItems.length;

            prevContainer.innerHTML = matchingItems.slice(0, 3).map((item) => `
                <a href="{{ route('product.detail', '') }}/${item.getAttribute('data-index')}" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer block">
                    <img src="${activeColorFilter === 'White' ? item.getAttribute('data-gambar-putih') : item.getAttribute('data-gambar-hitam')}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${item.getAttribute('data-nama')}</h5>
                        <p class="text-[10px] text-gray-400">${item.getAttribute('data-kategori')}</p>
                        <p class="text-[11px] font-semibold text-gray-800">Rp${parseInt(item.getAttribute('data-harga')).toLocaleString('id-ID')}</p>
                    </div>
                </a>
            `).join('') || '<p class="text-gray-400 text-xs">No matching products</p>';
        }

        function applySearchTag(tagText) {
            const input = document.getElementById('search-input');
            input.value = tagText;
            document.getElementById('clear-search-btn').classList.remove('hidden');

            if (!recentSearches.includes(tagText)) {
                recentSearches.unshift(tagText);
                if (recentSearches.length > 5) recentSearches.pop();
                renderRecentSearches();
            }

            window.location.href = "{{ route('search.page') }}?q=" + encodeURIComponent(tagText);
        }

        // 4. MASTER FILTER & SORT LOGIC
        function applyFiltersAndSort() {
            const sortVal = document.getElementById('sort-select').value;
            const container = document.getElementById('katalog-grid');
            const items = Array.from(document.querySelectorAll('.produk-item'));

            const selectedDrawerCats = Array.from(document.querySelectorAll('.drawer-cat-checkbox:checked')).map(cb => cb
                .value);
            const selectedSizes = Array.from(document.querySelectorAll('.active-size-filter')).map(btn => btn.getAttribute(
                'data-size'));

            let visibleCount = 0;

            items.forEach(item => {
                const index = item.getAttribute('data-index');
                const imgHitam = item.getAttribute('data-gambar-hitam');
                const imgPutih = item.getAttribute('data-gambar-putih');
                const imgElement = document.getElementById('produk-img-' + index);

                const parentBox = item.querySelector('.flex.items-center.gap-1\\.5');
                const btnHitam = parentBox ? parentBox.querySelectorAll('button')[0] : null;
                const btnPutih = parentBox ? parentBox.querySelectorAll('button')[1] : null;

                if (imgElement) {
                    if (activeColorFilter === 'White') {
                        imgElement.src = imgPutih;
                        if (btnPutih && btnHitam) {
                            btnPutih.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                            btnHitam.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        }
                    } else {
                        imgElement.src = imgHitam;
                        if (btnPutih && btnHitam) {
                            btnHitam.classList.add('ring-2', 'ring-black', 'ring-offset-1');
                            btnPutih.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                        }
                    }
                }

                const itemKategori = item.getAttribute('data-kategori');

                let matchesPillCategory = false;
                if (currentKategori === 'All') {
                    matchesPillCategory = true;
                } else if (currentKategori === 'Regular Tee' && (itemKategori === 'Regular Tee' || itemKategori ===
                        'Short Sleeve')) {
                    matchesPillCategory = true;
                } else if (itemKategori === currentKategori) {
                    matchesPillCategory = true;
                }

                let matchesDrawerCategory = true;
                if (selectedDrawerCats.length > 0) {
                    if (selectedDrawerCats.includes('Regular Tee') && (itemKategori === 'Regular Tee' ||
                            itemKategori === 'Short Sleeve')) {
                        matchesDrawerCategory = true;
                    } else {
                        matchesDrawerCategory = selectedDrawerCats.includes(itemKategori);
                    }
                }

                if (matchesPillCategory && matchesDrawerCategory) {
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
            document.getElementById('showing-text').innerText = `Showing ${visibleCount} of 12 items`;

            items.sort((a, b) => {
                const hargaA = parseInt(a.getAttribute('data-harga'));
                const hargaB = parseInt(b.getAttribute('data-harga'));
                const indexA = parseInt(a.getAttribute('data-index'));
                const indexB = parseInt(b.getAttribute('data-index'));

                if (sortVal === 'Price: Low to High') {
                    return hargaA - hargaB;
                } else if (sortVal === 'Price: High to Low') {
                    return hargaB - hargaA;
                } else {
                    return indexA - indexB;
                }
            });

            items.forEach(item => container.appendChild(item));
        }

        // FUNGSI SINKRONISASI BADGE KERANJANG DI NAVBAR HOME
        function updateCartCountUI() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Ganti jadi cart.length biar ngitung jumlah jenis produk unik (misal: 3 items)
            let totalUniqueItems = cart.length;

            const badgeDesktop = document.getElementById('cart-count-desktop');
            const badgeMobile = document.getElementById('cart-count-mobile');

            if (badgeDesktop) badgeDesktop.innerText = totalUniqueItems;
            if (badgeMobile) badgeMobile.innerText = totalUniqueItems;
        }
    </script>

</body>

</html>
