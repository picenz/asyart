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
<form action="{{ route('admin.account.profile.update') }}" method="POST" class="space-y-4">                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
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

<form action="{{ route('admin.account.password.update') }}" method="POST" class="space-y-4">                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Current password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password"
    placeholder="Enter current password"
    class="w-full px-3 py-2 pr-10 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-black transition"
    required>
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


        <!-- ALERT NOTIFIKASI -->
        @if (session('success'))
            <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- SECTION 3: ACTIVE DEVICES / SESSIONS -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Active Devices & Sessions</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">
                        Manage devices currently signed in to your admin account. Remove any unfamiliar device immediately.
                    </p>
                </div>

                @if ($sessions->where('is_current_device', false)->count() > 0)
                    <form action="{{ route('admin.account.devices.destroy-others') }}" method="POST"
                        onsubmit="return confirm('Keluarkan semua perangkat lain yang sedang login?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-3.5 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                            Sign out of all other devices
                        </button>
                    </form>
                @endif
            </div>

            <div class="divide-y divide-gray-100 border-t border-gray-100">
                @forelse ($sessions as $session)
                    <div class="py-4 first:pt-4 last:pb-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <!-- Icon Desktop / Mobile -->
                            <div class="p-2 rounded-lg bg-gray-100 text-gray-700 mt-0.5">
                                @if ($session->is_mobile)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-semibold text-gray-900">
                                        {{ $session->browser }} on {{ $session->platform }}
                                    </p>
                                    @if ($session->is_current_device)
                                        <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full">
                                            This device
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    IP: {{ $session->ip_address }} ·
                                    @if ($session->is_current_device)
                                        <span class="text-emerald-600 font-medium">Active now</span>
                                    @else
                                        Last active {{ $session->last_active }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            @if ($session->is_current_device)
                                <span class="text-[11px] text-gray-400 font-medium">Current session</span>
                            @else
                                <form action="{{ route('admin.account.devices.destroy', $session->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus dan keluarkan sesi perangkat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-white border border-red-200 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-50 transition">
                                        Remove device
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-xs text-gray-500">
                        Belum ada sesi aktif yang tercatat. Pastikan <code>SESSION_DRIVER=database</code> di file <code>.env</code>.
                    </p>
                @endforelse
            </div>
        </div>

    </div>
@endsection