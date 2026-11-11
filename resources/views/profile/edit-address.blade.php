<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- NAVBAR ATAS -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-xl font-serif font-bold text-black tracking-widest uppercase">
            ASYART.MERCH
        </a>
        <a href="{{ route('profile.addresses') }}" class="text-xs text-gray-600 hover:text-black transition">
            &larr; Back to Addresses
        </a>
    </header>

    <!-- KONTEN UTAMA FULL SCREEN -->
    <main class="max-w-3xl w-full mx-auto px-4 py-10 my-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-10 space-y-6">

            <div class="pb-4 border-b border-gray-100 space-y-1">
                <h3 class="text-2xl font-serif font-medium text-gray-900">Edit Shipping Address</h3>
                <p class="text-xs text-gray-500">Perbarui informasi lokasi pengiriman pesanan lu di bawah ini.</p>
            </div>

            <!-- Form Edit Alamat Full Width -->
            <form action="{{ route('address.update', $address->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Recipient
                        Name</label>
                    <input type="text" name="recipient_name"
                        value="{{ old('recipient_name', $address->recipient_name ?? auth()->user()->name) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Address Label
                        (Contoh: Rumah / Studio)</label>
                    <input type="text" name="address_name" value="{{ old('address_name', $address->address_name) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                        required>
                    @error('address_name')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Street
                        Address</label>
                    <textarea name="full_address" rows="3"
                        class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                        required>{{ old('full_address', $address->full_address) }}</textarea>
                    @error('full_address')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">City /
                            Kabupaten</label>
                        <input type="text" name="city" value="{{ old('city', $address->city) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                            required>
                        @error('city')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Province</label>
                        <input type="text" name="province" value="{{ old('province', $address->province ?? '') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                            required>
                        @error('province')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Postal
                            Code</label>
                        <input type="text" name="postal_code"
                            value="{{ old('postal_code', $address->postal_code) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                            required>
                        @error('postal_code')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wider text-gray-700 mb-1">Phone
                            Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2.5 text-xs focus:bg-white focus:border-black focus:outline-none transition"
                            required>
                        @error('phone')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- TAMBAHAN CHECKBOX SET AS DEFAULT SHIPPING ADDRESS -->
                <div class="flex items-center pt-2">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                        class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                    <label for="is_default" class="ml-2 block text-xs text-gray-700">
                        Set as default shipping address
                    </label>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('profile.addresses') }}"
                        class="px-4 py-2.5 border border-gray-200 text-gray-600 text-xs font-medium rounded hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-black text-white text-xs font-medium uppercase tracking-wider rounded hover:bg-gray-800 transition active:scale-95 shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 text-center py-4 text-[10px] text-gray-400">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

</body>

</html>
