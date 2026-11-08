
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - ASYART.MERCH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex flex-col lg:flex-row min-h-screen">
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

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <a href="{{ route('home') }}"
                       class="text-xs text-gray-500 hover:text-black inline-flex items-center gap-1 transition">
                        ← Back to Home
                    </a>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-2xl md:text-3xl font-serif font-medium">
                        Welcome back to ASYART.MERCH
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed px-4">
                        A collection of cute graphic tees designed to add a touch of fun, color, and sweetness to your everyday style.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="block text-xs text-gray-600 font-medium">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-xs text-gray-600 font-medium">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                   autocomplete="current-password"
                                   class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm pr-10 focus:outline-none focus:border-black transition">
                            <button type="button" onclick="togglePassword('password', 'icon-login-pwd')"
                                    aria-label="Tampilkan atau sembunyikan password"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-black">
                                <svg id="icon-login-pwd" class="w-4 h-4" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>


<div class="space-y-2">
    <label for="captcha" class="block text-xs text-gray-600 font-medium">
        CAPTCHA
    </label>

    <div class="flex items-center gap-3">
        <div class="flex-1 bg-gray-100 border border-gray-300 rounded px-4 py-3 text-center">
            <span class="font-mono text-xl font-bold tracking-[0.3em] select-none">
                {{ $captcha }}
            </span>
        </div>

        <button type="button"
                onclick="window.location.reload()"
                class="border border-gray-300 rounded px-3 py-3 text-xs hover:bg-gray-50">
            ↻ Refresh
        </button>
    </div>

    <input id="captcha" type="text" name="captcha"
           required maxlength="6" autocomplete="off"
           placeholder="Ketik kode di atas"
           class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">

    <x-input-error :messages="$errors->get('captcha')" class="mt-1" />
</div>


                    <div class="flex items-center justify-between text-xs pt-1">
                        @if (Route::has('password.request'))
                            <a class="underline text-gray-600 hover:text-black" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @else
                            <span></span>
                        @endif

                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="rounded border-gray-300 text-black shadow-sm focus:ring-black">
                            <span class="ms-2 text-xs text-gray-500">Remember me</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-black text-white text-sm py-3 rounded font-medium hover:bg-gray-800 transition">
                        Sign in
                    </button>
                </form>

                @if (Route::has('google.redirect'))
                    <div class="relative flex items-center justify-center my-4">
                        <div class="border-t border-gray-200 w-full"></div>
                        <span class="bg-white px-3 text-xs text-gray-400 absolute">Or</span>
                    </div>

                    <a href="{{ route('google.redirect') }}"
                       class="w-full border border-gray-300 text-sm py-2.5 rounded font-medium flex items-center justify-center gap-2 hover:bg-gray-50 transition text-gray-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>
                @endif

                <p class="text-center text-xs text-gray-500 pt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold underline text-black hover:text-gray-800">
                        Register di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

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
