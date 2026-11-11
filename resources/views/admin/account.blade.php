@extends('layouts.admin', ['title' => 'Admin - Account'])

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 pb-12">

        <!-- PAGE HEADER -->
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Account</h1>
            <p class="text-xs text-gray-500 mt-0.5">Your admin profile and password.</p>
        </div>

        <!-- SECTION 1: PROFILE -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Profile</h2>
            </div>

            <!-- Form Profile (Full name & Email) -->
            <form action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full name</label>
                        <input type="text" name="name" value="Ayu Lestari"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="admin@finness.com"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition shadow-sm">
                        Save profile
                    </button>
                </div>
            </form>
        </div>

        <!-- SECTION 2: CHANGE PASSWORD -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Change password</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Use at least 8 characters with a mix of letters and numbers.</p>
            </div>

            <form action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Current password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" value="••••••••••••"
                            class="w-full px-3 py-2 pr-10 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <!-- Icon Mata Buka -->
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Icon Mata Tutup -->
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- New Password -->
                    <div x-data="{ show: false }">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">New password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••••••"
                                class="w-full px-3 py-2 pr-10 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div x-data="{ show: false }">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm new password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                placeholder="Re-enter new password"
                                class="w-full px-3 py-2 pr-10 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition shadow-sm">
                        Update password
                    </button>
                </div>
            </form>
        </div>

        <!-- SECTION 3: SESSIONS -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Sessions</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">You're signed in on this device. Sign out everywhere if you
                    think someone else has access.</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                <div>
                    <p class="text-xs font-semibold text-gray-900">Chrome on macOS · Jakarta</p>
                    <p class="text-[11px] text-emerald-600 font-medium flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active now
                    </p>
                </div>
                <div>
                    <form action="#" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-3.5 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                            Sign out of all devices
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
