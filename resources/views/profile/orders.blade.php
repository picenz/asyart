<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Container Utama Split Screen -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- 1. BAGIAN KRI: BANNER GAMBAR & MENU NAVIGASI AKUN -->
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

            <!-- Teks & Menu Navigasi (Menu Orders aktif) -->
            <div class="relative z-10 text-white max-w-lg space-y-6 my-auto lg:my-12">
                <div class="space-y-2">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif italic leading-tight">
                        Order History
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                        Track your past purchases, view delivery statuses, and check detailed item summaries.
                    </p>
                </div>

                <!-- Menu Navigasi Akun -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 space-y-2 text-xs">
                    <a href="{{ route('profile.edit') }}"
                        class="block text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded transition">
                        Account details
                    </a>
                    <a href="{{ route('profile.orders') }}"
                        class="block font-semibold text-white bg-white/20 px-3 py-2 rounded transition">
                        ✓ Orders
                    </a>
                    <a href="{{ route('profile.addresses') }}"
                        class="block text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded transition">
                        Addresses
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

        <!-- 2. BAGIAN KANAN: DAFTAR RIWAYAT PESANAN (DINAMIS DARI DATABASE) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 md:p-12 bg-white overflow-y-auto">
            <div class="w-full max-w-xl mx-auto space-y-8">

                <!-- Header Halaman -->
                <div class="pb-4 border-b border-gray-100">
                    <h3 class="text-2xl font-serif font-medium">Order history</h3>
                    <p class="text-xs text-gray-500">View and track all your completed or ongoing orders</p>
                </div>

                <!-- LIST ORDERS -->
                <div class="space-y-6">
                    @forelse($orders as $order)
                        <!-- Order Card Dinamis -->
                        <div class="border border-gray-200 rounded-lg p-5 space-y-4 shadow-sm bg-white">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3 text-xs">
                                <div>
                                    <h4 class="font-bold text-gray-900">Order
                                        #{{ $order->order_number ?? 'RN-' . $order->id }}</h4>
                                    <p class="text-[10px] text-gray-400">Placed
                                        {{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <span
                                    class="text-[10px] font-semibold px-2.5 py-1 rounded {{ ($order->status ?? 'Processing') == 'Delivered' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->status ?? 'Processing' }}
                                </span>
                            </div>

                            <!-- Item Produk / Info Pesanan -->
                            <div class="flex justify-between items-center text-xs py-1">
                                <p class="text-gray-500">Total items / Detail pesanan tersimpan</p>
                                <span class="font-bold text-gray-900">Total
                                    Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <!-- Footer Card Order (Action & Total Terpisah Sempurna) -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs">
                                <a href="{{ route('profile.order.detail', $order->id) }}"
                                    class="hover:underline font-medium text-black">View details →</a>
                                <div class="text-right">
                                    <span class="text-[10px] text-gray-400">Total</span>
                                    <span
                                        class="font-bold text-gray-900 ml-1">Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 space-y-2 text-gray-400 text-xs">
                            <p class="font-medium text-gray-600">No order history found yet.</p>
                            <p>Your completed transactions will appear here once you finish a checkout.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>

</body>

</html>
