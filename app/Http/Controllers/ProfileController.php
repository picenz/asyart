<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Orders; // <-- Tambahan use Model Orders biar gak error class not found

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's order history.
     */
    public function orders(Request $request): View
    {
        // Ambil data pesanan dari database khusus untuk user yang sedang login
        $orders = Orders::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('profile.orders', [
            'user' => $request->user(),
            'orders' => $orders,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Ambil data tervalidasi
        $validated = $request->validated();

        // Simpan data first_name, last_name, dan email
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];

        // Simpan nomor telepon jika ada
        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        // Gabungkan First & Last Name ke kolom 'name' default Laravel biar sinkron
        $user->name = $validated['first_name'] . ' ' . $validated['last_name'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display detailed status and invoice of a specific order.
     */
    public function orderDetail($id)
    {
        $order = Orders::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Pastikan order_number selalu ada (fallback otomatis pakai RN-ID jika kosong)
        if (empty($order->order_number)) {
            $order->order_number = 'RN-' . $order->id;
        }

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Menampilkan halaman form tambah alamat baru
     */
    public function create()
    {
        return view('profile.addresses-create');
    }

    /**
     * Menyimpan data alamat baru dari form ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'address_label'  => 'required|string|max:100',
            'street_address' => 'required|string',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'phone_number'   => 'required|string|max:20',
        ]);

        // Cek apakah user mencentang 'set as default'
        if ($request->has('is_default')) {
            // Jika ada alamat lain yang default, ubah jadi false dulu (opsional sesuai kebutuhan tabel DB)
            if (method_exists($request->user(), 'addresses')) {
                $request->user()->addresses()->update(['is_default' => false]);
            }
            $validated['is_default'] = true;
        } else {
            $validated['is_default'] = false;
        }

        // Simpan ke database melalui relasi user (pastikan model User punya relasi addresses)
        if (method_exists($request->user(), 'addresses')) {
            $request->user()->addresses()->create($validated);
        }

        return redirect()->route('profile.addresses')->with('success', 'Alamat baru berhasil ditambahkan!');
    }
}
