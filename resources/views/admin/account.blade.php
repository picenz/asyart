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

            <!-- Avatar & Nama Admin -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center font-bold text-sm">
                        AL
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Ayu Lestari</h3>
                        <p class="text-xs text-gray-400">Admin · since Jan 2026</p>
                    </div>
                </div>
                <div>
                    <button type="button"
                        class="px-3.5 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        Change photo
                    </button>
                </div>
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

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Current password</label>
                    <input type="password" name="current_password" value="••••••••••••"
                        class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">New password</label>
                        <input type="password" name="password" placeholder="••••••••••••"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm new password</label>
                        <input type="password" name="password_confirmation" placeholder="Re-enter new password"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition">
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
