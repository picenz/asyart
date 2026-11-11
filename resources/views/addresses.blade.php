<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Addresses - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Container Utama Split Screen -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- 1. BAGIAN KIRI: BANNER GAMBAR & MENU NAVIGASI AKUN -->
        <div class="w-full lg:w-1/2 h-auto relative bg-cover bg-center p-6 lg:p-12 flex flex-col justify-between shrink-0 lg:sticky lg:top-0 lg:max-h-screen"
            style="background-image: url('{{ asset('images/login.jpg') }}');">

            <div class="absolute inset-0 bg-black/40"></div>

            <!-- Header / Logo Top Left -->
            <div class="relative z-10 flex items-center justify-between">
                <a href="{{ url('/') }}"
                    class="text-xl lg:text-2xl font-serif font-bold text-white tracking-widest uppercase hover:opacity-80 transition">
                    ASYART.MERCH
                </a>
                <a href="{{ url('/') }}"
                    class="text-xs text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded backdrop-blur-sm transition">
                    ← Back to Shop
                </a>
            </div>

            <!-- Teks & Menu Navigasi -->
            <div class="relative z-10 text-white max-w-lg space-y-6 my-auto lg:my-12">
                <div class="space-y-2">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif italic leading-tight">
                        Addresses
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                        Manage your delivery addresses for faster checkout and seamless shipping.
                    </p>
                </div>

                <!-- Menu Navigasi Akun Lengkap -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 space-y-2 text-xs">
                    <a href="{{ route('profile.edit') }}"
                        class="block text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded transition">
                        Account details
                    </a>
                    <a href="{{ route('profile.orders') }}"
                        class="block text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded transition">
                        Orders
                    </a>
                    <a href="{{ route('profile.addresses') }}"
                        class="block font-semibold text-white bg-white/20 px-3 py-2 rounded transition">
                        ✓ Addresses
                    </a>
                    <div class="pt-2 border-t border-white/20">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left text-red-300 hover:text-red-200 px-3 py-2 rounded transition">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer kecil di banner -->
            <div class="relative z-10 text-[10px] text-gray-300">
                &copy; 2026 ASYART.MERCH. All rights reserved.
            </div>
        </div>

        <!-- 2. BAGIAN KANAN: DAFTAR ALAMAT TERSIMPAN -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 md:p-12 bg-white overflow-y-auto">
            <div class="w-full max-w-xl mx-auto space-y-8">

                <!-- Header & Tombol Tambah Alamat -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-serif font-medium">Addresses</h3>
                        <p class="text-xs text-gray-500">Manage your saved shipping locations</p>
                    </div>
                    <a href="{{ route('profile.addresses.create') }}"
                        class="text-xs font-medium bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition active:scale-95 shadow-sm inline-block text-center">
                        Add new address
                    </a>
                </div>

                <!-- LIST ALAMAT TERSIMPAN (LOOPING DATABASE) -->
                <div class="space-y-4">
                    @forelse($addresses as $address)
                        <div class="border border-gray-200 rounded-lg p-5 space-y-3 bg-white shadow-sm relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-xs text-gray-900">{{ $address->address_name }}</h4>

                                    <!-- Badge Keterangan Alamat Utama / Default -->
                                    @if ($loop->first)
                                        <span
                                            class="bg-black text-white text-[10px] font-medium px-2 py-0.5 rounded-full uppercase tracking-wider">
                                            Default Address
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                {{ $address->full_address }}<br>
                                {{ $address->city }} — {{ $address->postal_code }}<br>
                                Indonesia<br>
                                {{ $address->phone }}
                            </p>

                            <div class="flex items-center gap-4 pt-2 border-t border-gray-100 text-xs">
                                <!-- Tombol Edit -->
                                <a href="{{ route('address.edit', $address->id) }}"
                                    class="font-medium text-black hover:underline">Edit</a>

                                <!-- Tombol Remove Memicu Modal Pop-up -->
                                <button type="button" onclick="openDeleteModal('{{ $address->id }}')"
                                    class="text-gray-400 hover:text-red-600 transition">Remove</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 space-y-3">
                            <p class="text-xs text-gray-500">Belum ada alamat tersimpan di database.</p>
                            <a href="{{ route('profile.addresses.create') }}"
                                class="inline-block text-xs font-medium text-black underline">Tambah Alamat Baru
                                Sekarang</a>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>

    <!-- === KUSTOM MODAL POP-UP (DI TENGAH LAYAR) === -->
    <div id="deleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl space-y-4">
            <div class="space-y-2 text-center">
                <h3 class="text-base font-serif font-bold text-gray-900">Hapus Alamat Ini?</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Yakin nih, cuk? Alamat ini bakal dihapus permanen dari daftar pengiriman lu dan nggak bisa
                    dibalikin.
                </p>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-1/2 py-2 border border-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>

                <!-- Form untuk eksekusi DELETE ke Backend -->
                <form id="deleteForm" method="POST" class="w-1/2">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT BUAT KONTROL MODAL -->
    <script>
        function openDeleteModal(addressId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            form.action = `/profile/addresses/${addressId}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }
    </script>

</body>

</html>
