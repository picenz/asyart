<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASYART.MERCH - Apparel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-900 font-sans">

    <!-- 1. NAVBAR -->
    <nav class="border-b border-gray-200 px-4 md:px-12 py-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center justify-between w-full md:w-auto">
                <a href="#" class="text-xl font-bold tracking-wider font-serif">ASYART.MERCH</a>
                <div class="flex items-center gap-4 md:hidden">
                    <button class="relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center">4</span>
                    </button>
                    <button>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="w-full md:w-96 relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" placeholder="Search products"
                    class="w-full bg-gray-50 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:border-black">
            </div>

            <div class="hidden md:flex items-center gap-5">
                <button class="relative hover:opacity-75">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center">4</span>
                </button>
                @guest
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
@else
    <a href="{{ route('profile.edit') }}">
        {{ Auth::user()->name }}
    </a>
@endguest
            </div>
        </div>
    </nav>

    <!-- CONTAINER UTAMA HALAMAN -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8">

        <!-- 2. HEADER APPAREL -->
        <div class="flex justify-between items-start mb-6">
            <div class="max-w-md">
                <h1 class="text-3xl md:text-4xl font-serif mb-2">Apparel</h1>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Your favorite tees, your own kind of vibe from cute graphics to fun prints, find a tee that feels
                    just like you!
                </p>
            </div>
            <span class="text-xs text-gray-400">128 Items</span>
        </div>

        <!-- 3. FILTER & SORT BAR -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
            <div class="flex flex-wrap gap-2 text-xs">
                <button class="bg-black text-white px-4 py-2 rounded-md">All</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">Long Sleeve</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">Baby Tee</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">Short Sleeve</button>
            </div>

            <div class="flex items-center gap-6 text-xs text-gray-700 w-full sm:w-auto justify-between sm:justify-end">
                <button class="flex items-center gap-1.5 hover:text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
                <div class="flex items-center gap-1">
                    <span class="text-gray-400">Sort:</span>
                    <select class="bg-transparent font-medium cursor-pointer focus:outline-none">
                        <option>Newest</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 4. GRID KATALOG PRODUK INTERAKTIF -->

        @php
            // Array 30 Produk: Tiap produk disiapin 2 varian gambar (hitam & putih)
            $katalog = [
                [
                    'nama' => 'Crop Top Bang Windah Hitam',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'hitam.jpg', // Nama gambar baju hitam
                    'gambar_putih' => 'putih.jpg', // Nama gambar baju putih
                ],
                [
                    'nama' => 'Crop Top Bang Windah Putih',
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'hitam.jpg',
                    'gambar_putih' => 'putih.jpg',
                ],
            ];

            // Looping otomatis buat bikin sisa card sampai total 30
            for ($i = 3; $i <= 30; $i++) {
                $katalog[] = [
                    'nama' => 'Crop Top Bang Windah Vol ' . $i,
                    'kategori' => 'Crop Top',
                    'harga' => 'Rp90.000',
                    'gambar_hitam' => 'hitam.jpg',
                    'gambar_putih' => 'putih.jpg',
                ];
            }
        @endphp

        <!-- Grid Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
            @foreach ($katalog as $index => $item)
                <div class="group">

                    <!-- Box Gambar -->
                    <div class="aspect-square bg-gray-100 mb-2 overflow-hidden rounded-md relative cursor-pointer">
                        <!-- ID Unik tiap card pake index array: produk-img-0, produk-img-1, dst -->
                        <img id="produk-img-{{ $index }}" src="{{ asset('images/' . $item['gambar_hitam']) }}"
                            alt="{{ $item['nama'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>

                    <!-- Tombol Buletan Warna (Bisa Diklik!) -->
                    <div class="flex items-center gap-1.5 mb-2">
                        <!-- Klik Varian Hitam -->
                        <button type="button"
                            onclick="document.getElementById('produk-img-{{ $index }}').src='{{ asset('images/' . $item['gambar_hitam']) }}'"
                            class="w-4 h-4 rounded-full bg-black ring-1 ring-offset-1 ring-black hover:scale-110 transition cursor-pointer focus:outline-none"
                            title="Hitam">
                        </button>

                        <!-- Klik Varian Putih -->
                        <button type="button"
                            onclick="document.getElementById('produk-img-{{ $index }}').src='{{ asset('images/' . $item['gambar_putih']) }}'"
                            class="w-4 h-4 rounded-full bg-white border border-gray-300 hover:scale-110 transition cursor-pointer focus:outline-none"
                            title="Putih">
                        </button>
                    </div>

                    <!-- Teks Info Produk -->
                    <h3 class="text-xs md:text-sm font-medium text-gray-900 group-hover:underline cursor-pointer">
                        {{ $item['nama'] }}</h3>
                    <p class="text-[11px] text-gray-400">{{ $item['kategori'] }}</p>
                    <p class="text-xs font-semibold mt-1">{{ $item['harga'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- LOAD MORE BUTTON -->
        <div class="text-center space-y-3 pt-4 border-t border-gray-100">
            <p class="text-[11px] text-gray-400">Showing 30 of 128 items</p>
            <button
                class="border border-gray-400 text-xs px-8 py-2.5 rounded hover:bg-black hover:text-white transition">
                Load more
            </button>
        </div>

    </main>

</body>

</html>
