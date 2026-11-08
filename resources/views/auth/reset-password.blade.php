<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Container Utama Split Screen -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- 1. BAGIAN KIRI: BANNER GAMBAR & BRANDING -->
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
                    From quirky characters to colorful prints, find the little things that make your style feel uniquely
                    yours.
                </p>
            </div>
        </div>

        <!-- 2. BAGIAN KANAN: FORM FORGOT PASSWORD DUMMY -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">

                <!-- Header Text -->
                <div class="space-y-2">
                    <h3 class="text-2xl md:text-3xl font-serif font-medium">Forgot your password?</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Enter the email linked to your account and we'll simulate sending a reset link.
                    </p>
                </div>

                <!-- AREA ALERT / KETERANGAN DUMMY (Akan muncul lewat JS) -->
                <div id="dummy-alert" class="hidden p-3 text-xs rounded border"></div>

                <!-- Form Dummy Murni Frontend Tanpa Action Backend -->
                <form onsubmit="handleDummySubmit(event)" class="space-y-4">
                    <!-- Email Input -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs text-gray-600 font-medium">Email</label>
                        <input id="email" type="email" name="email" value="dawadzaki11@gmail.com" required
                            class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                    </div>

                    <!-- Tombol Send Reset Link -->
                    <button type="submit"
                        class="w-full bg-black text-white text-sm py-3 rounded font-medium hover:bg-gray-800 transition active:scale-95 shadow-md">
                        Send reset link
                    </button>
                </form>

                <!-- Back to Sign In -->
                <div class="text-center text-xs text-gray-500 pt-2">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-bold underline text-black hover:text-gray-800">
                        Back to sign in
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- SCRIPT SIMULASI / DUMMY FRONTEND -->
    <script>
        function handleDummySubmit(event) {
            event.preventDefault(); // Mencegah form nembak backend Laravel asli

            const emailInput = document.getElementById('email').value.trim();
            const alertBox = document.getElementById('dummy-alert');

            // Daftar email yang dianggap terdaftar di database lu (termasuk dawadzaki11@gmail.com)
            const registeredEmails = ['dawadzaki11@gmail.com', 'admin@asyart.merch', 'dawa@gmail.com'];

            if (registeredEmails.includes(emailInput)) {
                // Keterangan Hijau Sukses (Terdaftar)
                alertBox.className =
                    "p-3 text-xs rounded bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium";
                alertBox.innerHTML =
                    `Password berhasil di reset! Silahkan cek email <strong>${emailInput}</strong> untuk tautan selanjutnya.`;
                alertBox.classList.remove('hidden');
            } else {
                // Keterangan Merah Gagal (Tidak Terdaftar)
                alertBox.className = "p-3 text-xs rounded bg-rose-50 border border-rose-200 text-rose-800 font-medium";
                alertBox.innerHTML =
                    `Akun tidak ditemukan! Silahkan <a href="{{ route('register') }}" class="underline font-bold">register</a> terlebih dahulu.`;
                alertBox.classList.remove('hidden');
            }
        }
    </script>

</body>

</html>
