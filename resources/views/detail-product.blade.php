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

    <!-- 1. NAVBAR -->
    <nav class="border-b border-gray-200 px-4 md:px-12 py-4 relative z-40 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <a href="{{ route('home') }}"
                class="text-xl font-bold tracking-wider font-serif hover:opacity-80 transition inline-block">ASYART.MERCH</a>

            <div class="flex items-center gap-4">
                <a href="{{ route('cart.page') }}" class="relative hover:opacity-75 transition duration-150 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span id="cart-count-desktop"
                        class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                </a>
            </div>
        </div>
    </nav>

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
                    <div class="aspect-square bg-blue-100 rounded-lg overflow-hidden relative border border-gray-100">
                        <img id="main-product-img" src="{{ asset('images/' . $product['gambar_hitam']) }}"
                            alt="{{ $product['nama'] }}" class="w-full h-full object-cover transition duration-300">
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Tambah ID thumb-black & thumb-white biar sinkron -->
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
                            <!-- Tambah ID color-btn-black & color-btn-white -->
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

                    <!-- Pilih Ukuran (Size) -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-[10px] uppercase font-semibold">
                            <span>SELECT SIZE</span>
                            <a href="javascript:void(0)" class="text-gray-400 underline">Size Guide</a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="selectSize('XS', this)"
                                class="size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">XS</button>
                            <button type="button" onclick="selectSize('S', this)"
                                class="size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">S</button>
                            <button type="button" onclick="selectSize('M', this)"
                                class="size-btn border border-black bg-black text-white px-3.5 py-2 rounded text-xs font-medium transition">M</button>
                            <button type="button" onclick="selectSize('L', this)"
                                class="size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">L</button>
                            <button type="button" onclick="selectSize('XL', this)"
                                class="size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition">XL</button>
                        </div>
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
                            <button type="button" onclick="addToBag()"
                                class="flex-1 bg-black text-white py-3.5 rounded font-medium text-xs hover:bg-gray-800 active:scale-95 transition">
                                Add to Bag
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

                <!-- Container Ulasan Random -->
                <div id="dynamic-reviews-container" class="md:col-span-2 space-y-6">
                    <!-- Dirender via JavaScript secara random -->
                </div>
            </div>
        </div>

        <!-- SECTION 2: YOU MAY ALSO LIKE (ACAK DINAMIS) -->
        <div class="border-t border-gray-200 pt-12 space-y-6">
            <h2 class="text-xl md:text-2xl font-serif font-medium">You may also like</h2>

            <!-- Container Produk Rekomendasi Random -->
            <div id="dynamic-recommendations" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Dirender via JavaScript secara random -->
            </div>
        </div>
    </main>

    <!-- SCRIPT INTERAKTIF & RANDOM GENERATOR -->
    <script>
        let selectedColor = 'Black';
        let selectedSize = 'M';
        let quantity = 1;

        // Pool Data Ulasan Acak
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
            },
            {
                nama: "Reza Artamevia",
                waktu: "4 days ago",
                bintang: "★★★★★",
                teks: "Sumpah ini keren banget, pas banget dipakai nongkrong malem-malem."
            },
            {
                nama: "Ahmad Dhani",
                waktu: "6 days ago",
                bintang: "★★★★★",
                teks: "Kainnya lembut, sablon plastisol-nya kuat ga gampang pecah. Mantap!"
            }
        ];

        // Pool Data Produk Rekomendasi
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
            generateRandomContent();
        });

        // Fungsi Generate Acak untuk Ulasan & Rekomendasi (Anti Double)
        function generateRandomContent() {
            // 1. Acak Ulasan (Ambil 2 secara acak)
            const shuffledReviews = [...poolReviews].sort(() => 0.5 - Math.random()).slice(0, 2);
            const reviewContainer = document.getElementById('dynamic-reviews-container');
            if (reviewContainer) {
                reviewContainer.innerHTML = '';
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

            // 2. Acak Produk Rekomendasi (Ambil 4 secara acak)
            const currentProductId = {{ request()->route('id') ?? 0 }};
            const filteredProducts = poolProducts.filter(p => p.id !== currentProductId);
            const shuffledProducts = [...filteredProducts].sort(() => 0.5 - Math.random()).slice(0, 4);
            const recommendationContainer = document.getElementById('dynamic-recommendations');

            if (recommendationContainer) {
                recommendationContainer.innerHTML = '';
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

        // Sinkronisasi Slipped: Klik thumbnail kecil -> update gambar & ikut update pilihan warna aktif
        function changeImage(imgUrl, colorName, element) {
            document.getElementById('main-product-img').src = imgUrl;

            // Highlight thumbnail aktif
            const thumbBtns = document.querySelectorAll('.thumb-btn');
            thumbBtns.forEach(btn => {
                btn.classList.remove('border-2', 'border-black');
                btn.classList.add('border', 'border-gray-200', 'opacity-60');
            });
            if (element) {
                element.classList.add('border-2', 'border-black');
                element.classList.remove('border-gray-200', 'opacity-60');
            }

            // Sinkronkan ke tombol warna di kanan tanpa loop
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
            }
        }

        // Klik tombol warna di kanan -> update state warna & otomatis klik thumbnail yang sesuai
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

            // Panggil thumbnail yang sesuai biar gambarnya ikut ganti & ter-highlight
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
        }

        function selectSize(sizeName, element) {
            selectedSize = sizeName;
            const sizeBtns = document.querySelectorAll('.size-btn');
            sizeBtns.forEach(btn => {
                btn.className =
                    'size-btn border border-gray-200 px-3.5 py-2 rounded text-xs hover:border-black transition';
            });
            if (element) {
                element.className =
                    'size-btn border border-black bg-black text-white px-3.5 py-2 rounded text-xs font-medium transition';
            }
        }

        function changeQty(delta) {
            quantity = Math.max(1, quantity + delta);
            document.getElementById('qty-val').innerText = quantity;
        }

        function updateCartCountUI() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            const badge = document.getElementById('cart-count-desktop');
            if (badge) badge.innerText = totalItems;
        }

        function addToBag() {
            const productName = "{{ $product['nama'] }}";
            const productCat = "{{ $product['kategori'] }}";
            const productPrice = "{{ $product['harga'] }}";
            const productImg = selectedColor === 'Black' ? "{{ asset('images/' . $product['gambar_hitam']) }}" :
                "{{ asset('images/' . $product['gambar_putih']) }}";

            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            let existingItem = cart.find(item => item.nama === productName && item.warna === selectedColor && item
                .ukuran === selectedSize);

            if (existingItem) {
                existingItem.qty += quantity;
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
                    'fixed top-20 right-12 bg-white border border-gray-200 text-xs p-4 rounded-lg shadow-2xl z-50 w-80 transition-all duration-300 transform translate-y-[-10px] opacity-0';
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
