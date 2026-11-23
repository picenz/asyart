<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = $request->session()->getId();

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($currentSessionId) {
                $agent = $this->parseUserAgent($session->user_agent ?? '');

                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address ?? 'Unknown IP',
                    'browser' => $agent['browser'],
                    'platform' => $agent['platform'],
                    'is_mobile' => $agent['is_mobile'],
                    'is_current_device' => $session->id === $currentSessionId,
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });

        return view('admin.account', compact('user', 'sessions'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function destroyDevice(Request $request, string $sessionId)
    {
        $user = Auth::user();

        if ($sessionId === $request->session()->getId()) {
            return back()->with('error', 'Tidak dapat menghapus perangkat yang sedang digunakan saat ini.');
        }

        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        if (!$deleted) {
            return back()->with('error', 'Perangkat tidak ditemukan atau sudah keluar.');
        }

        return back()->with('success', 'Perangkat berhasil dikeluarkan (logged out).');
    }

    public function destroyOtherDevices(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = $request->session()->getId();

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        return back()->with('success', 'Semua perangkat lain berhasil dikeluarkan.');
    }

    private function parseUserAgent(string $userAgent): array
    {
        $platform = 'Unknown OS';
        $browser = 'Unknown Browser';
        $isMobile = false;

        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
            $isMobile = true;
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
            $isMobile = true;
        } elseif (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/opr|opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        }

        return [
            'platform' => $platform,
            'browser' => $browser,
            'is_mobile' => $isMobile,
        ];
    }
}