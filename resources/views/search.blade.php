<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative overflow-x-hidden">

    <!-- SEARCH BACKDROP (Z-INDEX 40 SUPAYA MENAHAN KLIK LAYAR BELAKANG) -->
    <div id="search-backdrop" onclick="closeSearchOverlay()"
        class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity"></div>

    <!-- 1. NAVBAR & SEARCH SECTION -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <nav class="px-4 md:px-12 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 w-full">

                <!-- LOGO KIRI -->
                <div class="flex items-center justify-between w-full md:w-auto">
                    <a href="{{ route('home') }}"
                        class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition active:scale-95 inline-block">ASYART.MERCH</a>
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
                            autocomplete="off" value="{{ $query ?? '' }}" oninput="handleSearchInput()"
                            class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">
                        <button type="button" id="clear-search-btn" onclick="clearSearchInput()"
                            class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-sm font-bold cursor-pointer">×</button>
                    </form>
                </div>

                <!-- TOMBOL KEMBALI KE HOME -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('home') }}"
                        class="text-xs font-medium border border-black text-gray-900 bg-transparent px-3 py-1.5 rounded hover:bg-black hover:text-white active:scale-95 transition duration-200 inline-block">
                        Home
                    </a>
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
                        autocomplete="off" value="{{ $query ?? '' }}" oninput="handleSearchInput()"
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

    <!-- CONTAINER UTAMA HASIL PENCARIAN -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8">

        <!-- HEADER SEARCH -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-serif mb-2">Search Results for "{{ $query ?? $keyword }}"</h1>
            <p class="text-xs text-gray-500">
                @if (isset($results) && count($results) > 0)
                    Menampilkan {{ count($results) }} produk yang sesuai.
                @else
                    Menampilkan 0 produk yang sesuai.
                @endif
            </p>
        </div>

        <!-- HIDDEN DUMMY ITEMS UNTUK REFERENSI JS SEARCH OVERLAY -->
        <div id="katalog-grid" class="hidden">
            <div class="produk-item" data-nama="Baby Tee Bang Windah" data-kategori="Baby Tee" data-harga="90000"
                data-index="0" data-gambar-hitam="{{ asset('images/windahcrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/windahcrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Short Sleeve Bocil Bang Windah" data-kategori="Short Sleeve"
                data-harga="85000" data-index="1" data-gambar-hitam="{{ asset('images/windahreg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/windahreg-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Longsleeve Bang Windah" data-kategori="Long Sleeve"
                data-harga="110000" data-index="2" data-gambar-hitam="{{ asset('images/windahpnjg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/windahpnjg-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Baby Tee Snoopy" data-kategori="Baby Tee" data-harga="90000"
                data-index="3" data-gambar-hitam="{{ asset('images/snpcrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/snpcrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Baby Tee Radio Head" data-kategori="Baby Tee" data-harga="85000"
                data-index="4" data-gambar-hitam="{{ asset('images/radiocrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/radiocrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Baby Tee Cake" data-kategori="Baby Tee" data-harga="110000"
                data-index="5" data-gambar-hitam="{{ asset('images/cakecrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/cakecrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Baby Tee Blue Star Fish" data-kategori="Baby Tee" data-harga="95000"
                data-index="6" data-gambar-hitam="{{ asset('images/ikanbirucrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/ikanbirucrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Short Sleeve Circle Fish" data-kategori="Short Sleeve"
                data-harga="85000" data-index="7" data-gambar-hitam="{{ asset('images/ikanbulatreg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/ikanbulatreg-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Longsleeve Puffer Fish" data-kategori="Long Sleeve"
                data-harga="115000" data-index="8" data-gambar-hitam="{{ asset('images/ikanbuntalpnjg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/ikanbuntalpnjg-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Baby Tee Snoopy & Bang Windah" data-kategori="Baby Tee"
                data-harga="75000" data-index="9" data-gambar-hitam="{{ asset('images/npcrop-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/npcrop-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Short Sleeve Bocil Sigma" data-kategori="Short Sleeve"
                data-harga="90000" data-index="10" data-gambar-hitam="{{ asset('images/bclreg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/bclreg-pth.jpg') }}"></div>
            <div class="produk-item" data-nama="Longsleeve Orange Fish" data-kategori="Long Sleeve"
                data-harga="80000" data-index="11" data-gambar-hitam="{{ asset('images/ikanorenpnjg-htm.jpg') }}"
                data-gambar-putih="{{ asset('images/ikanorenpnjg-pth.jpg') }}"></div>
        </div>

        <!-- GRID PRODUK HASIL PENCARIAN -->
        @if (isset($results) && count($results) > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
                @foreach ($results as $item)
                    <div class="group produk-item-result">
                        <!-- Box Gambar -->
                        <a href="{{ route('product.detail', $item['id']) }}"
                            class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block cursor-pointer">
                            <img src="{{ asset('images/' . $item['gambar_hitam']) }}" alt="{{ $item['nama'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </a>

                        <!-- Teks Info Produk -->
                        <a href="{{ route('product.detail', $item['id']) }}" class="block">
                            <h3
                                class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline cursor-pointer">
                                {{ $item['nama'] }}
                            </h3>
                        </a>
                        <p class="text-[11px] text-gray-400">{{ $item['kategori'] }}</p>
                        <p class="text-xs font-semibold mt-1">{{ $item['harga'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <!-- KONDISI JIKA KOSONG -->
            <div class="border border-gray-100 rounded-lg p-12 text-center space-y-4 mb-12">
                <p class="text-sm text-gray-600">Waduh, produk yang lu cari gak ketemu cuy.</p>
                <p class="text-xs text-gray-400">Coba kata kunci lain atau cek rekomendasi produk di bawah ini.</p>
                <div>
                    <a href="{{ route('home') }}"
                        class="inline-block bg-black text-white text-xs px-6 py-2.5 rounded hover:opacity-80 transition">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        @endif

        <!-- REKOMENDASI / YOU MIGHT LIKE -->
        <div class="pt-8 border-t border-gray-100">
            <h2 class="text-xl font-serif mb-6">You might like</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="group">
                    <a href="{{ route('product.detail', 1) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block">
                        <img src="{{ asset('images/windahreg-htm.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>
                    <h3 class="text-xs md:text-sm font-medium">Short Sleeve Bocil Bang Windah</h3>
                    <p class="text-[11px] text-gray-400">Short Sleeve</p>
                    <p class="text-xs font-semibold mt-1">Rp85.000</p>
                </div>
                <div class="group">
                    <a href="{{ route('product.detail', 2) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block">
                        <img src="{{ asset('images/windahpnjg-htm.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>
                    <h3 class="text-xs md:text-sm font-medium">Longsleeve Bang Windah</h3>
                    <p class="text-[11px] text-gray-400">Long Sleeve</p>
                    <p class="text-xs font-semibold mt-1">Rp110.000</p>
                </div>
                <div class="group">
                    <a href="{{ route('product.detail', 3) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block">
                        <img src="{{ asset('images/snpcrop-htm.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>
                    <h3 class="text-xs md:text-sm font-medium">Baby Tee Snoopy</h3>
                    <p class="text-[11px] text-gray-400">Baby Tee</p>
                    <p class="text-xs font-semibold mt-1">Rp90.000</p>
                </div>
                <div class="group">
                    <a href="{{ route('product.detail', 0) }}"
                        class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative block">
                        <img src="{{ asset('images/windahcrop-htm.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>
                    <h3 class="text-xs md:text-sm font-medium">Baby Tee Bang Windah</h3>
                    <p class="text-[11px] text-gray-400">Baby Tee</p>
                    <p class="text-xs font-semibold mt-1">Rp90.000</p>
                </div>
            </div>
        </div>

    </main>

    <!-- SCRIPT JAVASCRIPT SEARCH OVERLAY -->
    <script>
        let recentSearches = ['Long Sleeve', 'Baby Tee', 'Baby Tee Bang Windah'];
        let scrollPosition = 0;

        document.addEventListener('DOMContentLoaded', () => {
            renderRecentSearches();
            renderSuggestions('');

            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');

            if (desktopInput) {
                desktopInput.addEventListener('focus', openSearchOverlay);
                if (desktopInput.value.trim().length > 0) {
                    document.getElementById('clear-search-btn').style.display = 'flex';
                }
            }
            if (mobileInput) {
                mobileInput.addEventListener('focus', openSearchOverlay);
                if (mobileInput.value.trim().length > 0) {
                    document.getElementById('clear-search-btn-mobile').style.display = 'flex';
                }
            }
        });

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
            const items = Array.from(document.querySelectorAll('#katalog-grid .produk-item'));

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
                    <li onclick="applySearchTag('Baby Tee Snoopy')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Baby Tee Snoopy</span><span class="text-gray-400">↗</span>
                    </li>
                    <li onclick="applySearchTag('Baby Tee')" class="cursor-pointer text-gray-600 hover:text-black flex items-center justify-between">
                        <span>🔍 Baby Tee</span><span class="text-gray-400">↗</span>
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
                <a href="/product/${item.getAttribute('data-index')}" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer block">
                    <img src="${item.getAttribute('data-gambar-hitam')}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${item.getAttribute('data-nama')}</h5>
                        <p class="text-[10px] text-gray-400">${item.getAttribute('data-kategori')}</p>
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

            window.location.href = "{{ route('search.page') }}?q=" + encodeURIComponent(tagText);
        }
    </script>

</body>

</html>A
