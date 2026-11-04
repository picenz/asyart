<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginOtpController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('login_otp_user_id')) {
            return redirect()->route('login');
        }

        if ($request->session()->get('login_otp_expires_at', 0) < time()) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'OTP kedaluwarsa. Silakan login lagi.']);
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('login_otp_user_id');
        $key = 'otp-attempts:' . $request->session()->getId();

        if (!$userId || $request->session()->get('login_otp_expires_at', 0) < time()) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'OTP kedaluwarsa. Silakan login lagi.']);
        }

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Terlalu banyak percobaan. Silakan login lagi.']);
        }

        RateLimiter::hit($key, 300);

        $storedHash = $request->session()->get('login_otp_hash', '');

        if (!$storedHash || !Hash::check($request->otp, $storedHash)) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah.',
            ]);
        }

        $user = User::find($userId);

        if (!$user || !in_array($user->role, ['admin', 'customer'], true)) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun tidak valid.']);
        }

        $this->clearOtp($request);
        RateLimiter::clear($key);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('login_otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $key = 'otp-resend:' . $request->session()->getId();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors([
                'otp' => 'Terlalu banyak meminta OTP. Coba lagi nanti.',
            ]);
        }

        RateLimiter::hit($key, 300);

        $user = User::find($userId);

        if (!$user) {
            $this->clearOtp($request);

            return redirect()->route('login');
        }

        $this->sendOtp($request, $user);

        return redirect()->route('login.otp')
            ->with('status', 'Kode OTP baru sudah dikirim ke email kamu.');
    }

    public function sendOtp(Request $request, User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $request->session()->put([
            'login_otp_user_id' => $user->id,
            'login_otp_hash' => Hash::make($otp),
            'login_otp_expires_at' => time() + 300,
        ]);

        Mail::raw(
            "Kode OTP login ASYART kamu: {$otp}\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Login ASYART');
            }
        );
    }

    private function clearOtp(Request $request): void
    {
        $request->session()->forget([
            'login_otp_user_id',
            'login_otp_hash',
            'login_otp_expires_at',
        ]);
    }
}
