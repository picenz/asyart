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

    <!-- Container Utama Split Screen (Full Width) -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- 1. BAGIAN KIRI: BANNER GAMBAR & BRANDING -->
        <div class="w-full lg:w-1/2 h-72 lg:h-auto relative bg-cover bg-center p-6 lg:p-12 flex flex-col justify-between shrink-0"
            style="background-image: url('{{ asset('images/login.jpg') }}');">

            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Logo Top Left -->
            <div class="relative z-10">
                <h1 class="text-xl lg:text-2xl font-serif font-bold text-white tracking-widest uppercase">
                    ASYART.MERCH
                </h1>
            </div>

            <!-- Teks Bottom Left -->
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

        <!-- 2. BAGIAN KANAN: FORM FORGOT PASSWORD BREEZE -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">

                <!-- Header Text -->
                <div class="text-center space-y-2">
                    <h3 class="text-2xl md:text-3xl font-serif font-medium">Forgot your password?</h3>
                    <p class="text-xs text-gray-500 leading-relaxed px-4">
                        Enter the email linked to your account and we'll send you a secure link to reset your password.
                    </p>
                </div>

                <!-- Session Status Bawaan Breeze (Pas Berhasil Kirim Email Reset) -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form Password Email -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs text-gray-600 font-medium">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus
                            class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        <p class="text-[11px] text-gray-400 mt-1">
                            We'll only use this to send the reset link. It expires in 30 minutes.
                        </p>
                    </div>

                    <!-- Tombol Kirim Link Reset -->
                    <button type="submit"
                        class="w-full bg-black text-white text-sm py-3 rounded font-medium hover:bg-gray-800 transition mt-2">
                        Send reset link
                    </button>
                </form>

                <!-- Link Kembali ke Login -->
                <p class="text-center text-xs text-gray-500 pt-4">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-bold underline text-black hover:text-gray-800">
                        Back to sign in
                    </a>
                </p>

            </div>
        </div>

    </div>

</body>

</html>
