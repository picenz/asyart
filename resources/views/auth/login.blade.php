<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - ASYART.MERCH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desktop: seluruh konten login terlihat dalam satu layar */
        @media (min-width: 1024px) {
            html, body { height: 100%; }
            body { overflow: hidden; }
            .login-main { height: 100vh; min-height: 0; overflow: hidden; }
            .login-banner { height: 100vh; min-height: 0; }
            .login-panel {
                height: 100vh;
                min-height: 0;
                padding: 0.65rem 2rem;
                overflow: hidden;
            }
            .login-content {
                width: 100%;
                max-width: 26rem;
                gap: 0.35rem;
            }
            .login-content > :not([hidden]) ~ :not([hidden]),
            .login-form > :not([hidden]) ~ :not([hidden]),
            .login-header > :not([hidden]) ~ :not([hidden]),
            .login-field > :not([hidden]) ~ :not([hidden]) {
                margin-top: 0;
            }
            .login-header { gap: 0.2rem; }
            .login-header h2 { font-size: 1.45rem; line-height: 1.75rem; }
            .login-header p { font-size: 0.68rem; line-height: 1rem; }
            .login-form { gap: 0.35rem; }
            .login-field { gap: 0.1rem; }
            .login-input { padding-top: 0.38rem; padding-bottom: 0.38rem; }
            .login-field label, .login-content label { font-size: 0.68rem; }
            .login-content .space-y-2 { row-gap: 0.25rem; }
            .captcha-code, .captcha-refresh { padding-top: 0.3rem; padding-bottom: 0.3rem; }
            .captcha-code span { font-size: 1rem; }
            .login-submit { padding-top: 0.45rem; padding-bottom: 0.45rem; }
            .login-divider { margin-top: 0.3rem; margin-bottom: 0.3rem; }
            .login-content > p { padding-top: 0.15rem; }
            .login-content a, .login-content button { line-height: 1.2rem; }
        }

        /* Layar laptop yang tingginya pendek: rapatkan lagi tanpa menghilangkan link Register */
        @media (min-width: 1024px) and (max-height: 760px) {
            .login-panel { padding-top: 0.35rem; padding-bottom: 0.35rem; }
            .login-content { gap: 0.2rem; }
            .login-header h2 { font-size: 1.25rem; line-height: 1.45rem; }
            .login-header p { font-size: 0.62rem; line-height: 0.85rem; }
            .login-form { gap: 0.22rem; }
            .login-input { padding-top: 0.28rem; padding-bottom: 0.28rem; }
            .captcha-code, .captcha-refresh { padding-top: 0.2rem; padding-bottom: 0.2rem; }
            .login-submit { padding-top: 0.32rem; padding-bottom: 0.32rem; }
            .login-divider { margin-top: 0.15rem; margin-bottom: 0.15rem; }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <main class="login-main flex min-h-screen flex-col lg:flex-row">

        <!-- Bagian kiri: banner dan branding -->
        <section
            class="login-banner relative flex h-64 w-full shrink-0 flex-col justify-between bg-cover bg-center p-6 sm:h-72 lg:min-h-screen lg:w-1/2 lg:p-12"
            style="background-image: url('{{ asset('images/login.jpg') }}');">

            <div class="absolute inset-0 bg-black/30"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}"
                    class="inline-block text-xl font-serif font-bold uppercase tracking-widest text-white transition hover:opacity-80 lg:text-2xl">
                    ASYART.MERCH
                </a>
            </div>

            <div class="relative z-10 max-w-lg space-y-2 text-white lg:space-y-4">
                <h1 class="text-2xl font-serif italic leading-tight sm:text-3xl lg:text-5xl">
                    Cute little tees for your everyday daydreams
                </h1>

                <p class="hidden text-xs leading-relaxed text-gray-200 opacity-90 sm:block lg:text-sm">
                    From quirky characters to colorful prints, find the little things that make your style feel uniquely yours.
                </p>
            </div>
        </section>

        <!-- Bagian kanan: form login -->
        <section class="login-panel flex w-full flex-1 items-center justify-center bg-white px-6 py-8 sm:px-10 lg:w-1/2 lg:px-10 lg:py-12">
            <div class="login-content flex w-full max-w-md flex-col space-y-4 lg:space-y-3">

                <!-- Kembali ke halaman utama -->
                <div>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-1 text-xs text-gray-500 transition hover:text-black">
                        <span aria-hidden="true">←</span>
                        Back to Home
                    </a>
                </div>

                <!-- Header -->
                <div class="login-header space-y-2 text-center">
                    <h2 class="text-2xl font-serif font-medium md:text-3xl">
                        Welcome back to ASYART.MERCH
                    </h2>

                    <p class="px-4 text-xs leading-relaxed text-gray-500">
                        A collection of cute graphic tees designed to add a touch of fun, color, and sweetness to your everyday style.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form login -->
                <form method="POST" action="{{ route('login') }}" class="login-form flex flex-col space-y-3 lg:space-y-2">
                    @csrf

                    <!-- Email -->
                    <div class="login-field space-y-1">
                        <label for="email" class="block text-xs font-medium text-gray-600">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="login-input w-full rounded border border-gray-300 px-3.5 py-2 text-sm transition focus:border-black focus:outline-none">

                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="login-field space-y-1">
                        <label for="password" class="block text-xs font-medium text-gray-600">
                            Password
                        </label>

                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="login-input w-full rounded border border-gray-300 px-3.5 py-2 pr-10 text-sm transition focus:border-black focus:outline-none">

                            <button
                                type="button"
                                onclick="togglePassword('password', 'icon-login-pwd')"
                                aria-label="Tampilkan atau sembunyikan password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 transition hover:text-black">

                                <svg
                                    id="icon-login-pwd"
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- CAPTCHA -->
                    <div class="space-y-2">
                        <label for="captcha" class="block text-xs font-medium text-gray-600">
                            CAPTCHA
                        </label>

                        <div class="flex items-center gap-3">
                            <div class="captcha-code flex flex-1 items-center justify-center rounded border border-gray-300 bg-gray-100 px-4 py-2 text-center">
                                <span class="select-none font-mono text-xl font-bold tracking-[0.3em]">
                                    {{ $captcha }}
                                </span>
                            </div>

                            <button
                                type="button"
                                onclick="window.location.reload()"
                                class="captcha-refresh rounded border border-gray-300 px-3 py-2 text-xs transition hover:bg-gray-50">
                                ↻ Refresh
                            </button>
                        </div>

                        <input
                            id="captcha"
                            type="text"
                            name="captcha"
                            required
                            maxlength="6"
                            autocomplete="off"
                            placeholder="Ketik kode di atas"
                            class="login-input w-full rounded border border-gray-300 px-3.5 py-2 text-sm transition focus:border-black focus:outline-none">

                        <x-input-error :messages="$errors->get('captcha')" class="mt-1" />
                    </div>

                    <!-- Remember me dan lupa password -->
                    <div class="flex items-center justify-between pt-1 text-xs">
                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-gray-600 underline transition hover:text-black">
                                Forgot your password?
                            </a>
                        @else
                            <span></span>
                        @endif

                        <label for="remember_me" class="inline-flex cursor-pointer items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-black shadow-sm focus:ring-black">

                            <span class="ms-2 text-xs text-gray-500">
                                Remember me
                            </span>
                        </label>
                    </div>

                    <!-- Tombol login -->
                    <button
                        type="submit"
                        class="login-submit mt-1 w-full rounded bg-black py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                        Sign in
                    </button>
                </form>

                <!-- Login dengan Google -->
                @if (Route::has('google.redirect'))
                    <div class="login-divider relative my-3 flex items-center justify-center">
                        <div class="w-full border-t border-gray-200"></div>
                        <span class="absolute bg-white px-3 text-xs text-gray-400">
                            Or
                        </span>
                    </div>

                    <a
                        href="{{ route('google.redirect') }}"
                        class="flex w-full items-center justify-center gap-2 rounded border border-gray-300 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                        <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                        </svg>

                        <span>Continue with Google</span>
                    </a>
                @endif

                <!-- Link ke halaman register -->
                <p class="pt-2 text-center text-xs text-gray-500">
                    Belum punya akun?
                    <a
                        href="{{ route('register') }}"
                        class="font-bold text-black underline transition hover:text-gray-800">
                        Register di sini
                    </a>
                </p>

            </div>
        </section>
    </main>

    <!-- Script untuk menampilkan atau menyembunyikan password -->
    <script>
        function togglePassword(fieldId, iconId) {
            const inputField = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (inputField.type === 'password') {
                inputField.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                inputField.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>

</html>
