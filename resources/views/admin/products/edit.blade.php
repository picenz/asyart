
@extends('layouts.admin', ['title' => 'Admin - Edit Product'])

@section('content')
    @php
        $colors = $product->variants->pluck('color')->unique()->values();
        $sizes = $product->variants->pluck('size')->unique()->values();

        $stocks = $product->variants
            ->groupBy('color')
            ->map(fn ($variants) => $variants->pluck('stock', 'size'));

        $firstVariant = $product->variants->first();
    @endphp

    <div x-data="{
        colors: @js($colors),
        newColor: '',
        sizes: @js($sizes),
        newSize: '',
        stocks: @js($stocks),
        showDeleteModal: false,

        addColor() {
            const color = this.newColor.trim();

            if (color !== '' && !this.colors.includes(color)) {
                this.colors.push(color);
                this.stocks[color] = {};
                this.newColor = '';
            }
        },

        addSize() {
            const size = this.newSize.trim().toUpperCase();

            if (size !== '' && !this.sizes.includes(size)) {
                this.sizes.push(size);
                this.newSize = '';
            }
        },

        removeColor(index) {
            const color = this.colors[index];
            this.colors.splice(index, 1);
            delete this.stocks[color];
        }
    }" class="max-w-7xl mx-auto pb-12">

        <!-- HEADER & ACTION BUTTONS -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <a href="{{ route('admin.products.index') }}" class="hover:underline">Products</a>
                    <span>></span>
                    <span class="text-gray-900 font-medium">Edit product</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Edit product</h1>
                <p class="text-xs text-gray-500 mt-0.5">Update product details, variants, and stock quantity.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" form="editProductForm"
                    class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition shadow-sm">
                    Update product
                </button>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <form id="editProductForm"
            action="{{ route('admin.products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Basic information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Product name</label>
                        <input type="text" name="name"
                            value="{{ old('name', $product->name) }}"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Images Upload -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Images</h2>

                    @if ($product->images->isNotEmpty())
                        <div class="flex flex-wrap gap-3">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $image->alt_text ?? $product->name }}"
                                    class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            @endforeach
                        </div>
                    @endif

                    <div
                        class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:bg-gray-50/50 transition cursor-pointer relative">
                        <input type="file" name="images[]" multiple accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs font-semibold text-gray-700">Drag images here or click to browse</p>
                            <p class="text-[11px] text-gray-400">JPG or PNG, up to 5 MB each. Existing images will be kept.</p>
                        </div>
                    </div>
                </div>

                <!-- Variants & Stock Management -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Variants</h2>

                    <!-- Colours Section -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Colours</label>
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <template x-for="(col, index) in colors" :key="col">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-black text-white rounded-lg text-xs font-medium">
                                    <span x-text="col"></span>
                                    <button type="button" @click="removeColor(index)"
                                        class="text-gray-400 hover:text-white font-bold ml-1">×</button>
                                </div>
                            </template>

                            <div class="inline-flex items-center gap-1.5">
                                <input type="text" x-model="newColor" @keydown.enter.prevent="addColor()"
                                    placeholder="Nama warna..."
                                    class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-xs focus:outline-none focus:border-black w-32">
                                <button type="button" @click="addColor()"
                                    class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:border-black hover:bg-gray-50 transition">
                                    + Add colour
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sizes Section -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Sizes</label>
                        <div class="flex flex-wrap items-center gap-2 mb-6">
                            <template x-for="(size, index) in sizes" :key="size">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-black text-white rounded-lg text-xs font-bold tracking-wide">
                                    <span x-text="size"></span>
                                    <button type="button" @click="sizes.splice(index, 1)"
                                        class="text-gray-400 hover:text-white font-bold ml-1">×</button>
                                </div>
                            </template>

                            <div class="inline-flex items-center gap-1.5">
                                <input type="text" x-model="newSize" @keydown.enter.prevent="addSize()"
                                    placeholder="Size..."
                                    class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-xs focus:outline-none focus:border-black w-24 uppercase">
                                <button type="button" @click="addSize()"
                                    class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:border-black hover:bg-gray-50 transition">
                                    + Add size
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Per Variant Matrix Table -->
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-gray-900">Stock per variant</label>
                            <span class="text-xs text-gray-500 font-medium">Total Stok Dinamis</span>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-500 uppercase text-[11px] font-semibold">
                                        <th class="py-3 px-4 w-32">Colour</th>
                                        <template x-for="size in sizes" :key="size">
                                            <th class="py-3 px-3 text-center" x-text="size"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(color, cIdx) in colors" :key="color">
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="py-3 px-4 font-semibold text-gray-900" x-text="color"></td>
                                            <template x-for="(size, sIdx) in sizes" :key="size">
                                                <td class="py-3 px-3 text-center">
                                                    <input type="number"
                                                        :name="'variants[' + color + '][' + size + '][stock]'"
                                                        :value="stocks[color]?.[size] ?? 0"
                                                        @input="(stocks[color] ??= {})[size] = $event.target.value"
                                                        min="0"
                                                        class="w-16 px-2 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-center focus:outline-none focus:border-black transition">

                                                    <input type="hidden"
                                                        :name="'variants[' + color + '][' + size + '][color]'"
                                                        :value="color">

                                                    <input type="hidden"
                                                        :name="'variants[' + color + '][' + size + '][size]'"
                                                        :value="size">
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-3">Variants with 0 stock are shown as "Sold out" in the store.</p>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="space-y-6">

                <!-- Status Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Status</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Visibility</label>
                        <div class="relative">
                            <select name="status"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black appearance-none">
                                <option value="Active" @selected(old('status', $product->is_active ? 'Active' : 'Draft') === 'Active')>Active — visible in store</option>
                                <option value="Draft" @selected(old('status', $product->is_active ? 'Active' : 'Draft') === 'Draft')>Draft — hidden from store</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Organization Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Organization</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                        <div class="relative">
                            <select name="category"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black appearance-none">
                                <option value="Long Sleeve" @selected(old('category', $product->category) === 'Long Sleeve')>Long Sleeve</option>
                                <option value="Short Sleeve" @selected(old('category', $product->category) === 'Short Sleeve')>Short Sleeve</option>
                                <option value="Baby tee" @selected(old('category', $product->category) === 'Baby tee')>Baby tee</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pricing Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Pricing</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Price (Rp)</label>
                        <input type="number" name="price"
                            value="{{ old('price', $firstVariant?->price ?? 0) }}"
                            min="0"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">SKU prefix</label>
                        <input type="text" name="sku"
                            value="{{ old('sku', $firstVariant?->sku ?? '') }}"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <button @click="showDeleteModal = true" type="button"
                            class="w-full py-2 px-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-semibold transition flex items-center justify-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Item Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- POPUP MODAL KONFIRMASI HAPUS -->
        <div x-show="showDeleteModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
            x-transition.opacity>

            <div @click.outside="showDeleteModal = false"
                class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl space-y-4 text-center">

                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-lg font-bold">
                    ⚠
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus item ini dari sistem? Tindakan ini tidak dapat dibatalkan.
                    </p>
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
    </div>
@endsection
