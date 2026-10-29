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

    <!-- 1. NAVBAR DENGAN SEARCH BAR -->
    <nav class="border-b border-gray-200 px-4 md:px-12 py-4 relative z-40 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition inline-block">ASYART.MERCH</a>

            <!-- SEARCH BAR DI NAVBAR -->
            <div class="hidden md:block w-[400px] relative">
                <form action="{{ route('search.page') }}" method="GET" class="relative flex items-center">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search products"
                        autocomplete="off"
                        class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-4 py-1.5 text-xs focus:outline-none focus:border-black transition duration-200">
                </form>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('cart.page') }}" class="relative hover:opacity-75 transition duration-150 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span id="cart-count-desktop"
                        class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                </a>
                <a href="{{ route('home') }}" class="text-xs font-semibold hover:underline">Home</a>
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
                <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search products"
                    autocomplete="off"
                    class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-xs focus:outline-none focus:border-black transition duration-200">
            </div>
        </form>
    </div>

    <!-- CONTAINER UTAMA HASIL PENCARIAN -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-12 space-y-12">

        <!-- HEADER PENCARIAN -->
        <div>
            <h1 class="text-2xl md:text-3xl font-serif font-medium mb-2">
                Search Results for "{{ $query ?? '' }}"
            </h1>
            <p class="text-xs text-gray-500">
                Menampilkan {{ isset($results) ? count($results) : 0 }} produk yang sesuai.
            </p>
        </div>

        @if (isset($results) && count($results) > 0)
            <!-- GRID PRODUK JIKA KETEMU -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($results as $index => $item)
                    <div class="group produk-item space-y-2">
                        <a href="{{ route('product.detail', $item['id'] ?? 0) }}"
                            class="aspect-square bg-gray-100 rounded-md overflow-hidden border border-gray-200 block">
                            <img src="{{ asset('images/' . $item['gambar_hitam']) }}" alt="{{ $item['nama'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </a>
                        <div>
                            <a href="{{ route('product.detail', $item['id'] ?? 0) }}" class="block">
                                <h4 class="text-xs font-medium text-gray-900 group-hover:underline">{{ $item['nama'] }}
                                </h4>
                            </a>
                            <p class="text-[10px] text-gray-400">{{ $item['kategori'] }}</p>
                            <p class="text-xs font-semibold text-gray-900 mt-0.5">{{ $item['harga'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- KONDISI JIKA TIDAK KETEMU (KOSONG) -->
            <div class="text-center py-12 space-y-6 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-800">Waduh, produk yang lu cari gak ketemu cuy.</p>
                    <p class="text-xs text-gray-400">Coba kata kunci lain atau cek rekomendasi produk di bawah ini.</p>
                </div>
                <div>
                    <a href="{{ route('home') }}"
                        class="inline-block border border-black bg-black text-white px-6 py-2.5 rounded text-xs font-medium hover:bg-gray-800 active:scale-95 transition">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>

            <!-- SECTION YOU MIGHT LIKE -->
            <div class="border-t border-gray-200 pt-12 space-y-6">
                <h2 class="text-xl md:text-2xl font-serif font-medium">You might like</h2>

                <!-- Grid Produk Rekomendasi Acak -->
                <div id="dynamic-recommendations" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    <!-- Dirender via JavaScript secara random -->
                </div>
            </div>
        @endif

    </main>

    <!-- SCRIPT JAVASCRIPT -->
    <script>
        const poolProducts = [{
                id: 0,
                nama: "Crop Top Bang Windah",
                kategori: "Crop Top",
                harga: "Rp90.000",
                gambar: "{{ asset('images/windahcrop-htm.jpg') }}"
            },
            {
                id: 1,
                nama: "Regular Tee Bocil Bang Windah",
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
                nama: "Crop Top Snoopy",
                kategori: "Crop Top",
                harga: "Rp90.000",
                gambar: "{{ asset('images/snpcrop-htm.jpg') }}"
            },
            {
                id: 4,
                nama: "Crop Top Radio Head",
                kategori: "Crop Top",
                harga: "Rp85.000",
                gambar: "{{ asset('images/radiocrop-htm.jpg') }}"
            },
            {
                id: 6,
                nama: "Crop Top Blue Star Fish",
                kategori: "Crop Top",
                harga: "Rp95.000",
                gambar: "{{ asset('images/ikanbirucrop-htm.jpg') }}"
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCountUI();
            generateRecommendations();
        });

        function generateRecommendations() {
            const recommendationContainer = document.getElementById('dynamic-recommendations');
            if (recommendationContainer) {
                const shuffledProducts = [...poolProducts].sort(() => 0.5 - Math.random()).slice(0, 4);

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
            const badge = document.getElementById('cart-count-desktop');
            if (badge) badge.innerText = totalItems;
        }
    </script>

</body>

</html>
