<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-xl font-bold">Verifikasi OTP</h1>
        <p class="mt-2 text-sm text-gray-600">
            Masukkan kode 6 digit yang dikirim ke email kamu.
            Kode berlaku selama 5 menit.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.otp.verify') }}">
        @csrf

        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700">
                Kode OTP
            </label>

            <input
                id="otp"
                name="otp"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                pattern="[0-9]{6}"
                required
                autofocus
                class="mt-1 block w-full rounded-md border-gray-300"
            >

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="mt-4 w-full rounded-md bg-black px-4 py-3 text-white hover:bg-gray-800"
        >
            Verifikasi dan Login
        </button>
    </form>

    <form method="POST" action="{{ route('login.otp.resend') }}" class="mt-3">
        @csrf

        <button
            type="submit"
            class="w-full rounded-md border border-gray-300 px-4 py-3 hover:bg-gray-50"
        >
            Kirim Ulang OTP
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 underline">
            Kembali ke login
        </a>
    </div>
</x-guest-layout>

