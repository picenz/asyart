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
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="flex items-center justify-between w-full md:w-auto">
                <a href="#"
                    class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition active:scale-95 inline-block">ASYART.MERCH</a>

                <!-- Ikon & Menu Auth Mobile (Tampilan HP) -->
                <div class="flex items-center gap-2 md:hidden">
                    <!-- Cart Icon Mobile -->
                    <button onclick="openCartDrawer()"
                        class="relative hover:opacity-75 active:scale-90 transition duration-150 mr-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-count-mobile"
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </button>

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

            <!-- SEARCH BAR TENGAH (LIVE SEARCH OVERLAY) -->
            <div class="w-full md:w-[450px] relative">
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" onfocus="openSearchOverlay()" oninput="handleSearchInput()"
                        placeholder="Search products" autocomplete="off"
                        class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">

                    <button id="clear-search-btn" onclick="clearSearchInput()"
                        class="absolute right-2.5 text-gray-400 hover:text-black hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- OVERLAY DROPDOWN PENCARIAN -->
                <div id="search-overlay-box"
                    class="absolute top-full left-0 right-0 md:-left-40 md:-right-40 mt-2 bg-white border border-gray-200 shadow-2xl rounded-lg p-5 z-50 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-gray-800">
                        <div class="space-y-3">
                            <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">SUGGESTIONS
                            </h4>
                            <ul id="suggestions-list" class="space-y-2"></ul>
                        </div>

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
                                    <button onclick="applySearchTag('Long Sleeve')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Long
                                        Sleeve</button>
                                    <button onclick="applySearchTag('Short Sleeve')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Short
                                        T-shirt</button>
                                    <button onclick="applySearchTag('Baby Tee')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Baby
                                        Tee</button>
                                    <button onclick="applySearchTag('Crop Top')"
                                        class="border border-gray-200 px-2.5 py-1 rounded text-gray-700 hover:border-black transition">Crop
                                        Top</button>
                                </div>
                            </div>
                        </div>

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
                <button onclick="openCartDrawer()"
                    class="relative hover:opacity-75 active:scale-90 transition duration-150 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span id="cart-count-desktop"
                        class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                </button>

                <!-- Auth Links Desktop -->
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
                <span id="header-item-count" class="text-xs text-gray-400">128 Items</span>
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
                    data-nama="{{ $item['nama'] }}" data-harga="{{ $hargaAngka }}" data-index="{{ $index }}"
                    data-gambar-hitam="{{ asset('images/' . $item['gambar_hitam']) }}"
                    data-gambar-putih="{{ asset('images/' . $item['gambar_putih']) }}">

                    <!-- Box Gambar (KLIK BUKA MODAL DETAIL) -->
                    <div onclick="openProductModal({{ $index }})"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative cursor-pointer">
                        <img id="produk-img-{{ $index }}"
                            src="{{ asset('images/' . $item['gambar_hitam']) }}" alt="{{ $item['nama'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>

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

                    <!-- Teks Info Produk (KLIK BUKA MODAL DETAIL) -->
                    <h3 onclick="openProductModal({{ $index }})"
                        class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline cursor-pointer">
                        {{ $item['nama'] }}
                    </h3>
                    <p class="text-[11px] text-gray-400">{{ $item['kategori'] }}</p>
                    <p class="text-xs font-semibold mt-1">{{ $item['harga'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- TAMPILAN NO RESULTS -->
        <div id="no-results-container" class="hidden py-8 md:py-16 text-center max-w-2xl mx-auto space-y-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <h2 class="text-2xl md:text-4xl font-serif">
                No results for "<span id="no-results-query-text"></span>"
            </h2>

            <p class="text-xs text-gray-500 max-w-md mx-auto leading-relaxed">
                Check the spelling, try a more general term, or remove some filters. You can also browse our most-loved
                categories below.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <button onclick="clearAllFilters()"
                    class="w-full sm:w-auto border border-black text-xs font-medium px-8 py-3 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200">
                    Clear search
                </button>
                <button onclick="clearAllFilters()"
                    class="w-full sm:w-auto bg-black text-white text-xs font-medium px-8 py-3 rounded hover:bg-gray-800 active:scale-95 transition duration-200">
                    Browse all apparel
                </button>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-2 pt-2 text-xs">
                <button onclick="applySearchTag('Long Sleeve')"
                    class="border border-gray-200 px-3.5 py-1.5 rounded text-gray-700 hover:border-black transition">Long
                    Sleeve</button>
                <button onclick="applySearchTag('Short Sleeve')"
                    class="border border-gray-200 px-3.5 py-1.5 rounded text-gray-700 hover:border-black transition">Short
                    Sleeve</button>
                <button onclick="applySearchTag('Baby Tee')"
                    class="border border-gray-200 px-3.5 py-1.5 rounded text-gray-700 hover:border-black transition">Baby
                    Tee</button>
                <button onclick="applySearchTag('Crop Top')"
                    class="border border-gray-200 px-3.5 py-1.5 rounded text-gray-700 hover:border-black transition">Crop
                    Top</button>
            </div>

            <div class="pt-16 border-t border-gray-100 text-left mt-12">
                <h3 class="text-xl md:text-2xl font-serif mb-6">You might like</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach (array_slice($katalog, 0, 4) as $recIndex => $recItem)
                        <div onclick="openProductModal({{ $recIndex }})" class="group cursor-pointer">
                            <div class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative">
                                <img src="{{ asset('images/' . $recItem['gambar_hitam']) }}"
                                    alt="{{ $recItem['nama'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <h3 class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline">
                                {{ $recItem['nama'] }}</h3>
                            <p class="text-[11px] text-gray-400">{{ $recItem['kategori'] }}</p>
                            <p class="text-xs font-semibold mt-1">{{ $recItem['harga'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- LOAD MORE BUTTON -->
        <div id="load-more-section" class="text-center space-y-3 pt-4 border-t border-gray-100">
            <p id="showing-text" class="text-[11px] text-gray-400">Showing {{ count($katalog) }} of 128 items</p>
            <button
                class="border border-gray-400 text-xs px-8 py-2.5 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200">
                Load more
            </button>
        </div>

    </main>

    <!-- 3. MODAL PRODUCT DETAIL (POP-UP DETIL PRODUK - MOCKUP FIGMA) -->
    <div id="product-modal-backdrop"
        class="fixed inset-0 bg-black/50 z-50 hidden transition-opacity duration-300 opacity-0 flex items-center justify-center p-4">
        <div id="product-modal-box"
            class="bg-white w-full max-w-4xl rounded-lg shadow-2xl overflow-hidden relative max-h-[90vh] flex flex-col md:flex-row transform scale-95 transition-transform duration-300">

            <!-- Close Button -->
            <button onclick="closeProductModal()"
                class="absolute top-4 right-4 z-10 text-gray-400 hover:text-black bg-white/80 p-1 rounded-full backdrop-blur transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- KIRI: Gambar Utama & Gallery Thumbnail -->
            <div class="w-full md:w-1/2 bg-gray-50 p-6 flex flex-col justify-between items-center">
                <div class="w-full aspect-square bg-gray-100 rounded-md overflow-hidden mb-4">
                    <img id="modal-main-img" src=""
                        class="w-full h-full object-cover transition duration-300">
                </div>
                <!-- Thumbnails Gallery -->
                <div class="flex items-center gap-3 w-full justify-center">
                    <button onclick="setModalThumbnail(0)"
                        class="w-14 h-14 rounded border-2 border-black overflow-hidden focus:outline-none modal-thumb-btn">
                        <img id="modal-thumb-0" src="" class="w-full h-full object-cover">
                    </button>
                    <button onclick="setModalThumbnail(1)"
                        class="w-14 h-14 rounded border border-gray-200 opacity-60 overflow-hidden focus:outline-none modal-thumb-btn">
                        <img id="modal-thumb-1" src="" class="w-full h-full object-cover">
                    </button>
                </div>
            </div>

            <!-- KANAN: Detail Info & Option Selection -->
            <div class="w-full md:w-1/2 p-6 md:p-8 overflow-y-auto space-y-5 text-xs text-gray-800">
                <div>
                    <h2 id="modal-product-title" class="text-2xl font-serif font-medium text-gray-900 mb-1">Product
                        Title</h2>
                    <p id="modal-product-cat" class="text-gray-400 uppercase tracking-wider text-[10px] mb-2">Category
                    </p>
                    <p id="modal-product-price" class="text-lg font-semibold text-gray-900">Rp0</p>
                </div>

                <!-- Star Rating Review -->
                <div class="flex items-center gap-2 pb-4 border-b border-gray-100">
                    <div class="flex text-black">
                        ★★★★★
                    </div>
                    <span class="font-semibold text-gray-900 text-xs">4.9</span>
                    <span class="text-gray-400 text-[11px]">(128 reviews)</span>
                </div>

                <!-- Color Selection -->
                <div class="space-y-2">
                    <span class="font-semibold text-gray-900 uppercase tracking-wider text-[10px] block">COLOUR: <span
                            id="modal-color-label" class="font-normal text-gray-600">Black</span></span>
                    <div class="flex items-center gap-3">
                        <button onclick="selectModalColor('Black', this)"
                            class="modal-color-btn w-7 h-7 rounded-full bg-black ring-2 ring-black ring-offset-1 focus:outline-none"></button>
                        <button onclick="selectModalColor('White', this)"
                            class="modal-color-btn w-7 h-7 rounded-full bg-white border border-gray-300 focus:outline-none opacity-60"></button>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-[10px] uppercase font-semibold">
                        <span>SELECT SIZE</span>
                        <a href="javascript:void(0)" class="text-gray-400 underline">Size Guide</a>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="selectModalSize('XS', this)"
                            class="modal-size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">XS</button>
                        <button onclick="selectModalSize('S', this)"
                            class="modal-size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">S</button>
                        <button onclick="selectModalSize('M', this)"
                            class="modal-size-btn border border-black bg-black text-white px-3.5 py-2 rounded text-xs font-medium transition">M</button>
                        <button onclick="selectModalSize('L', this)"
                            class="modal-size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">L</button>
                        <button onclick="selectModalSize('XL', this)"
                            class="modal-size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">XL</button>
                    </div>
                </div>

                <!-- Quantity & Add to Bag -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-gray-200 rounded text-sm">
                            <button onclick="changeModalQty(-1)"
                                class="px-3 py-2 text-gray-500 hover:text-black">-</button>
                            <span id="modal-qty-val" class="px-3 py-2 font-medium">1</span>
                            <button onclick="changeModalQty(1)"
                                class="px-3 py-2 text-gray-500 hover:text-black">+</button>
                        </div>
                        <button onclick="addCurrentModalToCart()"
                            class="flex-1 bg-black text-white py-3 rounded font-medium text-xs hover:bg-gray-800 active:scale-95 transition">
                            Add to Bag
                        </button>
                    </div>
                </div>

                <!-- Description Dropdown Accordion -->
                <div class="border-t border-gray-100 pt-4 space-y-3 text-xs text-gray-600 leading-relaxed">
                    <details class="group border-b border-gray-100 pb-2">
                        <summary
                            class="flex justify-between items-center cursor-pointer font-medium text-gray-900 list-none">
                            <span>Description & Details</span>
                            <span class="transition group-open:rotate-180">+</span>
                        </summary>
                        <p class="mt-2 text-gray-500 text-[11px] leading-relaxed">
                            Made from 100% premium heavy cotton combbed 24s. Soft touch, durable print screen,
                            comfortable for daily streetwear style.
                        </p>
                    </details>
                    <details class="group border-b border-gray-100 pb-2">
                        <summary
                            class="flex justify-between items-center cursor-pointer font-medium text-gray-900 list-none">
                            <span>Shipping & Returns</span>
                            <span class="transition group-open:rotate-180">+</span>
                        </summary>
                        <p class="mt-2 text-gray-500 text-[11px] leading-relaxed">
                            Free shipping on orders over Rp300.000. Express delivery available nationwide within 2-4
                            business days.
                        </p>
                    </details>
                </div>

            </div>
        </div>
    </div>

    <!-- 4. SHOPPING BAG / CART DRAWER (MOCKUP SLIDE-OVER BAG FIGMA) -->
    <div id="cart-drawer-overlay" onclick="closeCartDrawer()"
        class="fixed inset-0 bg-black/40 z-50 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="cart-drawer"
        class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col justify-between">

        <!-- Header Drawer Cart -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-serif font-medium">Shopping Bag <span id="cart-items-count-text"
                    class="text-sm text-gray-400 font-sans">(0)</span></h2>
            <button onclick="closeCartDrawer()" class="p-1 text-gray-400 hover:text-black transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Content Cart Items -->
        <div id="cart-drawer-body" class="p-6 overflow-y-auto flex-1 space-y-4 text-xs text-gray-800">
            <!-- Dynamic Cart Items via JS -->
        </div>

        <!-- Footer Drawer Cart -->
        <div id="cart-drawer-footer" class="p-6 border-t border-gray-100 bg-white space-y-4">
            <div class="flex justify-between items-center text-sm font-medium">
                <span class="text-gray-600">Subtotal</span>
                <span id="cart-subtotal-val" class="font-semibold text-gray-900">Rp0</span>
            </div>
            <p class="text-[10px] text-gray-400">Taxes and shipping calculated at checkout.</p>
            <button onclick="alert('Lanjut ke Halaman Checkout!')"
                class="w-full bg-black text-white py-3.5 text-xs font-medium rounded hover:bg-gray-800 active:scale-95 transition">
                Checkout
            </button>
        </div>
    </div>

    <!-- 5. TOAST NOTIFIKASI BARANG BERHASIL DITAMBAH -->
    <div id="cart-toast"
        class="fixed bottom-6 right-6 bg-black text-white text-xs px-5 py-3.5 rounded-lg shadow-xl z-50 hidden flex items-center gap-3 transition duration-300 transform translate-y-10">
        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Item added to your shopping bag!</span>
    </div>

    <!-- 6. SIDEBAR FILTER DRAWER PANEL -->
    <div id="filter-overlay" onclick="closeFilterDrawer()"
        class="fixed inset-0 bg-black/40 z-50 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="filter-drawer"
        class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col justify-between">
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
                            <span class="text-gray-600 group-hover:text-black">Long Sleeve T-Shirt</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">24</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Short Sleeve" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Short Sleeve T-Shirt</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">18</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Baby Tee" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Baby Tee</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">16</span>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" value="Crop Top" onchange="applyFiltersAndSort()"
                                class="drawer-cat-checkbox rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                            <span class="text-gray-600 group-hover:text-black">Crop Top</span>
                        </div>
                        <span class="text-gray-400 text-[11px]">20</span>
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

        // CART STATE
        let cart = [];
        let currentModalProductIndex = null;
        let selectedModalColor = 'Black';
        let selectedModalSize = 'M';
        let selectedModalQty = 1;

        document.addEventListener('DOMContentLoaded', () => {
            renderRecentSearches();
            renderSuggestions('');
            applyFiltersAndSort();
            renderCartUI();
        });

        // 1. PRODUCT MODAL DETAIL FUNCTIONS
        function openProductModal(index) {
            closeSearchOverlay();
            currentModalProductIndex = index;
            selectedModalQty = 1;
            document.getElementById('modal-qty-val').innerText = '1';

            const item = document.querySelectorAll('.produk-item')[index];
            const nama = item.getAttribute('data-nama');
            const kategori = item.getAttribute('data-kategori');
            const harga = parseInt(item.getAttribute('data-harga'));
            const imgHitam = item.getAttribute('data-gambar-hitam');
            const imgPutih = item.getAttribute('data-gambar-putih');

            document.getElementById('modal-product-title').innerText = nama;
            document.getElementById('modal-product-cat').innerText = kategori;
            document.getElementById('modal-product-price').innerText = `Rp${harga.toLocaleString('id-ID')}`;

            document.getElementById('modal-main-img').src = imgHitam;
            document.getElementById('modal-thumb-0').src = imgHitam;
            document.getElementById('modal-thumb-1').src = imgPutih;

            selectModalColor('Black', document.querySelectorAll('.modal-color-btn')[0]);

            const backdrop = document.getElementById('product-modal-backdrop');
            const box = document.getElementById('product-modal-box');
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                box.classList.remove('scale-95');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeProductModal() {
            const backdrop = document.getElementById('product-modal-backdrop');
            const box = document.getElementById('product-modal-box');
            box.classList.add('scale-95');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function setModalThumbnail(thumbIndex) {
            const item = document.querySelectorAll('.produk-item')[currentModalProductIndex];
            const imgUrl = thumbIndex === 0 ? item.getAttribute('data-gambar-hitam') : item.getAttribute(
                'data-gambar-putih');
            document.getElementById('modal-main-img').src = imgUrl;

            const btns = document.querySelectorAll('.modal-thumb-btn');
            btns.forEach(b => {
                b.classList.remove('border-2', 'border-black');
                b.classList.add('border', 'border-gray-200', 'opacity-60');
            });
            btns[thumbIndex].classList.add('border-2', 'border-black');
            btns[thumbIndex].classList.remove('border-gray-200', 'opacity-60');
        }

        function selectModalColor(colorName, element) {
            selectedModalColor = colorName;
            document.getElementById('modal-color-label').innerText = colorName;

            const btns = document.querySelectorAll('.modal-color-btn');
            btns.forEach(b => {
                b.classList.remove('ring-2', 'ring-black', 'ring-offset-1');
                b.classList.add('opacity-60');
            });
            element.classList.add('ring-2', 'ring-black', 'ring-offset-1');
            element.classList.remove('opacity-60');

            setModalThumbnail(colorName === 'Black' ? 0 : 1);
        }

        function selectModalSize(sizeName, element) {
            selectedModalSize = sizeName;
            const btns = document.querySelectorAll('.modal-size-btn');
            btns.forEach(b => {
                b.className =
                    'modal-size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition';
            });
            element.className =
                'modal-size-btn border border-black bg-black text-white px-3.5 py-2 rounded text-xs font-medium transition';
        }

        function changeModalQty(delta) {
            selectedModalQty = Math.max(1, selectedModalQty + delta);
            document.getElementById('modal-qty-val').innerText = selectedModalQty;
        }

        function addCurrentModalToCart() {
            const item = document.querySelectorAll('.produk-item')[currentModalProductIndex];
            const nama = item.getAttribute('data-nama');
            const harga = parseInt(item.getAttribute('data-harga'));
            const gambar = selectedModalColor === 'Black' ? item.getAttribute('data-gambar-hitam') : item.getAttribute(
                'data-gambar-putih');

            const existingIndex = cart.findIndex(c => c.nama === nama && c.warna === selectedModalColor && c.ukuran ===
                selectedModalSize);

            if (existingIndex > -1) {
                cart[existingIndex].qty += selectedModalQty;
            } else {
                cart.push({
                    nama: nama,
                    harga: harga,
                    warna: selectedModalColor,
                    ukuran: selectedModalSize,
                    gambar: gambar,
                    qty: selectedModalQty
                });
            }

            closeProductModal();
            renderCartUI();
            showCartToast();
            openCartDrawer();
        }

        // 2. SHOPPING BAG / CART DRAWER FUNCTIONS
        function openCartDrawer() {
            closeSearchOverlay();
            closeFilterDrawer();
            const overlay = document.getElementById('cart-drawer-overlay');
            const drawer = document.getElementById('cart-drawer');

            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeCartDrawer() {
            const overlay = document.getElementById('cart-drawer-overlay');
            const drawer = document.getElementById('cart-drawer');

            drawer.classList.add('translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function updateCartQty(cartIdx, delta) {
            cart[cartIdx].qty += delta;
            if (cart[cartIdx].qty <= 0) {
                cart.splice(cartIdx, 1);
            }
            renderCartUI();
        }

        function removeCartItem(cartIdx) {
            cart.splice(cartIdx, 1);
            renderCartUI();
        }

        function renderCartUI() {
            const totalItems = cart.reduce((acc, c) => acc + c.qty, 0);
            const subtotal = cart.reduce((acc, c) => acc + (c.harga * c.qty), 0);

            document.getElementById('cart-count-desktop').innerText = totalItems;
            document.getElementById('cart-count-mobile').innerText = totalItems;
            document.getElementById('cart-items-count-text').innerText = `(${totalItems})`;

            const body = document.getElementById('cart-drawer-body');
            const footer = document.getElementById('cart-drawer-footer');

            if (cart.length === 0) {
                body.innerHTML = `
                    <div class="py-16 text-center space-y-4">
                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto text-gray-400">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-serif font-medium">Your bag is empty</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Looks like you haven't added any tees to your shopping bag yet.</p>
                        <button onclick="closeCartDrawer()" class="bg-black text-white text-xs px-6 py-2.5 rounded font-medium hover:bg-gray-800 active:scale-95 transition">
                            Start Shopping
                        </button>
                    </div>
                `;
                footer.classList.add('hidden');
            } else {
                footer.classList.remove('hidden');
                document.getElementById('cart-subtotal-val').innerText = `Rp${subtotal.toLocaleString('id-ID')}`;

                body.innerHTML = cart.map((item, idx) => `
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <img src="${item.gambar}" class="w-16 h-16 object-cover rounded bg-gray-100">
                        <div class="flex-1 space-y-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-medium text-gray-900 leading-tight">${item.nama}</h4>
                                <button onclick="removeCartItem(${idx})" class="text-gray-300 hover:text-black font-bold text-xs ml-1">×</button>
                            </div>
                            <p class="text-[10px] text-gray-400">Color: ${item.warna} | Size: ${item.ukuran}</p>
                            <div class="flex justify-between items-center pt-1">
                                <div class="flex items-center border border-gray-200 rounded text-xs">
                                    <button onclick="updateCartQty(${idx}, -1)" class="px-2 py-0.5 text-gray-500 hover:text-black">-</button>
                                    <span class="px-2 font-medium">${item.qty}</span>
                                    <button onclick="updateCartQty(${idx}, 1)" class="px-2 py-0.5 text-gray-500 hover:text-black">+</button>
                                </div>
                                <span class="font-semibold text-gray-900 text-xs">Rp${(item.harga * item.qty).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        function showCartToast() {
            const toast = document.getElementById('cart-toast');
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.remove('translate-y-10'), 10);
            setTimeout(() => {
                toast.classList.add('translate-y-10');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 2500);
        }

        // 3. SEARCH OVERLAY FUNCTIONS
        function openSearchOverlay() {
            document.getElementById('search-overlay-box').classList.remove('hidden');
            document.getElementById('search-backdrop').classList.remove('hidden');
            renderSuggestions(document.getElementById('search-input').value);
        }

        function closeSearchOverlay() {
            document.getElementById('search-overlay-box').classList.add('hidden');
            document.getElementById('search-backdrop').classList.add('hidden');
        }

        function handleSearchInput() {
            const query = document.getElementById('search-input').value;
            const clearBtn = document.getElementById('clear-search-btn');

            if (query.trim() !== '') {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            renderSuggestions(query);
            applyFiltersAndSort();
        }

        function clearSearchInput() {
            const input = document.getElementById('search-input');
            input.value = '';
            document.getElementById('clear-search-btn').classList.add('hidden');
            renderSuggestions('');
            applyFiltersAndSort();
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
                    <button onclick="removeRecentSearch(${idx})" class="text-gray-300 hover:text-black text-xs font-bold">×</button>
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
                    <li onclick="applySearchTag('Pinky Fish')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Pinky Fish Baby Tee</span><span class="text-gray-400">↗</span>
                    </li>
                    <li onclick="applySearchTag('Crop Top Snoopy')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Crop Top Snoopy</span><span class="text-gray-400">↗</span>
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

            prevContainer.innerHTML = matchingItems.slice(0, 3).map((item, idx) => `
                <div onclick="openProductModal(${item.getAttribute('data-index')})" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer">
                    <img src="${item.getAttribute('data-gambar-hitam')}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${item.getAttribute('data-nama')}</h5>
                        <p class="text-[10px] text-gray-400">${item.getAttribute('data-kategori')}</p>
                        <p class="text-[11px] font-semibold text-gray-800">Rp${parseInt(item.getAttribute('data-harga')).toLocaleString('id-ID')}</p>
                    </div>
                </div>
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

            renderSuggestions(tagText);
            applyFiltersAndSort();
        }

        // 4. SIDEBAR FILTER DRAWER FUNCTIONS
        function openFilterDrawer() {
            closeSearchOverlay();
            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeFilterDrawer() {
            const overlay = document.getElementById('filter-overlay');
            const drawer = document.getElementById('filter-drawer');

            drawer.classList.add('translate-x-full');
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

        function toggleColorSwatch(btn, colorName) {
            if (activeColorFilter === colorName) {
                activeColorFilter = null;
                btn.classList.add('opacity-60');
            } else {
                activeColorFilter = colorName;
                document.querySelectorAll('.color-swatch-btn').forEach(s => s.classList.add('opacity-60'));
                btn.classList.remove('opacity-60');
            }
            applyFiltersAndSort();
        }

        function clearAllFilters() {
            document.getElementById('search-input').value = '';
            document.getElementById('clear-search-btn').classList.add('hidden');

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

            applyFiltersAndSort();
        }

        function pilihWarna(index, gambarUrl, element) {
            document.getElementById('produk-img-' + index).src = gambarUrl;
            const parent = element.parentElement;
            const buttons = parent.querySelectorAll('button');
            buttons.forEach(btn => btn.classList.remove('ring-2', 'ring-black', 'ring-offset-1'));
            element.classList.add('ring-2', 'ring-black', 'ring-offset-1');
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

        // 5. MASTER FILTER & SORT FUNCTION
        function applyFiltersAndSort() {
            const searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
            const rawSearchQuery = document.getElementById('search-input').value.trim();
            const sortVal = document.getElementById('sort-select').value;
            const container = document.getElementById('katalog-grid');
            const items = Array.from(document.querySelectorAll('.produk-item'));

            const selectedDrawerCats = Array.from(document.querySelectorAll('.drawer-cat-checkbox:checked')).map(cb => cb
                .value);
            const selectedSizes = Array.from(document.querySelectorAll('.active-size-filter')).map(btn => btn.getAttribute(
                'data-size'));

            let visibleCount = 0;

            items.forEach(item => {
                const itemKategori = item.getAttribute('data-kategori');
                const itemNama = item.getAttribute('data-nama').toLowerCase();

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
                    if (selectedDrawerCats.includes('Short Sleeve') && itemKategori === 'Short Sleeve') {
                        matchesDrawerCategory = true;
                    } else {
                        matchesDrawerCategory = selectedDrawerCats.includes(itemKategori);
                    }
                }

                let matchesSearch = itemNama.includes(searchQuery);

                if (matchesPillCategory && matchesDrawerCategory && matchesSearch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // SWITCH KATALOG TAMPIL ATAU KOSONG (NO RESULTS)
            const mainHeaderSection = document.getElementById('main-header-section');
            const katalogGrid = document.getElementById('katalog-grid');
            const noResultsContainer = document.getElementById('no-results-container');
            const loadMoreSection = document.getElementById('load-more-section');

            if (visibleCount === 0) {
                mainHeaderSection.classList.add('hidden');
                katalogGrid.classList.add('hidden');
                loadMoreSection.classList.add('hidden');

                noResultsContainer.classList.remove('hidden');
                document.getElementById('no-results-query-text').innerText = rawSearchQuery || currentKategori ||
                    'Selected Filter';
            } else {
                mainHeaderSection.classList.remove('hidden');
                katalogGrid.classList.remove('hidden');
                loadMoreSection.classList.remove('hidden');

                noResultsContainer.classList.add('hidden');
            }

            const headingEl = document.getElementById('page-heading');
            const subheadingEl = document.getElementById('page-subheading');
            const headerCountEl = document.getElementById('header-item-count');

            if (rawSearchQuery !== '') {
                headingEl.innerText = `Results for "${rawSearchQuery}"`;
                subheadingEl.innerText = 'Showing products that match your search and the filters you\'ve applied.';
            } else if (currentKategori !== 'All') {
                headingEl.innerText = `Results for "${currentKategori}"`;
                subheadingEl.innerText = 'Showing products that match your search and the filters you\'ve applied.';
            } else if (selectedDrawerCats.length > 0) {
                headingEl.innerText = `Results for "${selectedDrawerCats.join(', ')}"`;
                subheadingEl.innerText = 'Showing products that match your search and the filters you\'ve applied.';
            } else {
                headingEl.innerText = 'Apparel';
                subheadingEl.innerText =
                    'Your favorite tees, your own kind of vibe from cute graphics to fun prints, find a tee that feels just like you!';
            }

            headerCountEl.innerText = `${visibleCount} Items`;

            // Active Badges / Chips
            const activeTagsBar = document.getElementById('active-filters-bar');
            const activeTagsList = document.getElementById('active-tags-list');
            let tagsHTML = '';
            let totalActiveFilters = 0;

            if (currentKategori !== 'All') {
                totalActiveFilters++;
                tagsHTML += `<span class="bg-gray-100 border border-gray-200 text-gray-800 px-3 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
                    ${currentKategori} <button onclick="filterKategori('All', document.querySelector('.btn-kategori'))" class="hover:text-red-600 font-bold ml-1">×</button>
                </span>`;
            }

            selectedDrawerCats.forEach(cat => {
                totalActiveFilters++;
                tagsHTML += `<span class="bg-gray-100 border border-gray-200 text-gray-800 px-3 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
                    ${cat} <button onclick="uncheckDrawerCat('${cat}')" class="hover:text-red-600 font-bold ml-1">×</button>
                </span>`;
            });

            selectedSizes.forEach(size => {
                totalActiveFilters++;
                tagsHTML += `<span class="bg-gray-100 border border-gray-200 text-gray-800 px-3 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
                    Size ${size} <button onclick="removeSizeFilter('${size}')" class="hover:text-red-600 font-bold ml-1">×</button>
                </span>`;
            });

            if (activeColorFilter) {
                totalActiveFilters++;
                tagsHTML += `<span class="bg-gray-100 border border-gray-200 text-gray-800 px-3 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
                    ${activeColorFilter} <button onclick="toggleColorSwatch(document.querySelector('.color-swatch-btn'), '${activeColorFilter}')" class="hover:text-red-600 font-bold ml-1">×</button>
                </span>`;
            }

            if (totalActiveFilters > 0) {
                activeTagsList.innerHTML = tagsHTML;
                activeTagsBar.classList.remove('hidden');
                activeTagsBar.classList.add('flex');
            } else {
                activeTagsBar.classList.add('hidden');
                activeTagsBar.classList.remove('flex');
            }

            document.getElementById('filters-btn-count').innerText = `(${totalActiveFilters})`;
            document.getElementById('drawer-filter-count').innerText = `(${totalActiveFilters})`;

            const resultsCountEl = document.getElementById('results-count');
            const showingTextEl = document.getElementById('showing-text');
            if (resultsCountEl) resultsCountEl.innerText = visibleCount;
            if (showingTextEl) showingTextEl.innerText = `Showing ${visibleCount} of 128 items`;

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

        function uncheckDrawerCat(catValue) {
            const cb = Array.from(document.querySelectorAll('.drawer-cat-checkbox')).find(c => c.value === catValue);
            if (cb) cb.checked = false;
            applyFiltersAndSort();
        }

        function removeSizeFilter(sizeVal) {
            const btn = Array.from(document.querySelectorAll('.size-btn')).find(b => b.getAttribute('data-size') ===
                sizeVal);
            if (btn) toggleSizeBtn(btn);
        }
    </script>

</body>

</html>
