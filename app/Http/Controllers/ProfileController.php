<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Orders;
use App\Models\Address;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function orders(Request $request): View
    {
        $orders = Orders::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('profile.orders', [
            'user' => $request->user(),
            'orders' => $orders,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        $user->name = $validated['first_name'] . ' ' . $validated['last_name'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

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

    public function orderDetail($id)
    {
        $order = Orders::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (empty($order->order_number)) {
            $order->order_number = 'RN-' . $order->id;
        }

        return view('profile.order-detail', compact('order'));
    }

    // --- MANAJEMEN ALAMAT ---

    // Menampilkan daftar alamat user yang sedang login
    public function addresses(): View
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('addresses', compact('addresses'));
    }

    public function create()
    {
        return view('profile.addresses-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'address_name' => 'required|string|max:255',
            'full_address' => 'required|string',
            'city'         => 'required|string',
            'postal_code'  => 'required|string',
            'phone'        => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        Address::create($validated);

        return redirect()->route('profile.addresses')->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    public function editAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('profile.edit-address', compact('address'));
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'address_name' => 'required|string|max:255',
            'full_address' => 'required|string',
            'city'         => 'required|string',
            'postal_code'  => 'required|string',
            'phone'        => 'required|string',
        ]);

        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $address->update($request->only([
            'address_name',
            'full_address',
            'city',
            'postal_code',
            'phone'
        ]));

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil diperbarui!');
    }

    // --- TAMBAHAN METHOD HAPUS ALAMAT ---
    public function destroyAddress($id): RedirectResponse
    {
        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $address->delete();

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil dihapus!');
    }
}
