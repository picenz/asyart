<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Auth\LoginOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        $upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lower = 'abcdefghijkmnopqrstuvwxyz';
        $numbers = '23456789';

        $captcha = $upper[random_int(0, strlen($upper) - 1)]
            . $lower[random_int(0, strlen($lower) - 1)]
            . $numbers[random_int(0, strlen($numbers) - 1)];

        $all = $upper . $lower . $numbers;

        for ($i = 0; $i < 3; $i++) {
            $captcha .= $all[random_int(0, strlen($all) - 1)];
        }

        $captcha = str_shuffle($captcha);

        session(['login_captcha' => $captcha]);

        return view('auth.login', compact('captcha'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $emailInput = Str::lower((string) $request->input('email', ''));
        $emailIpKey = 'login-attempt:' . $emailInput . '|' . $request->ip();
        $ipKey      = 'login-ip-attempt:' . $request->ip();
        $maxAttempts = 5;
        $decaySeconds = 60; // Ban 1 menit (60 detik)

        // 1. Cek apakah sedang dalam masa ban 1 menit
        if (RateLimiter::tooManyAttempts($emailIpKey, $maxAttempts) || RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
            $seconds = max(
                RateLimiter::availableIn($emailIpKey),
                RateLimiter::availableIn($ipKey)
            );

            return back()
                ->with('ban_seconds', $seconds)
                ->withErrors(['email' => "Terlalu banyak percobaan gagal (5x). Silakan tunggu {$seconds} detik lagi."])
                ->withInput($request->only('email'));
        }

        // 2. Validasi CAPTCHA
        $request->validate([
            'captcha' => ['required', 'string'],
        ]);

        $expected = session('login_captcha', '');
        $provided = $request->input('captcha', '');

        session()->forget('login_captcha');

        if ($expected === '' || !hash_equals($expected, $provided)) {
            RateLimiter::hit($emailIpKey, $decaySeconds);
            RateLimiter::hit($ipKey, $decaySeconds);

            if (RateLimiter::tooManyAttempts($emailIpKey, $maxAttempts) || RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
                return back()
                    ->with('ban_seconds', $decaySeconds)
                    ->withErrors(['email' => "Anda telah 5x salah melakukan percobaan login. Silakan tunggu {$decaySeconds} detik."])
                    ->withInput($request->only('email'));
            }

            $remaining = max(0, $maxAttempts - RateLimiter::attempts($emailIpKey));

            return back()
                ->withErrors(['captcha' => "Kode CAPTCHA salah. Sisa percobaan: {$remaining}x lagi sebelum diblokir 1 menit."])
                ->withInput($request->only('email'));
        }

        // 3. Validasi Kredensial Email & Password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (
            !$user ||
            !Hash::check($credentials['password'], $user->password) ||
            !in_array($user->role, ['admin', 'customer'], true)
        ) {
            RateLimiter::hit($emailIpKey, $decaySeconds);
            RateLimiter::hit($ipKey, $decaySeconds);

            if (RateLimiter::tooManyAttempts($emailIpKey, $maxAttempts) || RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
                return back()
                    ->with('ban_seconds', $decaySeconds)
                    ->withErrors(['email' => "Anda telah 5x salah melakukan percobaan login. Silakan tunggu {$decaySeconds} detik."])
                    ->withInput($request->only('email'));
            }

            $remaining = max(0, $maxAttempts - RateLimiter::attempts($emailIpKey));

            return back()
                ->withErrors(['email' => "Email atau password salah. Sisa percobaan: {$remaining}x lagi sebelum diblokir 1 menit."])
                ->withInput($request->only('email'));
        }

        // Berhasil -> reset hitungan gagal
        RateLimiter::clear($emailIpKey);
        RateLimiter::clear($ipKey);

        $request->session()->regenerate();

        $otpController = app(LoginOtpController::class);

        try {
            $otpController->sendOtp($request, $user);
        } catch (\Throwable $e) {
            $request->session()->forget([
                'login_otp_user_id',
                'login_otp_hash',
                'login_otp_expires_at',
            ]);

            report($e);

            return back()
                ->withErrors(['email' => 'Email OTP gagal dikirim. Coba lagi.'])
                ->withInput($request->only('email'));
        }

        return redirect()->route('login.otp');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}