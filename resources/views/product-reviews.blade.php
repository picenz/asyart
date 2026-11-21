<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating &amp; Ulasan Produk | ASYART.MERCH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body,
        .font-editorial {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    // =========================================================================
    // KONTRAK STATUS & DATA PESANAN USER UNTUK ITEM INI (Siap di-config BE)
    // - $hasOrdered : true jika user pernah memesan item ini, false jika belum
    // - $hasReviewed: true jika user sudah pernah memberi rating pada item ini
    // - $orderItem  : ['color' => 'Black', 'size' => 'M', 'qty' => 2]
    // =========================================================================
    $hasOrdered = $hasOrdered ?? true;
    $hasReviewed = $hasReviewed ?? false;
    $orderItem = $orderItem ?? [
        'color' => 'Black',
        'size' => 'M',
        'qty' => 2,
    ];
    $canReview = $hasOrdered && !$hasReviewed;
@endphp

<body class="bg-neutral-100 text-neutral-900 antialiased selection:bg-neutral-900 selection:text-white">
    {{-- Top Bar Minimalis --}}
    <header class="sticky top-0 z-20 border-b border-neutral-200 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ url('/product/' . $id) }}"
                class="inline-flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-neutral-600 hover:text-neutral-950 transition">
                <span>&larr;</span>
                <span>Kembali ke produk</span>
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
                        Rating &amp; Ulasan Produk
                    </span>
                    <h1 class="mt-3 text-2xl font-bold tracking-tight text-neutral-950 sm:text-3xl">
                        Rating &amp; Ulasan Produk
                    </h1>
                    <p class="mt-1.5 text-sm text-neutral-600">
                        Bagikan pengalaman lo soal bahan, potongan, dan detail produk ini.
                    </p>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3">
                    <div class="text-3xl font-bold tracking-tight text-neutral-950 tabular-nums">4.9</div>
                    <div>
                        <div class="text-sm text-amber-400 leading-none">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <div class="mt-1 text-xs text-neutral-500">Customer Rating</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grid Utama: Form Review + Ringkasan Bintang --}}
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Form Kasih Rating --}}
            <div class="lg:col-span-7 rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-xl font-bold tracking-tight text-neutral-950">Tulis Ulasan Lu</h2>
                    <span class="rounded-full bg-neutral-100 px-2.5 py-1 text-[11px] font-medium text-neutral-600">
                        Maks. 1x rating per item
                    </span>
                </div>
                <p class="mt-1 text-xs text-neutral-500">Ulasan lo bakal bantu pembeli lain nentuin ukuran &amp;
                    pilihan.</p>

                {{-- Detail Pesanan Item (Warna, Ukuran, Qty) --}}
                @if ($hasOrdered)
                    <div class="mt-4 rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-500">
                                Detail Pesanan Item Ini
                            </span>
                            <span class="text-[11px] font-medium text-neutral-500">
                                1x kesempatan rating
                            </span>
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-neutral-800 tabular-nums">
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
                @else
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                        Kamu belum memesan produk ini. Pesan dulu produk ini untuk membuka akses rating &amp; ulasan.
                    </div>
                @endif

                @if ($hasReviewed)
                    <div
                        class="mt-4 rounded-xl border border-neutral-200 bg-neutral-100 px-4 py-3 text-xs font-medium text-neutral-800">
                        Kamu sudah memberikan rating untuk produk ini (1x rating per item).
                    </div>
                @endif

                <div id="submitToast"
                    class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800">
                    Mantap! Rating &amp; ulasan lu udah siap di-preview (belum masuk database ya, tinggal sambungin
                    endpoint POST).
                </div>

                <form id="reviewForm" action="#" method="POST" class="mt-5 space-y-5">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id }}">

                    {{-- Bintang SVG Interaktif dengan Outline Mengikuti Bentuk Bintang --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-700 mb-2">
                            Rating Bintang
                        </label>
                        <div class="flex flex-wrap items-center gap-3">
                            <div id="starGroup" class="flex items-center gap-2 select-none">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" data-value="{{ $i }}"
                                        @if (!$canReview) disabled @endif
                                        class="star-item p-0.5 transition duration-150 focus:outline-none {{ $canReview ? 'hover:scale-110 active:scale-95 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
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
                                5/5 — Gacor parah!
                            </span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5">
                        <p id="ratingError" class="mt-1.5 hidden text-xs font-medium text-red-600">
                            Pilih minimal 1 bintang dulu cuy.
                        </p>
                    </div>

                    {{-- Kolom Komentar --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="comment"
                                class="text-xs font-semibold uppercase tracking-wider text-neutral-700">
                                Ulasan Produk
                            </label>
                            <span id="charCount" class="text-[11px] text-neutral-400 tabular-nums">0 / 500</span>
                        </div>
                        <textarea id="comment" name="comment" rows="4" maxlength="500" @if (!$canReview) disabled @endif
                            placeholder="Gimana bahan, ukuran, atau kualitas produknya?"
                            class="w-full rounded-xl border border-neutral-300 bg-neutral-50/60 px-3.5 py-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-neutral-950 focus:bg-white focus:outline-none transition disabled:opacity-60 disabled:cursor-not-allowed"></textarea>
                        <p id="commentError" class="mt-1.5 hidden text-xs font-medium text-red-600">
                            Isi ulasan lo dulu sebelum dikirim.
                        </p>
                    </div>

                    <div class="pt-1 flex items-center justify-between gap-3">
                        <button id="submitReviewBtn" type="submit" @if ($hasReviewed) disabled @endif
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-neutral-950 px-6 py-3 text-sm font-semibold text-white hover:bg-neutral-800 active:scale-[0.99] transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ $hasReviewed ? 'Sudah Diberi Rating' : 'Kirim Rating' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Ringkasan Distribusi Bintang --}}
            <div
                class="lg:col-span-5 rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-neutral-950">Ringkasan Rating</h2>
                    <p class="mt-1 text-xs text-neutral-500">Gambaran sebaran bintang untuk produk ini.
                    </p>

                    @php
                        $breakdown = [
                            5 => 82,
                            4 => 12,
                            3 => 4,
                            2 => 1,
                            1 => 1,
                        ];
                    @endphp

                    <div class="mt-5 space-y-2.5 tabular-nums">
                        @foreach ($breakdown as $star => $percent)
                            <div class="flex items-center gap-3 text-xs">
                                <span class="w-8 font-medium text-neutral-700">{{ $star }} &#9733;</span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-neutral-100">
                                    <div class="h-full rounded-full bg-neutral-950"
                                        style="width: {{ $percent }}%">
                                    </div>
                                </div>
                                <span class="w-9 text-right text-neutral-500">{{ $percent }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 rounded-xl bg-neutral-100 p-4 text-xs text-neutral-600 leading-relaxed">
                    Tips: Kasih info tinggi/berat badan atau <em>size</em> yang lo pakai biar pembeli lain gampang
                    nentuin <em>fit</em> bajunya.
                </div>
            </div>
        </section>

        {{-- Daftar Review Terbaru --}}
        <section class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-2">
                <h2 class="text-xl font-bold tracking-tight text-neutral-950">Ulasan Pembeli</h2>
                <span id="reviewCountText" class="text-xs font-medium text-neutral-500 tabular-nums">2 ulasan</span>
            </div>

            <div id="reviewList" class="divide-y divide-neutral-100">
                <article class="py-5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-950 text-xs font-bold text-white">
                                GR
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-neutral-900">Gilang Ramadhan</p>
                                <p class="text-[11px] text-neutral-400">2 minggu lalu</p>
                            </div>
                        </div>
                        <span class="text-sm text-amber-400">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                    </div>
                    <p class="mt-2 text-xs text-neutral-500">
                        Varian: <span class="font-medium text-neutral-700">Black</span> · Ukuran: <span
                            class="font-medium text-neutral-700">M</span> · Jumlah: <span
                            class="font-medium text-neutral-700">1 pcs</span>
                    </p>
                    <p class="mt-2.5 text-sm leading-relaxed text-neutral-700">
                        Bahannya enak dipakai, nggak gerah, dan sablonannya rapi. Worth it banget buat harian.
                    </p>
                </article>

                <article class="py-5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-200 text-xs font-bold text-neutral-800">
                                DK
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-neutral-900">Dinda Kirana</p>
                                <p class="text-[11px] text-neutral-400">1 minggu lalu</p>
                            </div>
                        </div>
                        <span class="text-sm text-amber-400">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    </div>
                    <p class="mt-2 text-xs text-neutral-500">
                        Varian: <span class="font-medium text-neutral-700">Black</span> · Ukuran: <span
                            class="font-medium text-neutral-700">S</span> · Jumlah: <span
                            class="font-medium text-neutral-700">2 pcs</span>
                    </p>
                    <p class="mt-2.5 text-sm leading-relaxed text-neutral-700">
                        Cutting-nya cakep dan pas di badan. Pengiriman juga aman sampai tujuan.
                    </p>
                </article>
            </div>
        </section>
    </main>

    {{-- Popup Modal Jika Belum Memesan Produk Ini --}}
    <div id="notOrderedModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-xs" role="dialog"
        aria-modal="true" aria-labelledby="notOrderedTitle">
        <div class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span
                        class="inline-block rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-semibold text-amber-900">
                        Akses Rating Terkunci
                    </span>
                    <h3 id="notOrderedTitle" class="mt-2 text-lg font-bold text-neutral-950">
                        Silakan Pesan Produk Ini Terlebih Dahulu
                    </h3>
                </div>
                <button id="closeNotOrderedBtn" type="button"
                    class="rounded-lg p-1 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-900 transition cursor-pointer"
                    aria-label="Tutup popup">
                    &times;
                </button>
            </div>

            <p class="text-sm leading-relaxed text-neutral-600">
                Kamu belum pernah memesan produk ini. Silakan pesan produk ini terlebih dahulu sebelum memberikan rating
                dan ulasan (1x kesempatan rating per item yang dibeli).
            </p>

            <div class="pt-2 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
                <button id="okNotOrderedBtn" type="button"
                    class="rounded-xl border border-neutral-300 bg-white px-4 py-2.5 text-xs font-semibold text-neutral-800 hover:bg-neutral-50 transition cursor-pointer">
                    Mengerti
                </button>
                <a href="{{ url('/product/' . $id) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-neutral-950 px-4 py-2.5 text-xs font-semibold text-white hover:bg-neutral-800 transition">
                    Pesan Produk Sekarang
                </a>
            </div>
        </div>
    </div>

    <script>
        const hasOrdered = @json($hasOrdered);
        const orderItem = @json($orderItem);
        let hasReviewed = @json($hasReviewed);
        let canReview = hasOrdered && !hasReviewed;

        const stars = document.querySelectorAll('.star-item');
        const ratingValue = document.getElementById('ratingValue');
        const ratingBadge = document.getElementById('ratingBadge');
        const ratingError = document.getElementById('ratingError');
        const commentInput = document.getElementById('comment');
        const commentError = document.getElementById('commentError');
        const charCount = document.getElementById('charCount');
        const reviewForm = document.getElementById('reviewForm');
        const submitReviewBtn = document.getElementById('submitReviewBtn');
        const reviewList = document.getElementById('reviewList');
        const reviewCountText = document.getElementById('reviewCountText');
        const submitToast = document.getElementById('submitToast');

        const notOrderedModal = document.getElementById('notOrderedModal');
        const closeNotOrderedBtn = document.getElementById('closeNotOrderedBtn');
        const okNotOrderedBtn = document.getElementById('okNotOrderedBtn');

        const ratingLabels = {
            1: '1/5 — Kurang pas',
            2: '2/5 — Lumayan',
            3: '3/5 — Standar',
            4: '4/5 — Mantap',
            5: '5/5 — Gacor parah!',
        };

        let selectedRating = 5;
        ratingValue.value = selectedRating;

        function openNotOrderedPopup() {
            notOrderedModal.classList.remove('hidden');
            notOrderedModal.classList.add('flex');
        }

        function closeNotOrderedPopup() {
            notOrderedModal.classList.add('hidden');
            notOrderedModal.classList.remove('flex');
        }

        closeNotOrderedBtn?.addEventListener('click', closeNotOrderedPopup);
        okNotOrderedBtn?.addEventListener('click', closeNotOrderedPopup);
        notOrderedModal?.addEventListener('click', (e) => {
            if (e.target === notOrderedModal) closeNotOrderedPopup();
        });

        function renderStars(val) {
            stars.forEach((btn) => {
                const svg = btn.querySelector('.star-svg');
                const num = Number(btn.dataset.value);
                const isLit = num <= val;
                const isPicked = num === selectedRating && selectedRating > 0;

                svg.style.color = isLit ? '#fbbf24' : '#d4d4d4';

                if (isPicked) {
                    svg.setAttribute('stroke', '#0a0a0a');
                    svg.setAttribute('stroke-width', '2');
                } else {
                    svg.setAttribute('stroke', 'currentColor');
                    svg.setAttribute('stroke-width', '0');
                }
            });

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

        stars.forEach((btn) => {
            btn.addEventListener('mouseenter', () => {
                if (!canReview) return;
                renderStars(Number(btn.dataset.value));
            });

            btn.addEventListener('click', () => {
                if (!hasOrdered) {
                    openNotOrderedPopup();
                    return;
                }
                if (hasReviewed) return;

                selectedRating = Number(btn.dataset.value);
                ratingValue.value = selectedRating;
                ratingError.classList.add('hidden');
                renderStars(selectedRating);
            });
        });

        document.getElementById('starGroup').addEventListener('mouseleave', () => {
            renderStars(selectedRating);
        });

        commentInput.addEventListener('input', () => {
            charCount.textContent = `${commentInput.value.length} / 500`;
            if (commentInput.value.trim()) {
                commentError.classList.add('hidden');
            }
        });

        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();

            if (!hasOrdered) {
                openNotOrderedPopup();
                return;
            }

            if (hasReviewed) {
                return;
            }

            let valid = true;
            if (!ratingValue.value) {
                ratingError.classList.remove('hidden');
                valid = false;
            }
            if (!commentInput.value.trim()) {
                commentError.classList.remove('hidden');
                valid = false;
            }
            if (!valid) return;

            const score = Number(ratingValue.value);
            const text = commentInput.value.trim();
            const starVisual = '★'.repeat(score) + '☆'.repeat(5 - score);
            const colorText = orderItem?.color ?? '-';
            const sizeText = orderItem?.size ?? '-';
            const qtyText = orderItem?.qty ?? 1;

            const article = document.createElement('article');
            article.className = 'py-5';
            article.innerHTML = `
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-950 text-xs font-bold text-white">
                            LO
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Kamu (Preview)</p>
                            <p class="text-[11px] text-neutral-400">Baru saja</p>
                        </div>
                    </div>
                    <span class="text-sm text-amber-400">${starVisual}</span>
                </div>
                <p class="mt-2 text-xs text-neutral-500">
                    Varian: <span class="font-medium text-neutral-700"></span> · Ukuran: <span class="font-medium text-neutral-700"></span> · Jumlah: <span class="font-medium text-neutral-700"></span>
                </p>
                <p class="mt-2.5 text-sm leading-relaxed text-neutral-700 review-comment"></p>
            `;
            const variantSpans = article.querySelectorAll('p.mt-2 span');
            if (variantSpans[0]) variantSpans[0].textContent = colorText;
            if (variantSpans[1]) variantSpans[1].textContent = sizeText;
            if (variantSpans[2]) variantSpans[2].textContent = `${qtyText} pcs`;
            article.querySelector('p.review-comment').textContent = text;
            reviewList.prepend(article);

            const totalItems = reviewList.querySelectorAll('article').length;
            reviewCountText.textContent = `${totalItems} ulasan`;

            // Kunci agar hanya bisa 1x rating per item
            hasReviewed = true;
            canReview = false;
            commentInput.disabled = true;
            stars.forEach((btn) => {
                btn.disabled = true;
                btn.classList.remove('hover:scale-110', 'active:scale-95', 'cursor-pointer');
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
            submitReviewBtn.disabled = true;
            submitReviewBtn.textContent = 'Sudah Diberi Rating';

            submitToast.classList.remove('hidden');
            reviewForm.reset();
            selectedRating = 5;
            ratingValue.value = selectedRating;
            charCount.textContent = '0 / 500';
            renderStars(selectedRating);
        });

        renderStars(selectedRating);
    </script>
</body>

</html>
