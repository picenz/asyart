<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - ASYART.MERCH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Bagian Kiri: Visual Brand ASYART.MERCH -->
        <div class="w-full lg:w-1/2 h-72 lg:h-auto relative bg-cover bg-center p-6 lg:p-12 flex flex-col justify-between shrink-0"
             style="background-image: url('{{ asset('images/login.jpg') }}');">
            <div class="absolute inset-0 bg-black/30"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}"
                   class="text-xl lg:text-2xl font-serif font-bold text-white tracking-widest uppercase hover:opacity-80 transition inline-block">
                    ASYART.MERCH
                </a>
            </div>

            <div class="relative z-10 text-white max-w-lg space-y-2 lg:space-y-4">
                <h2 class="text-2xl sm:text-3xl lg:text-5xl font-serif italic leading-tight">
                    Cute little tees for your everyday daydreams
                </h2>
                <p class="hidden sm:block text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                    From quirky characters to colorful prints, find the little things that make your style feel uniquely yours.
                </p>
            </div>
        </div>

        <!-- Bagian Kanan: Form Verifikasi OTP -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <a href="{{ route('login') }}"
                       class="text-xs text-gray-500 hover:text-black inline-flex items-center gap-1 transition">
                        ← Kembali ke Login
                    </a>
                </div>

                <div class="text-center space-y-2">
                    <div class="mx-auto w-11 h-11 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-serif font-medium">
                        Verifikasi Kode OTP
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed px-4">
                        Kami telah mengirimkan 6 digit kode verifikasi ke email kamu. Kode ini berlaku selama <span class="font-semibold text-gray-700">5 menit</span>.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-3 rounded border border-green-200 bg-green-50 text-xs text-green-700 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="otp-form" method="POST" action="{{ route('login.otp.verify') }}" class="space-y-5">
                    @csrf

                    <!-- Input Hidden yang dikirim ke Laravel -->
                    <input type="hidden" id="otp" name="otp" value="{{ old('otp') }}">

                    <div class="space-y-2">
                        <label class="block text-xs text-gray-600 font-medium text-center">
                            Masukkan 6 Digit Kode OTP
                        </label>

                        <!-- 6 Kotak Input OTP Interaktif -->
                        <div class="flex items-center justify-center gap-2 sm:gap-3" id="otp-boxes">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text"
                                       inputmode="numeric"
                                       maxlength="1"
                                       pattern="[0-9]"
                                       autocomplete="one-time-code"
                                       aria-label="Digit {{ $i + 1 }}"
                                       class="otp-digit w-11 h-12 sm:w-12 sm:h-14 text-center font-mono text-lg sm:text-xl font-bold border border-gray-300 rounded focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition"
                                       {{ $i === 0 ? 'autofocus' : '' }}>
                            @endfor
                        </div>

                        <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center" />
                    </div>

                    <button type="submit"
                            class="w-full bg-black text-white text-sm py-3 rounded font-medium hover:bg-gray-800 transition">
                        Verifikasi &amp; Login
                    </button>
                </form>

                <div class="pt-1">
                    <form method="POST" action="{{ route('login.otp.resend') }}">
                        @csrf
                        <button type="submit"
                                id="btn-resend-otp"
                                class="w-full border border-gray-300 text-xs text-gray-700 py-2.5 rounded font-medium hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Tidak menerima kode? Kirim Ulang OTP
                        </button>
                    </form>
                </div>

                <p class="text-center text-[11px] text-gray-400">
                    Pastikan juga untuk memeriksa folder <span class="underline">Spam</span> atau <span class="underline">Promosi</span> pada email kamu.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const digits = document.querySelectorAll('.otp-digit');
            const hiddenOtp = document.getElementById('otp');
            const form = document.getElementById('otp-form');

            function syncOtpValue() {
                hiddenOtp.value = Array.from(digits).map(d => d.value).join('');
            }

            digits.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    syncOtpValue();

                    if (e.target.value && idx < digits.length - 1) {
                        digits[idx + 1].focus();
                    }

                    if (hiddenOtp.value.length === 6) {
                        form.submit();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                        digits[idx - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData)
                        .getData('text')
                        .replace(/[^0-9]/g, '')
                        .slice(0, 6);

                    pasted.split('').forEach((char, i) => {
                        if (digits[i]) digits[i].value = char;
                    });
                    syncOtpValue();

                    const nextIndex = Math.min(pasted.length, digits.length - 1);
                    digits[nextIndex].focus();

                    if (hiddenOtp.value.length === 6) {
                        form.submit();
                    }
                });
            });

            // Cooldown 60 detik real-time untuk tombol Kirim Ulang OTP
            let resendSeconds = {{ (int) ($resendCooldown ?? session('resend_cooldown', 0)) }};
            const btnResend = document.getElementById('btn-resend-otp');

            if (resendSeconds > 0 && btnResend) {
                btnResend.disabled = true;
                btnResend.textContent = `Kirim Ulang OTP (${resendSeconds}s)`;

                const resendTimer = setInterval(() => {
                    resendSeconds--;
                    if (resendSeconds > 0) {
                        btnResend.textContent = `Kirim Ulang OTP (${resendSeconds}s)`;
                    } else {
                        clearInterval(resendTimer);
                        btnResend.disabled = false;
                        btnResend.textContent = 'Tidak menerima kode? Kirim Ulang OTP';
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>