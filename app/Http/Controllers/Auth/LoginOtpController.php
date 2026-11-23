<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

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

        $userId = $request->session()->get('login_otp_user_id');
        $resendKey = 'otp-resend:' . $userId . '|' . $request->ip();
        $resendCooldown = RateLimiter::availableIn($resendKey);

        return view('auth.verify-otp', compact('resendCooldown'));
    }

    public function verify(Request $request)
    {
        $userId = $request->session()->get('login_otp_user_id');

        if (!$userId || $request->session()->get('login_otp_expires_at', 0) < time()) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'OTP kedaluwarsa. Silakan login lagi.']);
        }

        $otpKey       = 'otp-verify-attempt:' . $userId . '|' . $request->ip();
        $ipKey        = 'login-ip-attempt:' . $request->ip();
        $maxAttempts  = 5;
        $decaySeconds = 60; // Ban 1 menit (60 detik)

        if (RateLimiter::tooManyAttempts($otpKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($otpKey);

            return back()
                ->with('ban_seconds', $seconds)
                ->withErrors([
                    'otp' => "Terlalu banyak percobaan OTP gagal (5x). Silakan tunggu {$seconds} detik lagi.",
                ]);
        }

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $storedHash = $request->session()->get('login_otp_hash', '');

        if (!$storedHash || !Hash::check($request->otp, $storedHash)) {
            RateLimiter::hit($otpKey, $decaySeconds);
            RateLimiter::hit($ipKey, $decaySeconds);

            if (RateLimiter::tooManyAttempts($otpKey, $maxAttempts)) {
                return back()
                    ->with('ban_seconds', $decaySeconds)
                    ->withErrors([
                        'otp' => "Anda telah 5x salah memasukkan kode OTP. Akun dikunci sementara selama {$decaySeconds} detik.",
                    ]);
            }

            $remaining = max(0, $maxAttempts - RateLimiter::attempts($otpKey));

            return back()->withErrors([
                'otp' => "Kode OTP salah. Sisa percobaan: {$remaining}x lagi sebelum diblokir 1 menit.",
            ]);
        }

        $user = User::find($userId);

        if (!$user || !in_array($user->role, ['admin', 'customer'], true)) {
            $this->clearOtp($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun tidak valid.']);
        }

        $this->clearOtp($request);
        RateLimiter::clear($otpKey);
        RateLimiter::clear($ipKey);

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

        $otpKey = 'otp-verify-attempt:' . $userId . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            $seconds = RateLimiter::availableIn($otpKey);
            return back()
                ->with('ban_seconds', $seconds)
                ->withErrors([
                    'otp' => "Akun sedang diblokir sementara karena 5x salah. Tunggu {$seconds} detik lagi.",
                ]);
        }

        $resendKey = 'otp-resend:' . $userId . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($resendKey, 1)) {
            $seconds = RateLimiter::availableIn($resendKey);
            return back()->withErrors([
                'otp' => "Silakan tunggu {$seconds} detik sebelum meminta kode OTP baru.",
            ]);
        }

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

        // Set cooldown 60 detik untuk tombol Kirim Ulang OTP setiap kali OTP dikirim
        $resendKey = 'otp-resend:' . $user->id . '|' . $request->ip();
        RateLimiter::clear($resendKey);
        RateLimiter::hit($resendKey, 60);

        $htmlBody = "
        <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff;'>
            <div style='text-align: center; border-bottom: 1px solid #f3f4f6; padding-bottom: 16px; margin-bottom: 20px;'>
                <h2 style='margin: 0; letter-spacing: 3px; font-family: Georgia, serif; color: #111827;'>ASYART.MERCH</h2>
                <p style='margin: 4px 0 0; font-size: 12px; color: #6b7280;'>Verifikasi Keamanan Login</p>
            </div>
            <p style='font-size: 14px; color: #374151; line-height: 1.5;'>Halo <strong>{$user->name}</strong>,</p>
            <p style='font-size: 14px; color: #374151; line-height: 1.5;'>Gunakan kode OTP 6 digit berikut untuk menyelesaikan proses masuk ke akun ASYART.MERCH kamu:</p>
            <div style='margin: 24px 0; padding: 16px; background-color: #f9fafb; border: 1px dashed #9ca3af; border-radius: 6px; text-align: center;'>
                <span style='font-family: monospace; font-size: 28px; font-weight: bold; letter-spacing: 8px; color: #000000;'>{$otp}</span>
            </div>
            <p style='font-size: 12px; color: #6b7280; line-height: 1.5;'>Kode ini hanya berlaku selama <strong>5 menit</strong>. Demi keamanan akun, jangan pernah membagikan kode ini kepada siapa pun.</p>
            <hr style='border: none; border-top: 1px solid #f3f4f6; margin: 20px 0;'>
            <p style='font-size: 11px; color: #9ca3af; text-align: center; margin: 0;'>&copy; " . date('Y') . " ASYART.MERCH. All rights reserved.</p>
        </div>
        ";

        Mail::html($htmlBody, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode Verifikasi OTP Login - ASYART.MERCH');
        });
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