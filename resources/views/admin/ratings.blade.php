@extends('layouts.admin', ['title' => 'Ratings'])

@php
    // =========================================================================
    // KONTRAK DATA PER ITEM / PRODUK (Semua 12 item dari katalog ASYART.MERCH)
    // Siap diganti data dari Controller / BE dengan struktur yang sama:
    // - id, code, name, category, average_rating, total_reviews, breakdown (5..1 => %), reviews
    // Tiap review di reviews berisi:
    // - id, customer_name, customer_email, rating, comment, status, created_at
    // - Detail item pesanan pembeli per komen:
    //   order_number, color, size, qty, variant_note (opsional)
    // =========================================================================
    $isLoading = $isLoading ?? false;

    $items = $items ?? [
        [
            'id' => 0,
            'code' => '#0',
            'name' => 'Baby Tee Bang Windah',
            'category' => 'Baby Tee',
            'average_rating' => 4.9,
            'total_reviews' => 128,
            'breakdown' => [5 => 82, 4 => 12, 3 => 4, 2 => 1, 1 => 1],
            'reviews' => [
                [
                    'id' => 1,
                    'customer_name' => 'Gilang Ramadhan',
                    'customer_email' => 'gilang@example.com',
                    'rating' => 5,
                    'comment' =>
                        'Bahannya enak dipakai, nggak gerah, dan sablonannya rapi. Worth it banget buat harian.',
                    'status' => 'Published',
                    'created_at' => '2 minggu lalu',
                    'order_number' => 'RN-84921',
                    'color' => 'Putih',
                    'size' => 'M',
                    'qty' => 2,
                    'variant_note' => 'Reguler Fit',
                ],
                [
                    'id' => 2,
                    'customer_name' => 'Dinda Kirana',
                    'customer_email' => 'dinda@example.com',
                    'rating' => 4,
                    'comment' => 'Cutting-nya cakep dan pas di badan. Pengiriman juga aman sampai tujuan.',
                    'status' => 'Published',
                    'created_at' => '1 minggu lalu',
                    'order_number' => 'RN-85104',
                    'color' => 'Hitam',
                    'size' => 'S',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 1,
            'code' => '#1',
            'name' => 'Short Sleeve Bocil Bang Windah',
            'category' => 'Short Sleeve',
            'average_rating' => 4.8,
            'total_reviews' => 54,
            'breakdown' => [5 => 79, 4 => 14, 3 => 4, 2 => 2, 1 => 1],
            'reviews' => [
                [
                    'id' => 3,
                    'customer_name' => 'Raka Pratama',
                    'customer_email' => 'raka@example.com',
                    'rating' => 5,
                    'comment' => 'Desainnya unik banget, bahan adem buat dipakai keluar seharian.',
                    'status' => 'Published',
                    'created_at' => '6 hari lalu',
                    'order_number' => 'RN-85219',
                    'color' => 'Hitam',
                    'size' => 'L',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 2,
            'code' => '#2',
            'name' => 'Longsleeve Bang Windah',
            'category' => 'Long Sleeve',
            'average_rating' => 4.9,
            'total_reviews' => 47,
            'breakdown' => [5 => 85, 4 => 10, 3 => 3, 2 => 1, 1 => 1],
            'reviews' => [
                [
                    'id' => 4,
                    'customer_name' => 'Nadia Putri',
                    'customer_email' => 'nadia@example.com',
                    'rating' => 5,
                    'comment' => 'Lengan panjangnya pas, sablon tajam, dan nggak luntur pas dicuci.',
                    'status' => 'Published',
                    'created_at' => '5 hari lalu',
                    'order_number' => 'RN-85388',
                    'color' => 'Abu Misty',
                    'size' => 'M',
                    'qty' => 2,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 3,
            'code' => '#3',
            'name' => 'Baby Tee Snoopy',
            'category' => 'Baby Tee',
            'average_rating' => 4.8,
            'total_reviews' => 61,
            'breakdown' => [5 => 80, 4 => 13, 3 => 4, 2 => 2, 1 => 1],
            'reviews' => [
                [
                    'id' => 5,
                    'customer_name' => 'Alya Zahra',
                    'customer_email' => 'alya@example.com',
                    'rating' => 5,
                    'comment' => 'Gemes banget print Snoopy-nya, bahannya lembut dan nyaman.',
                    'status' => 'Published',
                    'created_at' => '4 hari lalu',
                    'order_number' => 'RN-85402',
                    'color' => 'Putih',
                    'size' => 'S',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 4,
            'code' => '#4',
            'name' => 'Baby Tee Radio Head',
            'category' => 'Baby Tee',
            'average_rating' => 4.7,
            'total_reviews' => 38,
            'breakdown' => [5 => 74, 4 => 16, 3 => 6, 2 => 2, 1 => 2],
            'reviews' => [
                [
                    'id' => 6,
                    'customer_name' => 'Dimas Saputra',
                    'customer_email' => 'dimas@example.com',
                    'rating' => 4,
                    'comment' => 'Vibe grafisnya dapet banget, fit-nya oke buat dipaduin sama outer.',
                    'status' => 'Published',
                    'created_at' => '3 hari lalu',
                    'order_number' => 'RN-85510',
                    'color' => 'Hitam',
                    'size' => 'XL',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 5,
            'code' => '#5',
            'name' => 'Baby Tee Cake',
            'category' => 'Baby Tee',
            'average_rating' => 4.9,
            'total_reviews' => 44,
            'breakdown' => [5 => 84, 4 => 11, 3 => 3, 2 => 1, 1 => 1],
            'reviews' => [
                [
                    'id' => 7,
                    'customer_name' => 'Salsa Rahma',
                    'customer_email' => 'salsa@example.com',
                    'rating' => 5,
                    'comment' => 'Lucu banget detail cake-nya, bahannya juga nggak nerawang.',
                    'status' => 'Published',
                    'created_at' => '2 hari lalu',
                    'order_number' => 'RN-85630',
                    'color' => 'Cream',
                    'size' => 'M',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 6,
            'code' => '#6',
            'name' => 'Baby Tee Blue Star Fish',
            'category' => 'Baby Tee',
            'average_rating' => 4.8,
            'total_reviews' => 29,
            'breakdown' => [5 => 78, 4 => 15, 3 => 5, 2 => 1, 1 => 1],
            'reviews' => [
                [
                    'id' => 8,
                    'customer_name' => 'Farah Nabila',
                    'customer_email' => 'farah@example.com',
                    'rating' => 5,
                    'comment' => 'Warna birunya cakep, bahan adem dan gampang di-mix and match.',
                    'status' => 'Published',
                    'created_at' => '2 hari lalu',
                    'order_number' => 'RN-85712',
                    'color' => 'Biru',
                    'size' => 'S',
                    'qty' => 2,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 7,
            'code' => '#7',
            'name' => 'Short Sleeve Circle Fish',
            'category' => 'Short Sleeve',
            'average_rating' => 4.7,
            'total_reviews' => 33,
            'breakdown' => [5 => 75, 4 => 15, 3 => 6, 2 => 2, 1 => 2],
            'reviews' => [
                [
                    'id' => 9,
                    'customer_name' => 'Bima Nugraha',
                    'customer_email' => 'bima@example.com',
                    'rating' => 4,
                    'comment' => 'Potongannya santai, enak dipakai buat aktivitas harian.',
                    'status' => 'Published',
                    'created_at' => '1 hari lalu',
                    'order_number' => 'RN-85801',
                    'color' => 'Hitam',
                    'size' => 'L',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 8,
            'code' => '#8',
            'name' => 'Longsleeve Puffer Fish',
            'category' => 'Long Sleeve',
            'average_rating' => 4.8,
            'total_reviews' => 27,
            'breakdown' => [5 => 80, 4 => 12, 3 => 5, 2 => 2, 1 => 1],
            'reviews' => [
                [
                    'id' => 10,
                    'customer_name' => 'Tari Kusuma',
                    'customer_email' => 'tari@example.com',
                    'rating' => 5,
                    'comment' => 'Grafis puffer fish-nya nyentrik tapi tetap clean, bahan nyaman.',
                    'status' => 'Published',
                    'created_at' => '1 hari lalu',
                    'order_number' => 'RN-85895',
                    'color' => 'Navy',
                    'size' => 'M',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 9,
            'code' => '#9',
            'name' => 'Baby Tee Snoopy & Bang Windah',
            'category' => 'Baby Tee',
            'average_rating' => 4.9,
            'total_reviews' => 52,
            'breakdown' => [5 => 83, 4 => 12, 3 => 3, 2 => 1, 1 => 1],
            'reviews' => [
                [
                    'id' => 11,
                    'customer_name' => 'Naufal Hakim',
                    'customer_email' => 'naufal@example.com',
                    'rating' => 5,
                    'comment' => 'Kolab konsepnya kocak dan pas, kualitas kainnya mantap.',
                    'status' => 'Published',
                    'created_at' => '1 hari lalu',
                    'order_number' => 'RN-85940',
                    'color' => 'Putih',
                    'size' => 'L',
                    'qty' => 3,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 10,
            'code' => '#10',
            'name' => 'Short Sleeve Bocil Sigma',
            'category' => 'Short Sleeve',
            'average_rating' => 4.7,
            'total_reviews' => 41,
            'breakdown' => [5 => 76, 4 => 14, 3 => 6, 2 => 2, 1 => 2],
            'reviews' => [
                [
                    'id' => 12,
                    'customer_name' => 'Yoga Pratama',
                    'customer_email' => 'yoga@example.com',
                    'rating' => 5,
                    'comment' => 'Sablon jelas, bahan nggak gampang kusut, harga sesuai kualitas.',
                    'status' => 'Published',
                    'created_at' => 'Baru saja',
                    'order_number' => 'RN-86011',
                    'color' => 'Hitam',
                    'size' => 'M',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
        [
            'id' => 11,
            'code' => '#11',
            'name' => 'Longsleeve Orange Fish',
            'category' => 'Long Sleeve',
            'average_rating' => 4.8,
            'total_reviews' => 24,
            'breakdown' => [5 => 79, 4 => 14, 3 => 4, 2 => 2, 1 => 1],
            'reviews' => [
                [
                    'id' => 13,
                    'customer_name' => 'Keisha Putri',
                    'customer_email' => 'keisha@example.com',
                    'rating' => 5,
                    'comment' => 'Aksen warna oranye-nya seger banget, nyaman dipakai seharian.',
                    'status' => 'Published',
                    'created_at' => 'Baru saja',
                    'order_number' => 'RN-86088',
                    'color' => 'Oranye',
                    'size' => 'S',
                    'qty' => 1,
                    'variant_note' => null,
                ],
            ],
        ],
    ];

    $defaultItemId = $selectedItemId ?? ($items[0]['id'] ?? 0);
@endphp

@section('content')
    <div class="space-y-6" x-data="{
        items: @js($items),
        selectedItemId: @js($defaultItemId),
        selectedStar: 'all',
        searchQuery: '',
        get currentItem() {
            const found = this.items.find(item => Number(item.id) === Number(this.selectedItemId));
            return found || this.items[0] || {
                id: null,
                code: '-',
                name: 'Belum ada item',
                category: '-',
                average_rating: 0,
                total_reviews: 0,
                breakdown: { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 },
                reviews: []
            };
        },
        get filteredReviews() {
            const list = Array.isArray(this.currentItem.reviews) ? this.currentItem.reviews : [];
            const q = this.searchQuery.trim().toLowerCase();
    
            return list.filter(rev => {
                const starOk = this.selectedStar === 'all' || Number(this.selectedStar) === Number(rev.rating ?? 0);
                if (!starOk) return false;
                if (!q) return true;
    
                const hay = `${rev.customer_name ?? ''} ${rev.customer_email ?? ''} ${rev.comment ?? ''} ${rev.order_number ?? ''} ${rev.color ?? ''} ${rev.size ?? ''} ${rev.qty ?? ''} ${rev.variant_note ?? ''}`.toLowerCase();
                return hay.includes(q);
            });
        }
    }">
        <!-- Header Halaman + Selector Per Item -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Ratings</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pantau rating dan ulasan pembeli per item produk sesuai alur ulasan user.
                </p>
            </div>

            <div class="w-full md:w-80">
                <label for="admin-rating-item-select"
                    class="block text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">
                    Pilih Item / Produk
                </label>
                <select id="admin-rating-item-select" x-model="selectedItemId" @change="selectedStar = 'all'"
                    class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-900 focus:outline-none focus:border-black transition">
                    <template x-for="item in items" :key="item.id">
                        <option :value="item.id" x-text="`${item.name} (${item.code ?? '#' + item.id})`"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- Ringkasan Rating Khusus Item Terpilih -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Skor Rata-rata Item -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Rata-rata Rating Item</p>
                    <span class="px-2 py-0.5 text-[11px] font-semibold bg-gray-100 text-gray-700 rounded"
                        x-text="currentItem.code ?? ('#' + currentItem.id)"></span>
                </div>
                <div class="mt-3 flex items-end gap-3">
                    <span class="text-4xl font-bold text-gray-900"
                        x-text="Number(currentItem.average_rating ?? 0).toFixed(1)"></span>
                    <span class="text-sm text-gray-500 pb-1">/ 5.0</span>
                </div>
                <p class="text-xs text-gray-500 mt-3 truncate"
                    x-text="`${currentItem.name} • ${currentItem.category ?? ''}`"></p>
            </div>

            <!-- Total Ulasan Item -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Ulasan Item Ini</p>
                <div class="mt-3">
                    <span class="text-4xl font-bold text-gray-900"
                        x-text="Number(currentItem.total_reviews ?? (currentItem.reviews?.length ?? 0))"></span>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    Khusus ulasan yang masuk untuk item terpilih
                </p>
            </div>

            <!-- Distribusi Bintang Item -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-2">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400 mb-3">Distribusi Bintang Item</p>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-10 font-medium text-gray-700">{{ $star }} ★</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-black rounded-full transition-all duration-200"
                                :style="`width: ${Math.max(0, Math.min(100, Number(currentItem.breakdown?.[{{ $star }}] ?? 0)))}%`">
                            </div>
                        </div>
                        <span class="w-9 text-right text-gray-500"
                            x-text="`${Number(currentItem.breakdown?.[{{ $star }}] ?? 0)}%`"></span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filter Bintang & Pencarian Ulasan pada Item Terpilih -->
        <div
            class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="selectedStar = 'all'"
                    :class="selectedStar === 'all' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition">
                    Semua
                </button>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <button type="button" @click="selectedStar = '{{ $star }}'"
                        :class="selectedStar === '{{ $star }}' ? 'bg-black text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition">
                        {{ $star }} ★
                    </button>
                @endforeach
            </div>

            <div class="w-full sm:w-72">
                <input type="text" name="search" x-model="searchQuery"
                    placeholder="Cari nama pembeli, varian, atau ulasan..."
                    class="w-full px-3.5 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black focus:bg-white transition">
            </div>
        </div>

        <!-- Tabel Daftar Ulasan Item Terpilih -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-gray-200 bg-gray-50 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Pembeli</th>
                            <th class="py-3 px-4">Item Pesanan</th>
                            <th class="py-3 px-4">Rating</th>
                            <th class="py-3 px-4">Ulasan</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @if ($isLoading)
                            @for ($i = 0; $i < 3; $i++)
                                <tr class="animate-pulse">
                                    <td class="py-4 px-4">
                                        <div class="h-4 w-28 bg-gray-200 rounded"></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="h-4 w-32 bg-gray-200 rounded"></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="h-5 w-12 bg-gray-200 rounded"></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="h-4 w-56 bg-gray-200 rounded"></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="h-4 w-20 bg-gray-200 rounded"></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="h-5 w-20 bg-gray-200 rounded-full"></div>
                                    </td>
                                </tr>
                            @endfor
                        @else
                            <template x-for="review in filteredReviews"
                                :key="review.id ?? `${review.customer_name}-${review.created_at}`">
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="py-3.5 px-4 align-top">
                                        <div class="font-medium text-gray-900" x-text="review.customer_name ?? '-'"></div>
                                        <div class="text-xs text-gray-400" x-show="review.customer_email"
                                            x-text="review.customer_email"></div>
                                    </td>
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-medium"
                                                x-text="`Warna: ${review.color ?? '-'}`"></span>
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-medium"
                                                x-text="`Ukuran: ${review.size ?? '-'}`"></span>
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-medium"
                                                x-text="`Qty: ${Number(review.qty ?? 1)}`"></span>
                                        </div>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-gray-400">
                                            <span x-show="review.order_number"
                                                x-text="`Order #${review.order_number}`"></span>
                                            <span x-show="review.variant_note" x-text="`• ${review.variant_note}`"></span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-900"
                                            x-text="`${Number(review.rating ?? 0)} ★`"></span>
                                    </td>
                                    <td class="py-3.5 px-4 align-top text-gray-600 max-w-md"
                                        x-text="review.comment ?? ''">
                                    </td>
                                    <td class="py-3.5 px-4 align-top text-xs text-gray-500 whitespace-nowrap"
                                        x-text="review.created_at ?? '-'"></td>
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700"
                                            x-text="review.status ?? 'Published'"></span>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="filteredReviews.length === 0">
                                <td colspan="6" class="py-10 px-4 text-center text-sm text-gray-400">
                                    Belum ada ulasan untuk item atau filter yang dipilih.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
