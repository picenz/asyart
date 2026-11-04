    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shopping Bag - ASYART.MERCH</title>
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-white text-gray-900 font-sans relative">

        <!-- NAVBAR -->
        <div class="sticky top-0 z-50 bg-white border-b border-gray-200">
            <nav class="max-w-7xl mx-auto px-4 md:px-12 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider font-serif">ASYART.MERCH</a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('cart.page') }}" class="relative hover:opacity-75 transition duration-150 mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <!-- COUNTER JUMLAH BARIS ITEM UNIK -->
                        <span id="cart-count-desktop"
                            class="absolute -top-1 -right-2 text-[10px] bg-black text-white rounded-full w-4 h-4 flex items-center justify-center font-medium">0</span>
                    </a>
                    <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-black">← Continue Shopping</a>
                </div>
            </nav>
        </div>

        <!-- CONTAINER UTAMA KERANJANG -->
        <main class="max-w-4xl mx-auto px-4 md:px-12 py-8">
            <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm space-y-6">

                <!-- HEADER & TOMBOL CLEAR CART DENGAN IKON -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl font-serif font-medium">Your Bag</h1>
                        <span id="bag-item-count" class="text-xs text-gray-400">0 items</span>
                    </div>

                    <!-- Tombol Kosongkan Keranjang (Clear All) -->
                    <button type="button" id="clear-cart-trigger-btn" onclick="openClearCartModal()"
                        class="hidden items-center gap-1.5 text-xs text-red-500 hover:text-red-700 font-medium transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Clear Cart
                    </button>
                </div>

                <!-- List Item di Keranjang -->
                <div id="cart-items-container" class="divide-y divide-gray-100 space-y-4">
                    <!-- Dirender dinamis via JavaScript -->
                </div>

                <!-- Bagian Subtotal & Checkout -->
                <div id="cart-summary-section" class="pt-4 border-t border-gray-100 space-y-4 hidden">
                    <div class="flex justify-between items-center text-sm font-semibold">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">Rp0</span>
                    </div>
                    <button type="button" onclick="checkout()"
                        class="w-full bg-black text-white py-3.5 rounded text-xs font-medium hover:bg-gray-800 active:scale-95 transition cursor-pointer">
                        Checkout
                    </button>
                </div>

            </div>
        </main>

        <!-- MODAL KONFIRMASI HAPUS SATU ITEM -->
        <div id="delete-modal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center px-4">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full space-y-4 shadow-2xl text-center">
                <h3 class="text-sm font-semibold text-gray-900">Hapus Produk?</h3>
                <p class="text-xs text-gray-500">Yakin nih mau ngapus produk ini dari keranjang lu? Ntar kehabisan lho.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-1/2 border border-gray-300 py-2.5 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button type="button" id="confirm-delete-btn"
                        class="w-1/2 bg-red-600 text-white py-2.5 rounded text-xs font-medium hover:bg-red-700 transition">Hapus</button>
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI KOSONGKAN SEMUA KERANJANG (CLEAR CART) -->
        <div id="clear-cart-modal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center px-4">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full space-y-4 shadow-2xl text-center">
                <h3 class="text-sm font-semibold text-gray-900">Kosongkan Keranjang?</h3>
                <p class="text-xs text-gray-500">Yakin ingin mengosongkan keranjang belanjaan? Semua produk yang udah
                    dimasukin bakal kehapus lho.</p>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeClearCartModal()"
                        class="w-1/2 border border-gray-300 py-2.5 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button type="button" id="confirm-clear-cart-btn" onclick="clearEntireCart()"
                        class="w-1/2 bg-red-600 text-white py-2.5 rounded text-xs font-medium hover:bg-red-700 transition">Kosongkan</button>
                </div>
            </div>
        </div>

        <!-- SCRIPT JAVASCRIPT MASTER SINKRONISASI -->


