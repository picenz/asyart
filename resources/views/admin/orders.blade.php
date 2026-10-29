<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-900 min-h-screen flex flex-col justify-between">

    <!-- NAVBAR ADMIN -->
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="text-lg font-bold font-serif tracking-wider">ASYART.MERCH <span
                    class="text-xs font-sans font-normal text-gray-500">| Admin Panel</span></span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-red-600 hover:underline font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <!-- KONTEN UTAMA DASHBOARD -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full flex-1 space-y-8">

        <!-- Header Sambutan -->
        <div
            class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-serif font-medium">Selamat Datang, Admin!</h1>
                <p class="text-xs text-gray-500 pt-1">Kelola pesanan masuk dan pantau aktivitas toko dari panel ini.</p>
            </div>
            <div class="text-xs bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg font-mono">
                Role: <span class="font-bold text-gray-900">Administrator</span>
            </div>
        </div>

        <!-- Menu Grid Kartu Akses Cepat -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            <!-- Kartu Menu Kelola Pesanan -->
            <a href="{{ route('admin.orders') }}"
                class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-black transition space-y-3 group">
                <div
                    class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-800 group-hover:bg-black group-hover:text-white transition">
                    📦
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Daftar Pesanan Masuk</h3>
                    <p class="text-xs text-gray-500 pt-1 leading-relaxed">Lihat rincian data pemesan, alamat pengiriman,
                        dan status pembayaran.</p>
                </div>
                <span class="inline-block text-xs font-semibold text-black group-hover:translate-x-1 transition">Kelola
                    pesanan →</span>
            </a>

            <!-- Kartu Tambahan (Opsional) -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm opacity-60 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                    👕
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-700">Kelola Produk</h3>
                    <p class="text-xs text-gray-400 pt-1 leading-relaxed">Tambah atau ubah katalog produk apparel
                        (Segera hadir).</p>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-[11px] text-gray-400 border-t border-gray-200 bg-white">
        &copy; 2026 ASYART.MERCH. All rights reserved.
    </footer>

</body>

</html>
