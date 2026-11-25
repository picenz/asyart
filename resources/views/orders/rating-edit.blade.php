<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rating Pesanan | ASYART.MERCH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body,
        .font-editorial {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

@php
    $orderNumber = $order->order_number ?? 'RN-' . $order->id;
    $placedDate = optional($order->created_at)->format('d M Y') ?? '-';
    $totalPrice = number_format($order->total_price ?? 0, 0, ',', '.');
    $decodedItems = json_decode($order->items_json ?? '[]', true);
    $items = is_array($decodedItems) ? $decodedItems : [];

    $firstItem = $items[0] ?? [];
    $orderItem = $orderItem ?? [
        'name' => $firstItem['nama'] ?? ($firstItem['name'] ?? 'Item Produk'),
        'color' => $firstItem['warna'] ?? ($firstItem['color'] ?? '-'),
        'size' => $firstItem['ukuran'] ?? ($firstItem['size'] ?? '-'),
        'qty' => $firstItem['qty'] ?? 1,
    ];

    $savedSessionRating = session('rating_draft_' . $order->id);
    $currentRating = (int) old(
        'rating',
        data_get($existingRating ?? null, 'rating') ??
            (data_get($rating ?? null, 'rating') ??
                (data_get($order, 'rating') ?? data_get($savedSessionRating, 'rating', 5))),
    );
    $currentRating = max(1, min(5, $currentRating));

    $currentReview = (string) old(
        'review',
        data_get($existingRating ?? null, 'review') ??
            (data_get($rating ?? null, 'review') ??
                (data_get($order, 'review') ?? data_get($savedSessionRating, 'review', ''))),
    );
@endphp

<body class="bg-neutral-100 text-neutral-900 antialiased selection:bg-neutral-900 selection:text-white">
    {{-- Top Bar Minimalis --}}
    <header class="sticky top-0 z-20 border-b border-neutral-200 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('profile.orders') }}"
                class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-black transition">
                ← KEMBALI KE ORDER HISTORY
            </a>

            <span class="text-sm font-bold tracking-[0.2em] uppercase text-neutral-950">
                ASYART.MERCH
            </span>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 sm:py-12 space-y-8">
        {{-- Hero / Judul Halaman --}}
        <section class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span
                        class="inline-block rounded-full bg-neutral-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-neutral-600">
                        Edit Rating &amp; Ulasan
                    </span>
                    <h1 class="mt-3 text-2xl font-bold tracking-tight text-neutral-950 sm:text-3xl">
                        Edit Rating Pesanan Anda
                    </h1>
                    <p class="mt-1.5 text-sm text-neutral-600">
                        Perbarui bintang dan ulasan kamu untuk pesanan <strong
                            class="font-semibold text-neutral-900">#{{ $orderNumber }}</strong> ({{ $placedDate }}).
                    </p>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
                            Status Order
                        </div>
                        <div class="mt-0.5 text-xs font-bold text-neutral-900">
                            {{ $order->status ?? 'Completed' }} · Rp{{ $totalPrice }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grid Utama: Form Edit Rating + Ringkasan Pesanan --}}
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Form Edit & Hapus Rating --}}
            <div class="lg:col-span-7 rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-xl font-bold tracking-tight text-neutral-950">Perbarui Ulasan Kamu</h2>
                    <span class="rounded-full bg-neutral-100 px-2.5 py-1 text-[11px] font-medium text-neutral-600">
                        Maks. 500 karakter
                    </span>
                </div>
                <p class="mt-1 text-xs text-neutral-500">
                    Ubah rating bintang atau teks ulasan pesanan ini, atau hapus rating jika diperlukan.
                </p>

                {{-- Detail Pesanan Item --}}
                <div class="mt-4 rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-500">
                            Detail Pesanan #{{ $orderNumber }}
                        </span>
                        <a href="{{ route('profile.order.detail', $order->id) }}"
                            class="text-[11px] font-medium text-neutral-600 hover:text-neutral-950 underline">
                            Lihat Detail Order →
                        </a>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-neutral-800 tabular-nums">
                        <span class="rounded-md bg-white border border-neutral-200 px-2.5 py-1">
                            Item: <strong class="font-semibold">{{ $orderItem['name'] ?? 'Item Produk' }}</strong>
                        </span>
                        <span class="rounded-md bg-white border border-neutral-200 px-2.5 py-1">
                            Warna: <strong class="font-semibold">{{ $orderItem['color'] ?? '-' }}</strong>
                        </span>
                        <span class="rounded-md bg-white border border-neutral-200 px-2.5 py-1">
                            Ukuran: <strong class="font-semibold">{{ $orderItem['size'] ?? '-' }}</strong>
                        </span>
                        <span class="rounded-md bg-white border border-neutral-200 px-2.5 py-1">
                            Jumlah: <strong class="font-semibold">{{ $orderItem['qty'] ?? 1 }} pcs</strong>
                        </span>
                    </div>
                </div>

                @if (session('success'))
                    <div
                        class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="editRatingForm" method="POST" action="{{ route('orders.rating.update', $order->id) }}"
                    class="mt-5 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Bintang SVG Interaktif dengan Outline Mengikuti Bentuk Bintang --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                            Rating Bintang
                        </label>
                        <div class="flex flex-wrap items-center gap-3">
                            <div id="starGroup" class="flex items-center gap-2 select-none">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" data-value="{{ $i }}"
                                        class="star-item p-0.5 transition duration-150 focus:outline-none hover:scale-110 active:scale-95 cursor-pointer"
                                        aria-label="Pilih {{ $i }} bintang">
                                        <svg class="star-svg h-8 w-8 text-neutral-300 transition duration-150"
                                            viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                                            stroke-width="0" stroke-linejoin="round">
                                            <path
                                                d="M12 2.75l2.86 5.8 6.4.93-4.63 4.51 1.09 6.37L12 17.36l-5.72 3 1.09-6.37-4.63-4.51 6.4-.93L12 2.75z" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <span id="ratingBadge"
                                class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 tabular-nums">
                                {{ $currentRating }}/5
                            </span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="{{ $currentRating }}">
                        <p id="ratingError" class="mt-1.5 hidden text-xs font-medium text-red-600">
                            Pilih minimal 1 bintang dulu cuy.
                        </p>
                        @error('rating')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kolom Komentar / Ulasan (Maks. 500 Karakter) --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="review"
                                class="text-xs font-semibold uppercase tracking-wider text-neutral-700">
                                Ulasan Pesanan
                            </label>
                            <span id="charCount" class="text-[11px] text-neutral-400 tabular-nums">
                                {{ mb_strlen($currentReview) }} / 500
                            </span>
                        </div>
                        <textarea id="review" name="review" rows="4" maxlength="500"
                            placeholder="Gimana bahan, ukuran, atau kualitas produknya? (Maks. 500 karakter)"
                            class="w-full rounded-xl border border-neutral-300 bg-neutral-50/60 px-3.5 py-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-neutral-950 focus:bg-white focus:outline-none transition">{{ $currentReview }}</textarea>
                        <p id="reviewError" class="mt-1.5 hidden text-xs font-medium text-red-600">
                            Ulasan maksimal 500 karakter.
                        </p>
                        @error('review')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Aksi: Hapus Rating + Batal + Simpan Perubahan --}}
                    <div class="pt-3 border-t border-neutral-100 flex flex-wrap items-center justify-between gap-3">
                        <button type="button" id="openDeleteModalBtn"
                            class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition cursor-pointer">
                            Hapus Rating
                        </button>

                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('profile.orders') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-neutral-300 bg-white px-4 py-2.5 text-xs font-semibold text-neutral-800 hover:bg-neutral-50 transition">
                                Batal
                            </a>
                            <button id="saveRatingBtn" type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-neutral-950 px-5 py-2.5 text-xs font-semibold text-white hover:bg-neutral-800 active:scale-[0.99] transition cursor-pointer">
                                Simpan Perubahan Rating
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Form DELETE terpisah untuk disambungkan BE --}}
                <form id="deleteRatingForm" method="POST" action="{{ route('orders.rating.destroy', $order->id) }}"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            {{-- Panel Samping: Ringkasan Pesanan & Info Hapus Rating --}}
            <div
                class="lg:col-span-5 rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-neutral-950">Ringkasan Pesanan</h2>
                    <p class="mt-1 text-xs text-neutral-500">
                        Rincian item pada pesanan <strong
                            class="font-semibold text-neutral-800">#{{ $orderNumber }}</strong>.
                    </p>

                    <div class="mt-5 divide-y divide-neutral-100 border-t border-b border-neutral-100 text-xs">
                        @forelse ($items as $item)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-neutral-900">
                                        {{ $item['nama'] ?? ($item['name'] ?? 'Item Produk') }}
                                    </p>
                                    <p class="text-[11px] text-neutral-500 mt-0.5">
                                        @if (!empty($item['warna']) || !empty($item['color']))
                                            Warna: {{ $item['warna'] ?? $item['color'] }} ·
                                        @endif
                                        @if (!empty($item['ukuran']) || !empty($item['size']))
                                            Ukuran: {{ $item['ukuran'] ?? $item['size'] }} ·
                                        @endif
                                        Qty: {{ $item['qty'] ?? 1 }}
                                    </p>
                                </div>
                                <span class="font-semibold text-neutral-900 tabular-nums">
                                    {{ $item['harga'] ?? '' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-4 text-neutral-500">
                                Detail item tersimpan pada pesanan #{{ $orderNumber }}.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs">
                        <span class="text-neutral-500">Total Pembayaran</span>
                        <span class="text-sm font-bold text-neutral-950 tabular-nums">Rp{{ $totalPrice }}</span>
                    </div>
                </div>

                <div class="mt-6 rounded-xl bg-neutral-100 p-4 text-xs text-neutral-600 leading-relaxed">
                    Tips: Jika kamu klik <strong>Hapus Rating</strong>, rating dan ulasan yang sudah diberikan untuk
                    pesanan ini akan dihapus. Karena sistem hanya mengizinkan <strong>1x rating</strong>, setelah
                    dihapus kamu <strong>tidak bisa</strong> memberikan rating baru lagi.
                </div>
            </div>
        </section>
    </main>

    {{-- Modal Konfirmasi Hapus Rating --}}
    <div id="deleteRatingModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-xs" role="dialog"
        aria-modal="true" aria-labelledby="deleteRatingTitle">
        <div class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span
                        class="inline-block rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-semibold text-red-800">
                        Konfirmasi Hapus Rating
                    </span>
                    <h3 id="deleteRatingTitle" class="mt-2 text-lg font-bold text-neutral-950">
                        Hapus Rating Pesanan #{{ $orderNumber }}?
                    </h3>
                </div>
                <button id="closeDeleteModalBtn" type="button"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-900 transition cursor-pointer"
                    aria-label="Tutup modal">
                    &times;
                </button>
            </div>

            <p class="text-sm leading-relaxed text-neutral-600">
                Rating dan ulasan kamu untuk pesanan ini akan dihapus. Karena sistem hanya mengizinkan
                <strong class="font-semibold text-neutral-900">1x rating</strong>, setelah dihapus kamu
                <strong class="font-semibold text-neutral-900">tidak bisa</strong> memberikan rating baru lagi untuk
                pesanan ini.
            </p>

            <div class="pt-2 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
                <button id="cancelDeleteModalBtn" type="button"
                    class="rounded-xl border border-neutral-300 bg-white px-4 py-2.5 text-xs font-semibold text-neutral-800 hover:bg-neutral-50 transition cursor-pointer">
                    Batal
                </button>
                <button id="confirmDeleteRatingBtn" type="button"
                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-red-700 transition cursor-pointer">
                    Ya, Hapus Rating
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const stars = document.querySelectorAll('.star-item');
            const ratingValue = document.getElementById('ratingValue');
            const ratingBadge = document.getElementById('ratingBadge');
            const ratingError = document.getElementById('ratingError');
            const reviewInput = document.getElementById('review');
            const reviewError = document.getElementById('reviewError');
            const charCount = document.getElementById('charCount');
            const editRatingForm = document.getElementById('editRatingForm');

            const deleteRatingModal = document.getElementById('deleteRatingModal');
            const openDeleteModalBtn = document.getElementById('openDeleteModalBtn');
            const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
            const cancelDeleteModalBtn = document.getElementById('cancelDeleteModalBtn');
            const confirmDeleteRatingBtn = document.getElementById('confirmDeleteRatingBtn');
            const deleteRatingForm = document.getElementById('deleteRatingForm');

            const ratingLabels = {
                1: '1/5 — Kurang pas',
                2: '2/5 — Lumayan',
                3: '3/5 — Standar',
                4: '4/5 — Mantap',
                5: '5/5 — Keren Abis!',
            };

            let selectedRating = Number(ratingValue?.value || 5);
            selectedRating = Math.max(1, Math.min(5, selectedRating));
            if (ratingValue) ratingValue.value = selectedRating;

            function renderStars(val) {
                stars.forEach((btn) => {
                    const svg = btn.querySelector('.star-svg');
                    const num = Number(btn.dataset.value);
                    const isLit = num <= val;
                    const isPicked = num === selectedRating && selectedRating > 0;

                    if (svg) {
                        svg.style.color = isLit ? '#fbbf24' : '#d4d4d4';
                        if (isPicked) {
                            svg.setAttribute('stroke', '#0a0a0a');
                            svg.setAttribute('stroke-width', '2');
                        } else {
                            svg.setAttribute('stroke', 'currentColor');
                            svg.setAttribute('stroke-width', '0');
                        }
                    }
                });

                if (ratingBadge) {
                    if (val > 0) {
                        ratingBadge.textContent = ratingLabels[val] || `${val}/5 Bintang`;
                        ratingBadge.className =
                            'rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 tabular-nums';
                    } else {
                        ratingBadge.textContent = 'Belum dipilih';
                        ratingBadge.className =
                            'rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-600 tabular-nums';
                    }
                }
            }

            stars.forEach((btn) => {
                btn.addEventListener('mouseenter', () => {
                    renderStars(Number(btn.dataset.value));
                });

                btn.addEventListener('click', () => {
                    selectedRating = Number(btn.dataset.value);
                    ratingValue.value = selectedRating;
                    ratingError?.classList.add('hidden');
                    renderStars(selectedRating);
                });
            });

            document.getElementById('starGroup')?.addEventListener('mouseleave', () => {
                renderStars(selectedRating);
            });

            reviewInput?.addEventListener('input', () => {
                if (reviewInput.value.length > 500) {
                    reviewInput.value = reviewInput.value.slice(0, 500);
                }
                if (charCount) {
                    charCount.textContent = `${reviewInput.value.length} / 500`;
                }
                if (reviewInput.value.length <= 500) {
                    reviewError?.classList.add('hidden');
                }
            });

            editRatingForm?.addEventListener('submit', (e) => {
                let valid = true;
                const score = Number(ratingValue?.value || 0);
                const text = reviewInput?.value || '';

                if (!score || score < 1 || score > 5) {
                    ratingError?.classList.remove('hidden');
                    valid = false;
                }
                if (text.length > 500) {
                    reviewError?.classList.remove('hidden');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                }
            });

            function openDeleteModal() {
                deleteRatingModal?.classList.remove('hidden');
                deleteRatingModal?.classList.add('flex');
            }

            function closeDeleteModal() {
                deleteRatingModal?.classList.add('hidden');
                deleteRatingModal?.classList.remove('flex');
            }

            openDeleteModalBtn?.addEventListener('click', openDeleteModal);
            closeDeleteModalBtn?.addEventListener('click', closeDeleteModal);
            cancelDeleteModalBtn?.addEventListener('click', closeDeleteModal);
            deleteRatingModal?.addEventListener('click', (e) => {
                if (e.target === deleteRatingModal) closeDeleteModal();
            });

            confirmDeleteRatingBtn?.addEventListener('click', () => {
                deleteRatingForm?.submit();
            });

            renderStars(selectedRating);
        })();
    </script>
</body>

</html>