<script>
    const cartUrl = @json(route('cart.page'));
    const checkoutUrl = @json(url('/checkout'));
    const cartItemsUrl = @json(url('/cart/items'));
    const clearCartUrl = @json(route('cart.clear'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let cart = @json($items);
    let itemIndexToDelete = null;
    let busy = false;

    const rupiah = value =>
        'Rp' + Number(value || 0).toLocaleString('id-ID');

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[char]);
    }

    async function cartRequest(url, method, data = null) {
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: data ? JSON.stringify(data) : null
        });

        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(
                Object.values(result.errors || {}).flat().join('\n') ||
                result.message ||
                'Gagal memperbarui keranjang.'
            );
        }

        return result;
    }

    function updateCartCountUI() {
        const badge = document.getElementById('cart-count-desktop');

        if (badge) {
            badge.innerText = cart.length;
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const summary = document.getElementById('cart-summary-section');
        const count = document.getElementById('bag-item-count');
        const subtotalEl = document.getElementById('cart-subtotal');
        const clearBtn = document.getElementById('clear-cart-trigger-btn');

        updateCartCountUI();

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="py-12 text-center space-y-3">
                    <p class="text-xs text-gray-400">
                        Keranjang belanjaan lu masih kosong nih.
                    </p>
                    <a href="{{ route('home') }}"
                       class="inline-block bg-black text-white text-xs px-6 py-2.5 rounded">
                        Mulai Belanja
                    </a>
                </div>
            `;

            summary.classList.add('hidden');
            clearBtn.classList.add('hidden');
            clearBtn.classList.remove('flex');
            count.innerText = '0 items';
            subtotalEl.innerText = 'Rp0';
            return;
        }

        summary.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
        clearBtn.classList.add('flex');

        count.innerText =
            `${cart.length} item${cart.length > 1 ? 's' : ''}`;

        let subtotal = 0;

        container.innerHTML = cart.map(item => {
            const price = Number(item.harga) || 0;
            const qty = Number(item.qty) || 1;
            const stock = Number(item.stock) || 0;
            const total = price * qty;

            subtotal += total;

            return `
                <div class="flex flex-col sm:flex-row sm:items-center
                            justify-between gap-4 p-3 rounded-lg border
                            border-gray-100 hover:border-gray-200
                            transition bg-gray-50/50">

                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <img src="${escapeHTML(item.gambar)}"
                             alt="${escapeHTML(item.nama)}"
                             class="w-20 h-20 object-cover rounded
                                    bg-gray-100 border border-gray-200 shrink-0">

                        <div class="space-y-2 min-w-0">
                            <h4 class="text-xs font-semibold text-gray-900">
                                ${escapeHTML(item.nama)}
                            </h4>

                            <p class="text-[11px] text-gray-600">
                                Color: ${escapeHTML(item.warna)}
                                · Size: ${escapeHTML(item.ukuran)}
                            </p>

                            <p class="text-xs font-medium text-gray-800">
                                ${rupiah(price)}
                            </p>

                            <p class="text-[10px] text-gray-400">
                                Sisa stok:
                                <span class="font-semibold text-gray-700">
                                    ${stock} pcs
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end
                                gap-4 pt-3 sm:pt-0 border-t sm:border-t-0
                                border-gray-100">

                        <div class="flex items-center border border-gray-200
                                    bg-white rounded text-xs">

                            <button type="button"
                                onclick="updateQty(${item.id}, -1)"
                                ${busy || qty <= 1 ? 'disabled' : ''}
                                class="px-2.5 py-1.5 text-gray-500 hover:text-black">
                                −
                            </button>

                            <span class="px-2 py-1.5 font-medium">
                                ${qty}
                            </span>

                            <button type="button"
                                onclick="updateQty(${item.id}, 1)"
                                ${busy || qty >= stock ? 'disabled' : ''}
                                class="px-2.5 py-1.5 text-gray-500 hover:text-black">
                                +
                            </button>
                        </div>

                        <span class="text-xs font-semibold w-24 text-right">
                            ${rupiah(total)}
                        </span>

                        <button type="button"
                            onclick="openDeleteModal(${item.id})"
                            ${busy ? 'disabled' : ''}
                            class="text-[10px] text-red-600 hover:underline">
                            Remove
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        subtotalEl.innerText = rupiah(subtotal);
    }

    async function updateQty(id, delta) {
        if (busy) return;

        const item = cart.find(row => row.id === id);
        if (!item) return;

        const quantity = Number(item.qty) + delta;

        if (quantity < 1) {
            openDeleteModal(id);
            return;
        }

        if (quantity > Number(item.stock)) {
            alert('Jumlah melebihi stok yang tersedia.');
            return;
        }

        await performCartAction(async () => {
            await cartRequest(`${cartItemsUrl}/${id}`, 'PATCH', {
                quantity
            });
        });
    }

    function openDeleteModal(id) {
        itemIndexToDelete = id;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        itemIndexToDelete = null;
        document.getElementById('delete-modal').classList.add('hidden');
    }

    document.getElementById('confirm-delete-btn')
        .addEventListener('click', async () => {
            if (itemIndexToDelete === null || busy) return;

            const id = itemIndexToDelete;

            await performCartAction(async () => {
                await cartRequest(`${cartItemsUrl}/${id}`, 'DELETE');
                closeDeleteModal();
            });
        });

    function openClearCartModal() {
        document.getElementById('clear-cart-modal').classList.remove('hidden');
    }

    function closeClearCartModal() {
        document.getElementById('clear-cart-modal').classList.add('hidden');
    }

    async function clearEntireCart() {
        if (busy) return;

        await performCartAction(async () => {
            await cartRequest(clearCartUrl, 'DELETE');
            closeClearCartModal();
        });
    }

    async function performCartAction(action) {
        if (busy) return;

        busy = true;
        renderCart();

        try {
            await action();
            window.location.reload();
        } catch (error) {
            alert(error.message);
            busy = false;
            renderCart();
        }
    }

    function checkout() {
        window.location.href = checkoutUrl;
    }

    document.addEventListener('DOMContentLoaded', renderCart);
</script>
</body>
</html>

