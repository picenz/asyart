@extends('layouts.admin', ['title' => 'Admin - Add Product'])

@section('content')
    <div x-data="{
        colors: ['White', 'Black'],
        newColor: '',
        sizes: ['S', 'M', 'L', 'XL', 'XXL'],
        newSize: '',
        addColor() {
            if (this.newColor.trim() !== '' && !this.colors.includes(this.newColor.trim())) {
                this.colors.push(this.newColor.trim());
                this.newColor = '';
            }
        },
        addSize() {
            if (this.newSize.trim() !== '' && !this.sizes.includes(this.newSize.trim().toUpperCase())) {
                this.sizes.push(this.newSize.trim().toUpperCase());
                this.newSize = '';
            }
        }
    }" class="max-w-7xl mx-auto pb-12">

        <!-- HEADER & ACTION BUTTONS -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <a href="{{ route('admin.products.index') }}" class="hover:underline">Products</a>
                    <span>></span>
                    <span class="text-gray-900 font-medium">Add product</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Add product</h1>
                <p class="text-xs text-gray-500 mt-0.5">Fill in the details, then save as draft or publish.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" form="addProductForm"
                    class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition shadow-sm">
                    Save product
                </button>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <form id="addProductForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <!-- KOLOM KIRI (2 SPAN) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Basic information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Product name</label>
                        <input type="text" name="name" placeholder="Insert Product Name"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" placeholder="Describe the fit, fabric and care instructions."
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"></textarea>
                    </div>
                </div>

                <!-- Images Upload -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Images</h2>

                    <div
                        class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:bg-gray-50/50 transition cursor-pointer relative">
                        <input type="file" name="images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs font-semibold text-gray-700">Drag images here or click to browse</p>
                            <p class="text-[11px] text-gray-400">JPG or PNG, up to 5 MB each. The first image is the cover.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Variants & Stock Management (COLOURS & SIZES INTERAKTIF) -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-gray-900">Variants</h2>

                    <!-- Colours Section -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Colours</label>
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <template x-for="(col, index) in colors" :key="index">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-black text-white rounded-lg text-xs font-medium">
                                    <span x-text="col"></span>
                                    <button type="button" @click="colors.splice(index, 1)"
                                        class="text-gray-400 hover:text-white font-bold ml-1">✕</button>
                                </div>
                            </template>

                            <!-- Input Tambah Warna -->
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

                    <!-- Sizes Section (DIBIKIN INTERAKTIF JUGA) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Sizes</label>
                        <div class="flex flex-wrap items-center gap-2 mb-6">
                            <template x-for="(size, index) in sizes" :key="index">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-black text-white rounded-lg text-xs font-bold tracking-wide">
                                    <span x-text="size"></span>
                                    <button type="button" @click="sizes.splice(index, 1)"
                                        class="text-gray-400 hover:text-white font-bold ml-1">✕</button>
                                </div>
                            </template>

                            <!-- Input Tambah Ukuran -->
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
                                    <tr
                                        class="bg-gray-50/80 border-b border-gray-200 text-gray-500 uppercase text-[11px] font-semibold">
                                        <th class="py-3 px-4 w-32">Colour</th>
                                        <template x-for="size in sizes" :key="size">
                                            <th class="py-3 px-3 text-center" x-text="size"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(color, cIdx) in colors" :key="cIdx">
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="py-3 px-4 font-semibold text-gray-900" x-text="color"></td>
                                            <template x-for="(size, sIdx) in sizes" :key="sIdx">
                                                <td class="py-3 px-3 text-center">
                                                    <input type="number"
                                                        :name="'variants[' + color + '][' + size + '][stock]'"
                                                        value="0" min="0"
                                                        class="w-16 px-2 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-center focus:outline-none focus:border-black transition">

                                                    <!-- Hidden inputs untuk backend -->
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
                        <p class="text-[11px] text-gray-400 mt-3">Variants with 0 stock are shown as "Sold out" in the
                            store.</p>
                    </div>

                </div>

            </div>

            <!-- KOLOM KANAN (1 SPAN) -->
            <div class="space-y-6">

                <!-- Status Card -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-gray-900">Status</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Visibility</label>
                        <div class="relative">
                            <select name="status"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black appearance-none">
                                <option value="Draft">Draft — hidden from store</option>
                                <option value="Active">Active — visible in store</option>
                            </select>
                            <span
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
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
                                <option value="">Select a category</option>
                                <option value="Long Sleeve">Long Sleeve</option>
                                <option value="Short Sleeve">Short Sleeve</option>
                                <option value="Baby tee">Baby tee</option>
                            </select>
                            <span
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
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
                        <input type="number" name="price" placeholder="0"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">SKU prefix</label>
                        <input type="text" name="sku" placeholder="e.g. FN-LRS"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                </div>

            </div>

        </form>
    </div>
@endsection
