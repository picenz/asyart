<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Auth\LoginOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    // Pastikan ada huruf besar, huruf kecil, dan angka
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
    $request->validate([
        'captcha' => ['required', 'string'],
    ]);

    $expected = session('login_captcha', '');
    $provided = $request->input('captcha', '');

    session()->forget('login_captcha');

    if ($expected === '' || !hash_equals($expected, $provided)) {
        return back()
            ->withErrors(['captcha' => 'Kode CAPTCHA salah. Silakan coba lagi.'])
            ->withInput($request->only('email'));
    }

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $key = 'login:' . Str::lower($credentials['email'])
        . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        return back()
            ->withErrors(['email' => 'Terlalu banyak percobaan login. Coba lagi nanti.'])
            ->withInput($request->only('email'));
    }

    $user = User::where('email', $credentials['email'])->first();

    if (
        !$user ||
        !Hash::check($credentials['password'], $user->password) ||
        !in_array($user->role, ['admin', 'customer'], true)
    ) {
        RateLimiter::hit($key, 60);

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    RateLimiter::clear($key);

    // Belum login. Siapkan session untuk verifikasi OTP.
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // <-- Pastikan ini mengarah ke root '/'
    }
}
