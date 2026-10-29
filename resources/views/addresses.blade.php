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

            <!-- Teks & Menu Navigasi (Menu Addresses aktif) -->
            <div class="relative z-10 text-white max-w-lg space-y-6 my-auto lg:my-12">
                <div class="space-y-2">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif italic leading-tight">
                        Addresses
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                        Manage your delivery addresses for faster checkout and seamless shipping.
                    </p>
                </div>

                <!-- Menu Navigasi Akun -->
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

        <!-- 2. BAGIAN KANAN: DAFTAR ALAMAT TERSIMPAN (SAVED ADDRESSES) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 md:p-12 bg-white overflow-y-auto">
            <div class="w-full max-w-xl mx-auto space-y-8">

                <!-- Header & Tombol Tambah Alamat -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-serif font-medium">Addresses</h3>
                        <p class="text-xs text-gray-500">Manage your saved shipping locations</p>
                    </div>
                    <button type="button" onclick="alert('Form tambah alamat baru akan muncul di sini')"
                        class="text-xs font-medium bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition active:scale-95 shadow-sm">
                        Add new address
                    </button>
                </div>

                <!-- LIST ALAMAT TERSIMPAN -->
                <div class="space-y-4">

                    <!-- Alamat Utama (Default) -->
                    <div class="border border-gray-200 rounded-lg p-5 space-y-3 bg-white shadow-sm relative">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs text-gray-900">Dawa Dzaki</h4>
                            <span
                                class="bg-black text-white text-[9px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded">Default</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Jl. Koja Raya No. 45<br>
                            Koja, Jakarta Utara 14220<br>
                            Indonesia<br>
                            +62 812 3456 7890
                        </p>
                        <div class="flex items-center gap-4 pt-2 border-t border-gray-100 text-xs">
                            <button type="button" onclick="alert('Edit alamat')"
                                class="font-medium text-black hover:underline">Edit</button>
                            <button type="button" onclick="alert('Hapus alamat')"
                                class="text-gray-400 hover:text-red-600">Remove</button>
                        </div>
                    </div>

                    <!-- Alamat Kedua (Studio / Lainnya) -->
                    <div class="border border-gray-200 rounded-lg p-5 space-y-3 bg-white shadow-sm">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs text-gray-900">Dawa Dzaki — Studio</h4>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Finness Studio, Lt. 4<br>
                            Jl. Kemang Raya No. 12<br>
                            Mampang Prapatan, Jakarta Selatan 12730<br>
                            Indonesia
                        </p>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="alert('Edit alamat')"
                                    class="font-medium text-black hover:underline">Edit</button>
                                <button type="button" onclick="alert('Hapus alamat')"
                                    class="text-gray-400 hover:text-red-600">Remove</button>
                            </div>
                            <button type="button" onclick="alertikan('Set as default')"
                                class="text-gray-500 hover:text-black underline">Set as default</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</body>

</html>
