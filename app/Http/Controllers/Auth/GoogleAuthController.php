<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Login Google gagal. Silakan coba lagi.',
                ]);
        }

        $email = $googleUser->getEmail();

        if (!$email || !$googleUser->getId()) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google tidak memberikan informasi akun yang diperlukan.',
                ]);
        }

        // Hanya terima email yang telah diverifikasi Google.
        $googleData = $googleUser->user;

        if (($googleData['email_verified'] ?? false) !== true) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Email Google belum terverifikasi.',
                ]);
        }


$user = User::where('email', $email)->first();

if (!$user) {
    $nameParts = preg_split('/\s+/', trim($googleUser->getName() ?? 'Customer'), 2);

    $user = DB::transaction(function () use ($email, $nameParts) {
        $newUser = User::create([
            'name' => implode(' ', $nameParts),
            'first_name' => $nameParts[0] ?? 'Customer',
            'last_name' => $nameParts[1] ?? '',
            'email' => $email,
            'password' => bcrypt(Str::random(40)),
            'role' => 'customer',
        ]);

        // Pastikan email Google yang sudah terverifikasi
        // juga tercatat terverifikasi di ASYART.
        $newUser->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return $newUser;
    });
} elseif (!$user->email_verified_at) {
    // Akun lama yang emailnya cocok dengan Google
    // terverifikasi, tandai sebagai verified.
    $user->forceFill([
        'email_verified_at' => now(),
    ])->save();
}


        // Jangan pernah menaikkan role berdasarkan data Google.
        if (!in_array($user->role, ['admin', 'customer'], true)) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Role akun tidak valid.',
                ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
