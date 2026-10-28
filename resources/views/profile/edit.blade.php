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

        <!-- 1. BAGIAN KIRI: BANNER GAMBAR & BRANDING -->
        <div class="w-full lg:w-1/2 h-64 lg:h-auto relative bg-cover bg-center p-6 lg:p-12 flex flex-col justify-between shrink-0 lg:sticky lg:top-0 lg:max-h-screen"
            style="background-image: url('{{ asset('images/login.jpg') }}');">

            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Logo Top Left -->
            <div class="relative z-10">
                <a href="{{ url('/') }}"
                    class="text-xl lg:text-2xl font-serif font-bold text-white tracking-widest uppercase hover:opacity-80 transition">
                    ASYART.MERCH
                </a>
            </div>

            <!-- Teks Bottom Left -->
            <div class="relative z-10 text-white max-w-lg space-y-2 lg:space-y-4 my-auto lg:my-0">
                <h2 class="text-2xl sm:text-3xl lg:text-5xl font-serif italic leading-tight">
                    Account Settings
                </h2>
                <p class="hidden sm:block text-xs lg:text-sm text-gray-200 opacity-90 leading-relaxed">
                    Update your personal details, manage your password, sign out from your session, or control your
                    account preferences.
                </p>
            </div>
        </div>

        <!-- 2. BAGIAN KANAN: FORM PROFILE SETTINGS -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 md:p-12 bg-white overflow-y-auto">
            <div class="w-full max-w-md mx-auto space-y-8">

                <!-- Header & Button Kembali ke Toko -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-serif font-medium">My Profile</h3>
                        <p class="text-xs text-gray-500">Manage your account information</p>
                    </div>
                    <a href="{{ url('/') }}"
                        class="text-xs font-medium border border-black px-3 py-1.5 rounded hover:bg-black hover:text-white active:scale-95 transition">
                        Back to Shop
                    </a>
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

                        <!-- Name -->
                        <div class="space-y-1">
                            <label for="name" class="block text-xs text-gray-600 font-medium">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                                required autofocus autocomplete="name"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-xs text-gray-600 font-medium">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                required autocomplete="username"
                                class="w-full border border-gray-300 rounded px-3.5 py-2.5 text-sm focus:outline-none focus:border-black transition">
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
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

                <!-- SECTION 3: LOGOUT / SIGN OUT -->
                <div class="space-y-3">
                    <h4 class="text-xs font-semibold font-serif text-gray-900 uppercase tracking-wider">3. Account
                        Session</h4>
                    <p class="text-xs text-gray-500">
                        Done managing your profile? You can log out from your active account session here.
                    </p>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="border border-red-600 text-red-600 hover:bg-red-600 hover:text-white text-xs px-5 py-2.5 rounded font-medium active:scale-95 transition duration-200">
                            Logout / Sign Out
                        </button>
                    </form>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- SECTION 4: DELETE ACCOUNT -->
                <div class="space-y-4 pb-8">
                    <h4 class="text-xs font-semibold font-serif text-red-600 uppercase tracking-wider">4. Delete Account
                    </h4>
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
