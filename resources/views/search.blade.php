<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - ASYART.MERCH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans relative overflow-x-hidden">

    <!-- SEARCH BACKDROP -->
    <div id="search-backdrop" onclick="closeSearchOverlay()"
        class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity"></div>

    <!-- 1. NAVBAR DENGAN SEARCH BAR -->
    <div class="sticky top-0 z-50 bg-white">
        <nav class="border-b border-gray-200 px-4 md:px-12 py-4 relative bg-white">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

                <a href="{{ route('home') }}"
                    class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition inline-block">
                    ASYART.MERCH
                </a>

                <!-- SEARCH BAR DESKTOP -->
                <div class="hidden md:block w-[450px] relative">
                    <form action="{{ route('search') }}" method="GET" onsubmit="handleSearchSubmit(this)" class="relative flex items-center">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        <input type="text"
                            id="search-input"
                            name="q"
                            value="{{ $query ?? '' }}"
                            placeholder="Search products"
                            autocomplete="off"
                            oninput="handleSearchInput()"
                            class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">

                        <button type="button"
                            id="clear-search-btn"
                            onclick="clearSearchInput()"
                            class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-sm font-bold cursor-pointer">
                            ×
                        </button>
                    </form>
                </div>

                <!-- CART DAN HOME -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('cart.page') }}"
                        class="relative hover:opacity-75 transition duration-150 mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-count-desktop"
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">
                            {{ auth()->check() && auth()->user()->role === 'customer' ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : 0 }}
                        </span>
                    </a>

                    <a href="{{ route('home') }}"
                        class="text-xs font-semibold hover:underline">
                        Home
                    </a>
                </div>
            </div>
        </nav>

        <!-- 2. SEARCH BAR MOBILE -->
        <div class="px-4 py-2 md:hidden bg-white border-b border-gray-100 relative">
            <form action="{{ route('search') }}" method="GET" onsubmit="handleSearchSubmit(this)" class="w-full relative">
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>

                    <input type="text"
                        id="search-input-mobile"
                        name="q"
                        value="{{ $query ?? '' }}"
                        placeholder="Search products"
                        autocomplete="off"
                        oninput="handleSearchInput()"
                        class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-black transition duration-200">

                    <button type="button"
                        id="clear-search-btn-mobile"
                        onclick="clearSearchInput()"
                        class="absolute inset-y-0 right-3 hidden items-center text-gray-400 hover:text-black text-sm font-bold cursor-pointer">
                        ×
                    </button>
                </div>
            </form>
        </div>

        <!-- SHARED SEARCH OVERLAY DROPDOWN (MUNCUL JUGA DI HALAMAN SEARCH) -->
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
                            <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">RECENT SEARCHES</h4>
                            <button type="button" onclick="clearAllRecentSearches()"
                                class="text-[10px] text-gray-400 hover:text-black underline cursor-pointer">Clear</button>
                        </div>
                        <ul id="recent-searches-list" class="space-y-1 md:space-y-1.5"></ul>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 md:mb-2">POPULAR</h4>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" onclick="applySearchTag('Long Sleeve')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Long Sleeve</button>
                            <button type="button" onclick="applySearchTag('Short Sleeve')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Short Sleeve</button>
                            <button type="button" onclick="applySearchTag('Baby Tee')"
                                class="border border-gray-200 px-2 py-0.5 md:px-2.5 md:py-1 rounded text-gray-700 hover:border-black transition">Baby Tee</button>
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

    <!-- 3. SEARCH RESULTS CONTENT -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8 space-y-8">

        <!-- TOMBOL BACK + HEADER PENCARIAN -->
        <div class="space-y-4">
            <div>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-black border border-gray-200 hover:border-black px-3 py-1.5 rounded-md transition active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Katalog
                </a>
            </div>

            <div>
                <h1 class="text-2xl md:text-3xl font-serif font-medium mb-2">
                    Search Results for "{{ $query ?? '' }}"
                </h1>

                <p class="text-xs text-gray-500">
                    Menampilkan {{ count($results ?? []) }} produk yang sesuai.
                </p>
            </div>
        </div>

        @if (!empty($results) && count($results) > 0)

            <!-- GRID HASIL PENCARIAN -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($results as $item)
                    <div class="group produk-item space-y-2">
                        <a href="{{ route('product.show', $item['id']) }}"
                            class="aspect-square bg-gray-100 rounded-md overflow-hidden border border-gray-200 block">
                            <img src="{{ asset('images/' . $item['gambar_hitam']) }}"
                                alt="{{ $item['nama'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </a>

                        <div>
                            <a href="{{ route('product.show', $item['id']) }}" class="block">
                                <h4 class="text-xs font-medium text-gray-900 group-hover:underline">
                                    {{ $item['nama'] }}
                                </h4>
                            </a>

                            <p class="text-[10px] text-gray-400">
                                {{ $item['kategori'] }}
                            </p>

                            <p class="text-xs font-semibold text-gray-900 mt-0.5">
                                {{ $item['harga'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        @else

            <!-- JIKA PRODUK TIDAK DITEMUKAN -->
            <div class="text-center py-12 space-y-6 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-800">
                        Waduh, produk yang lu cari gak ketemu cuy.
                    </p>
                    <p class="text-xs text-gray-400">
                        Coba kata kunci lain atau cek rekomendasi produk di bawah ini.
                    </p>
                </div>

                <a href="{{ route('home') }}"
                    class="inline-block border border-black bg-black text-white px-6 py-2.5 rounded text-xs font-medium hover:bg-gray-800 active:scale-95 transition">
                    Kembali ke Katalog
                </a>
            </div>

            <!-- REKOMENDASI PRODUK -->
            @if (!empty($recommendations) && count($recommendations) > 0)
                <div class="border-t border-gray-200 pt-12 space-y-6">
                    <h2 class="text-xl md:text-2xl font-serif font-medium">
                        {{ $hasSimilar ? 'Mungkin maksud kamu?' : 'You might like' }}
                    </h2>

                    @if ($hasSimilar)
                        <p class="text-xs text-gray-500">
                            Kami menemukan beberapa produk dengan nama yang mirip dengan "{{ $query }}".
                        </p>
                    @else
                        <p class="text-xs text-gray-500">
                            Coba cek beberapa rekomendasi produk berikut.
                        </p>
                    @endif

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($recommendations as $item)
                            <div class="group space-y-2">
                                <a href="{{ route('product.show', $item['id']) }}"
                                    class="aspect-square bg-gray-100 rounded-md overflow-hidden border border-gray-200 block">
                                    <img src="{{ asset('images/' . $item['gambar_hitam']) }}"
                                        alt="{{ $item['nama'] }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </a>

                                <div>
                                    <a href="{{ route('product.show', $item['id']) }}">
                                        <h4 class="text-xs font-medium text-gray-900 group-hover:underline">
                                            {{ $item['nama'] }}
                                        </h4>
                                    </a>
                                    <p class="text-[10px] text-gray-400">
                                        {{ $item['kategori'] }}
                                    </p>
                                    <p class="text-xs font-semibold text-gray-900 mt-0.5">
                                        {{ $item['harga'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

    </main>

    <!-- 4. SEARCH BAR & DROPDOWN OVERLAY SCRIPT -->
    <script>
        const ALL_CATALOG = @json($katalog ?? []);
        const IMAGE_BASE_URL = "{{ asset('images') }}/";
        const RECENT_STORAGE_KEY = 'asyart_recent_searches';
        let recentSearches = JSON.parse(localStorage.getItem(RECENT_STORAGE_KEY) || '["Long Sleeve", "Baby Tee"]');

        function esc(str) {
            return String(str).replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        function enc(str) {
            return encodeURIComponent(str).replace(/'/g, '%27');
        }

        function getSearchValue() {
            const d = document.getElementById('search-input');
            const m = document.getElementById('search-input-mobile');
            if (document.activeElement === m) return m.value;
            if (document.activeElement === d) return d.value;
            return (d && d.value.trim() !== '') ? d.value : (m ? m.value : '');
        }

        function saveRecentSearch(term) {
            const clean = String(term || '').trim();
            if (!clean) return;
            recentSearches = recentSearches.filter(item => item.toLowerCase() !== clean.toLowerCase());
            recentSearches.unshift(clean);
            if (recentSearches.length > 5) recentSearches.pop();
            localStorage.setItem(RECENT_STORAGE_KEY, JSON.stringify(recentSearches));
            renderRecentSearches();
        }

        function handleSearchSubmit(formEl) {
            const inputEl = formEl ? formEl.querySelector('input[name="q"]') : null;
            const q = inputEl ? inputEl.value.trim() : getSearchValue().trim();
            if (q) saveRecentSearch(q);
        }

        function openSearchOverlay() {
            document.getElementById('search-overlay-box').classList.remove('hidden');
            document.getElementById('search-backdrop').classList.remove('hidden');
            recentSearches = JSON.parse(localStorage.getItem(RECENT_STORAGE_KEY) || '["Long Sleeve", "Baby Tee"]');
            renderRecentSearches();
            renderSuggestions(getSearchValue());
        }

        function closeSearchOverlay() {
            document.getElementById('search-overlay-box').classList.add('hidden');
            document.getElementById('search-backdrop').classList.add('hidden');
        }

        function handleSearchInput() {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');
            const clearBtnDesktop = document.getElementById('clear-search-btn');
            const clearBtnMobile = document.getElementById('clear-search-btn-mobile');

            const query = getSearchValue();

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

            if (desktopInput) desktopInput.focus();
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
            localStorage.setItem(RECENT_STORAGE_KEY, JSON.stringify(recentSearches));
            renderRecentSearches();
        }

        function clearAllRecentSearches() {
            recentSearches = [];
            localStorage.setItem(RECENT_STORAGE_KEY, JSON.stringify(recentSearches));
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

            const matchingItems = ALL_CATALOG.filter(item => {
                const nama = String(item.nama || '').toLowerCase();
                const kategori = String(item.kategori || '').toLowerCase();
                return nama.includes(q) || kategori.includes(q);
            });

            if (q === '') {
                sugContainer.innerHTML = ['Longsleeve Bang Windah', 'Baby Tee Snoopy', 'Baby Tee']
                    .map(suggestionItem).join('');
            } else {
                sugContainer.innerHTML = matchingItems.slice(0, 4)
                    .map(item => suggestionItem(item.nama)).join('') ||
                    '<li class="text-gray-400">No suggestions found</li>';
            }

            if (resultCountEl) resultCountEl.innerText = matchingItems.length;

            prevContainer.innerHTML = matchingItems.slice(0, 3).map(item => `
                <a href="/product/${encodeURIComponent(item.id)}" class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer">
                    <img src="${esc(IMAGE_BASE_URL + (item.gambar_hitam || item.gambar_putih || ''))}" class="w-10 h-10 object-cover rounded bg-gray-100">
                    <div>
                        <h5 class="text-xs font-medium text-gray-900 leading-tight">${esc(item.nama)}</h5>
                        <p class="text-[10px] text-gray-400">${esc(item.kategori)}</p>
                        <p class="text-[11px] font-semibold text-gray-800">${esc(item.harga)}</p>
                    </div>
                </a>
            `).join('') || '<p class="text-gray-400 text-xs">No matching products</p>';
        }

        function applySearchTag(tagText) {
            saveRecentSearch(tagText);
            window.location.href = "{{ route('search') }}?q=" + encodeURIComponent(tagText);
        }

        function viewAllResults() {
            const q = getSearchValue().trim();
            if (q) saveRecentSearch(q);
            window.location.href = "{{ route('search') }}?q=" + encodeURIComponent(q);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const desktopInput = document.getElementById('search-input');
            const mobileInput = document.getElementById('search-input-mobile');

            renderRecentSearches();
            handleSearchInput();

            if (desktopInput) desktopInput.addEventListener('focus', openSearchOverlay);
            if (mobileInput) mobileInput.addEventListener('focus', openSearchOverlay);
        });
    </script>

</body>
</html>