<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - ASYART.MERCH</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Container Utama Split Screen (Full Width) -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- 1. BAGIAN KIRI: BANNER GAMBAR, BRANDING & MENU NAVIGASI AKUN -->
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

            <!-- Teks & Menu Navigasi Akun di Tengah/Bawah Banner -->
            <div class="relative z-10 text-white max-w-lg space-y-6 my-auto lg:my-12">
                <div class="space-y-2">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif italic leading-tight">
                        Account Settings
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                        Update your personal details, manage your password, check your orders, or control your
                        preferences.
                    </p>
                </div>

                <!-- Menu Navigasi Akun (Addresses sudah dihubungkan ke rute baru) -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 space-y-2 text-xs">
                    <a href="{{ route('profile.edit') }}"
                        class="block font-semibold text-white bg-white/20 px-3 py-2 rounded transition">
                        ✓ Account details
                    </a>
                    <a href="{{ route('profile.orders') }}"
                        class="block text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded transition">
                        Orders
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

        <!-- 2. BAGIAN KANAN: FORM PROFILE SETTINGS LENGKAP -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 md:p-12 bg-white overflow-y-auto">
            <div class="w-full max-w-lg mx-auto space-y-8">

                <!-- Header Form -->
                <div class="pb-4 border-b border-gray-100">
                    <h3 class="text-2xl font-serif font-medium">My Profile</h3>
                    <p class="text-xs text-gray-500">Manage your account information and preferences</p>
                </div>

                <!-- Session Status Alert -->
                @if (session('status') === 'profile-updated')
                    <div class="bg-gray-100 border-l-4 border-black text-gray-800 p-3 text-xs rounded">
                        Profile information successfully updated.
                    </div>
                @elseif (session('status') === 'password-updated')
                    <div class="bg-gray-100 border-l-4 border-black text-gray-800 p-3 text-xs rounded">
                        Password successfully updated.
                    </div>
                @endif

                <!-- SECTION 1: PROFILE INFORMATION -->
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold font-serif text-gray-900 uppercase tracking-wider">1. Profile
                        Information</h4>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <!-- First & Last Name Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="first_name" class="block text-xs text-gray-600 font-medium">First
                                    name</label>
                                <input id="first_name" type="text" name="first_name"
                                    value="{{ old('first_name', $user->first_name ?? '') }}" required autofocus
                                    class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            </div>
                            <div class="space-y-1">
                                <label for="last_name" class="block text-xs text-gray-600 font-medium">Last name</label>
                                <input id="last_name" type="text" name="last_name"
                                    value="{{ old('last_name', $user->last_name ?? '') }}" required
                                    class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-xs text-gray-600 font-medium">Email</label>
                            <input id="email" type="email" name="email"
                                value="{{ old('email', $user->email) }}" required autocomplete="username"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <!-- Phone (Optional) -->
                        <div class="space-y-1">
                            <label for="phone" class="block text-xs text-gray-600 font-medium">Phone
                                (optional)</label>
                            <input id="phone" type="text" name="phone"
                                value="{{ old('phone', $user->phone ?? '+62 812 3456 7890') }}"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                        </div>

                        <button type="submit"
                            class="bg-black text-white text-xs px-5 py-2.5 rounded font-medium hover:bg-gray-800 active:scale-95 transition">
                            Save Changes
                        </button>
                    </form>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- SECTION 2: UPDATE PASSWORD -->
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold font-serif text-gray-900 uppercase tracking-wider">2. Update
                        Password</h4>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')

                        <!-- Current Password -->
                        <div class="space-y-1">
                            <label for="update_password_current_password"
                                class="block text-xs text-gray-600 font-medium">Current Password</label>
                            <input id="update_password_current_password" type="password" name="current_password"
                                autocomplete="current-password"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                        </div>

                        <!-- New Password -->
                        <div class="space-y-1">
                            <label for="update_password_password" class="block text-xs text-gray-600 font-medium">New
                                Password</label>
                            <input id="update_password_password" type="password" name="password"
                                autocomplete="new-password"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1">
                            <label for="update_password_password_confirmation"
                                class="block text-xs text-gray-600 font-medium">Confirm Password</label>
                            <input id="update_password_password_confirmation" type="password"
                                name="password_confirmation" autocomplete="new-password"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                        </div>

                        <button type="submit"
                            class="bg-black text-white text-xs px-5 py-2.5 rounded font-medium hover:bg-gray-800 active:scale-95 transition">
                            Update Password
                        </button>
                    </form>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- SECTION 3: PREFERENCES -->
                <div class="space-y-3">
                    <h4 class="text-xs font-semibold font-serif text-gray-900 uppercase tracking-wider">3. Preferences
                    </h4>
                    <div class="space-y-2.5 text-xs">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" checked class="w-4 h-4 accent-black rounded border-gray-300">
                            <span class="text-gray-700">Email me about new arrivals and private sales</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" class="w-4 h-4 accent-black rounded border-gray-300">
                            <span class="text-gray-700">Send me order updates by SMS</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- SECTION 4: DELETE ACCOUNT -->
                <div class="space-y-4 pb-8">
                    <h4 class="text-xs font-semibold font-serif text-red-600 uppercase tracking-wider">4. Delete
                        Account</h4>
                    <p class="text-xs text-gray-500">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?');"
                        class="space-y-3">
                        @csrf
                        @method('delete')

                        <div class="space-y-1">
                            <label for="delete_password" class="block text-xs text-gray-600 font-medium">Enter
                                Password to Confirm</label>
                            <input id="delete_password" type="password" name="password" placeholder="Password"
                                required
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-red-600 transition">
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                        </div>

                        <button type="submit"
                            class="bg-red-600 text-white text-xs px-5 py-2.5 rounded font-medium hover:bg-red-700 active:scale-95 transition">
                            Delete Account
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
