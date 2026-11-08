<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alamat Baru - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Header / Navbar Sederhana -->
    <header class="bg-white border-b border-gray-200 px-4 py-4 sm:px-8 flex justify-between items-center">
        <a href="{{ route('profile.addresses') }}"
            class="text-lg font-bold tracking-tight text-gray-900 hover:text-black">
            ASYART.MERCH
        </a>
        <a href="{{ route('profile.addresses') }}" class="text-sm font-medium text-gray-600 hover:text-black">
            &larr; Back to your addresses
        </a>
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Judul Halaman -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Addresses</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your delivery addresses for faster checkout and seamless
                shipping.</p>
        </div>

        <!-- Form Card Section (Tab navigasi di atas sudah dihapus bersih) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 sm:p-8 shadow-sm">
            <div class="mb-6 pb-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Add New Shipping Address</h2>
            </div>

            <!-- Form yang siap dicolok BE (Action ke route store & method POST) -->
            <form action="{{ route('profile.addresses.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nama Penerima -->
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Recipient
                        Name</label>
                    <input type="text" name="recipient_name" id="recipient_name" required
                        value="{{ old('recipient_name', auth()->user()->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                        placeholder="Contoh: Dawa Dzaki">
                    @error('recipient_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Label Alamat (Contoh: Rumah / Studio / Kantor) -->
                <div>
                    <label for="address_label" class="block text-sm font-medium text-gray-700 mb-1">Address Label /
                        Title</label>
                    <input type="text" name="address_label" id="address_label" required
                        value="{{ old('address_label') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                        placeholder="Contoh: Rumah, Studio, dll">
                    @error('address_label')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Detail Alamat / Jalan -->
                <div>
                    <label for="street_address" class="block text-sm font-medium text-gray-700 mb-1">Street Address &
                        Details</label>
                    <textarea name="street_address" id="street_address" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                        placeholder="Jl. Nama Jalan No. XX, Patokan, dll">{{ old('street_address') }}</textarea>
                    @error('street_address')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid untuk Kota, Provinsi, & Kode Pos -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City / Region</label>
                        <input type="text" name="city" id="city" required value="{{ old('city') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                            placeholder="Jakarta Utara">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal
                            Code</label>
                        <input type="text" name="postal_code" id="postal_code" required
                            value="{{ old('postal_code') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                            placeholder="14220">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" id="country" value="Indonesia" readonly
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-500 text-sm cursor-not-allowed">
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" required
                        value="{{ old('phone_number') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black text-sm"
                        placeholder="+62 8xx xxxx xxxx">
                </div>

                <!-- Checkbox Set as Default -->
                <div class="flex items-center pt-2">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                        class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                    <label for="is_default" class="ml-2 block text-sm text-gray-700">
                        Set as default shipping address
                    </label>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('profile.addresses') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800 transition">
                        Save Address
                    </button>
                </div>

            </form>
        </div>

    </main>

    <!-- Footer -->
    <footer class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-xs text-gray-500 border-t border-gray-200 mt-12">
        © 2026 ASYART.MERCH. All rights reserved.
    </footer>

</body>

</html>
