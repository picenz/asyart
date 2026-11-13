@extends('layouts.admin', ['title' => 'Admin Products'])

@section('content')
    <div>
        <!-- Judul & Tombol Add Product -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Products</h1>
                <p class="text-sm text-gray-500 mt-0.5">12 products in your store.</p>
            </div>
            <div>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add product
                </a>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <form method="GET" action="{{ route('admin.products.index') }}"
            class="flex flex-col sm:flex-row items-center gap-3 mb-6">
            <div class="w-full sm:w-80 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search product name or SKU"
                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <!-- Filter Category -->
                <select name="category" onchange="this.form.submit()"
                    class="w-full sm:w-auto px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:border-black">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Category: All</option>
                    <option value="long_sleeve" {{ request('category') == 'long_sleeve' ? 'selected' : '' }}>Long Sleeve
                    </option>
                    <option value="short_sleeve" {{ request('category') == 'short_sleeve' ? 'selected' : '' }}>Short Sleeve
                    </option>
                    <option value="baby_tee" {{ request('category') == 'baby_tee' ? 'selected' : '' }}>Baby tee</option>
                </select>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()"
                    class="w-full sm:w-auto px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:outline-none focus:border-black">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Status: All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </form>

        <!-- TABEL UTAMA PRODUCTS -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-gray-100 text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3.5 px-4 font-medium">Product</th>
                            <th class="py-3.5 px-4 font-medium">Category</th>
                            <th class="py-3.5 px-4 font-medium">Price</th>
                            <th class="py-3.5 px-4 font-medium">Stock</th>
                            <th class="py-3.5 px-4 font-medium">Variants</th>
                            <th class="py-3.5 px-4 font-medium">Status</th>
                            <th class="py-3.5 px-4 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs text-gray-800">
                        @forelse($products ?? [] as $product)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs overflow-hidden border border-gray-200">
                                        @if (!empty($product->image))
                                            <img src="{{ str_starts_with($product->image, 'products/') ? asset('storage/' . $product->image) : asset($product->image) }}"
                                                alt="" class="w-full h-full object-cover">
                                        @else
                                            👕
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="font-medium text-gray-900 hover:underline">{{ $product->name }}</a>
                                        <p class="text-[11px] text-gray-500">SKU: {{ $product->sku }}</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">{{ $product->category }}</td>
                                <td class="py-3.5 px-4 font-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 font-medium">
                                    @if ($product->stock <= 0)
                                        <span class="text-red-600">Out of stock</span>
                                    @elseif($product->stock <= 3)
                                        <span class="text-amber-600">{{ $product->stock }} left</span>
                                    @else
                                        <span class="text-gray-900">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">
                                    {{ $product->variants_count ?? '2 colours · 5 sizes' }}</td>
                                <td class="py-3.5 px-4">
                                    @if ($product->status == 'Active')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                        </span>
                                    @endif
                                </td>

                                <!-- KOLOM ACTION DENGAN DROPDOWN & MODAL POP-UP HAPUS -->
                                <td class="py-3.5 px-4 text-right relative" x-data="{ openDropdown: false, showDeleteModal: false }">
                                    <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false"
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                        ···
                                    </button>

                                    <!-- Menu Dropdown Pop-up -->
                                    <div x-show="openDropdown" x-transition.origin.top.right style="display: none;"
                                        class="absolute right-4 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 text-left">

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit product
                                        </a>

                                        <!-- Tombol Hapus (Memicu Modal Pop-up) -->
                                        <button @click="openDropdown = false; showDeleteModal = true" type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition text-left">
                                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus product
                                        </button>
                                    </div>

                                    <!-- POPUP MODAL KONFIRMASI HAPUS -->
                                    <div x-show="showDeleteModal" style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
                                        x-transition.opacity>

                                        <div @click.outside="showDeleteModal = false"
                                            class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                                                ⚠️
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                                                <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus
                                                    produk <span
                                                        class="font-semibold text-gray-800">{{ $product->name }}</span>
                                                    dari sistem? Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="flex items-center gap-2 pt-2">
                                                <button @click="showDeleteModal = false" type="button"
                                                    class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                                                    Batal
                                                </button>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                    method="POST" class="w-1/2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition">
                                                        Ya, Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- DATA STATIS CADANGAN SESUAI FIGMA -->
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center font-bold text-xs text-blue-600 border border-gray-200">
                                        👕</div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', 1) }}"
                                            class="font-medium text-gray-900 hover:underline">Longsleeve
                                            Fish
                                            Dishes</a>
                                        <p class="text-[11px] text-gray-500">SKU: FN-TWB</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">Long Sleeve</td>
                                <td class="py-3.5 px-4 font-semibold">Rp90.000</td>
                                <td class="py-3.5 px-4 font-medium text-gray-900">24</td>
                                <td class="py-3.5 px-4 text-gray-600">2 colours · 5 sizes</td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right relative" x-data="{ openDropdown: false, showDeleteModal: false }">
                                    <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false"
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                        ···
                                    </button>
                                    <div x-show="openDropdown" x-transition.origin.top.right style="display: none;"
                                        class="absolute right-4 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 text-left">
                                        <a href="{{ route('admin.products.edit', 1) }}"
                                            class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">Edit
                                            product</a>
                                        <button @click="openDropdown = false; showDeleteModal = true" type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition text-left">Hapus
                                            product</button>
                                    </div>
                                    <!-- Modal Statis 1 -->
                                    <div x-show="showDeleteModal" style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
                                        <div @click.outside="showDeleteModal = false"
                                            class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                                                ⚠️</div>
                                            <div>
                                                <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                                                <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus
                                                    produk Longsleeve Fish Dishes?</p>
                                            </div>
                                            <div class="flex items-center gap-2 pt-2">
                                                <button @click="showDeleteModal = false" type="button"
                                                    class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Batal</button>
                                                <form action="{{ route('admin.products.destroy', 1) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition">Ya,
                                                        Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center font-bold text-xs text-pink-600 border border-gray-200">
                                        👕</div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', 2) }}"
                                            class="font-medium text-gray-900 hover:underline">Pinky Fish
                                            Baby
                                            Tee</a>
                                        <p class="text-[11px] text-gray-500">SKU: FN-BFB</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">Baby tee</td>
                                <td class="py-3.5 px-4 font-semibold">Rp70.000</td>
                                <td class="py-3.5 px-4 font-medium text-gray-900">41</td>
                                <td class="py-3.5 px-4 text-gray-600">2 colours · 5 sizes</td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right relative" x-data="{ openDropdown: false, showDeleteModal: false }">
                                    <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false"
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                        ···
                                    </button>
                                    <div x-show="openDropdown" x-transition.origin.top.right style="display: none;"
                                        class="absolute right-4 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 text-left">
                                        <a href="{{ route('admin.products.edit', 2) }}"
                                            class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">Edit
                                            product</a>
                                        <button @click="openDropdown = false; showDeleteModal = true" type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition text-left">Hapus
                                            product</button>
                                    </div>
                                    <!-- Modal Statis 2 -->
                                    <div x-show="showDeleteModal" style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
                                        <div @click.outside="showDeleteModal = false"
                                            class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                                                ⚠️</div>
                                            <div>
                                                <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                                                <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus
                                                    produk Pinky Fish Baby tee?</p>
                                            </div>
                                            <div class="flex items-center gap-2 pt-2">
                                                <button @click="showDeleteModal = false" type="button"
                                                    class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Batal</button>
                                                <form action="{{ route('admin.products.destroy', 2) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition">Ya,
                                                        Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center font-bold text-xs text-amber-600 border border-gray-200">
                                        👕</div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', 3) }}"
                                            class="font-medium text-gray-900 hover:underline">Longsleeve
                                            Gold Fish</a>
                                        <p class="text-[11px] text-gray-500">SKU: FN-AMD</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">Long Sleeve</td>
                                <td class="py-3.5 px-4 font-semibold">Rp90.000</td>
                                <td class="py-3.5 px-4 font-medium text-amber-600">6 left</td>
                                <td class="py-3.5 px-4 text-gray-600">2 colours · 5 sizes</td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right relative" x-data="{ openDropdown: false, showDeleteModal: false }">
                                    <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false"
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                        ···
                                    </button>
                                    <div x-show="openDropdown" x-transition.origin.top.right style="display: none;"
                                        class="absolute right-4 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 text-left">
                                        <a href="{{ route('admin.products.edit', 3) }}"
                                            class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">Edit
                                            product</a>
                                        <button @click="openDropdown = false; showDeleteModal = true" type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition text-left">Hapus
                                            product</button>
                                    </div>
                                    <!-- Modal Statis 3 -->
                                    <div x-show="showDeleteModal" style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
                                        <div @click.outside="showDeleteModal = false"
                                            class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                                                ⚠️</div>
                                            <div>
                                                <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                                                <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus
                                                    produk Longsleeve Gold Fish?</p>
                                            </div>
                                            <div class="flex items-center gap-2 pt-2">
                                                <button @click="showDeleteModal = false" type="button"
                                                    class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Batal</button>
                                                <form action="{{ route('admin.products.destroy', 3) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition">Ya,
                                                        Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center font-bold text-xs text-orange-600 border border-gray-200">
                                        👕</div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', 4) }}"
                                            class="font-medium text-gray-900 hover:underline">Tshirt MasKoki
                                            Baby tee</a>
                                        <p class="text-[11px] text-gray-500">SKU: FN-BKC</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">Baby tee</td>
                                <td class="py-3.5 px-4 font-semibold">Rp70.000</td>
                                <td class="py-3.5 px-4 font-medium text-red-600">Out of stock</td>
                                <td class="py-3.5 px-4 text-gray-600">2 colours · 5 sizes</td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right relative" x-data="{ openDropdown: false, showDeleteModal: false }">
                                    <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false"
                                        class="p-1.5 border border-gray-200 rounded-md hover:bg-gray-100 text-gray-500 transition inline-block">
                                        ···
                                    </button>
                                    <div x-show="openDropdown" x-transition.origin.top.right style="display: none;"
                                        class="absolute right-4 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 text-left">
                                        <a href="{{ route('admin.products.edit', 4) }}"
                                            class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">Edit
                                            product</a>
                                        <button @click="openDropdown = false; showDeleteModal = true" type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition text-left">Hapus
                                            product</button>
                                    </div>
                                    <!-- Modal Statis 4 -->
                                    <div x-show="showDeleteModal" style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
                                        <div @click.outside="showDeleteModal = false"
                                            class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                                                ⚠️</div>
                                            <div>
                                                <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                                                <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus
                                                    produk Tshirt MasKoki Baby tee?</p>
                                            </div>
                                            <div class="flex items-center gap-2 pt-2">
                                                <button @click="showDeleteModal = false" type="button"
                                                    class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Batal</button>
                                                <form action="{{ route('admin.products.destroy', 4) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition">Ya,
                                                        Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINATION -->
            <div
                class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                <p class="text-xs text-gray-500">Showing 1–8 of 12 products</p>
                <div class="flex items-center gap-1">
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-400 bg-gray-50 cursor-not-allowed"
                        disabled>&lt;</button>
                    <button class="px-3 py-1.5 bg-black text-white rounded-lg text-xs font-semibold">1</button>
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">2</button>
                    <button
                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">&gt;</button>
                </div>
            </div>
        </div>
    </div>
@endsection
