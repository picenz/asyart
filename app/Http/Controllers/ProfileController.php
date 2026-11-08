<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Orders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
            ->paginate(5);

        return view('profile.orders', [
            'user' => $request->user(),
            'orders' => $orders,
        ]);
    }

    public function addresses(Request $request): View
    {
        $addresses = Address::where('user_id', $request->user()->id)
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('profile.addresses', [
            'user' => $request->user(),
            'addresses' => $addresses,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->name = trim($data['first_name'] . ' ' . $data['last_name']);
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
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

    public function orderDetail($id): View
    {
        $order = Orders::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('profile.order-detail', compact('order'));
    }

    // Tambah alamat baru
    public function storeAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:100'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user, $data, $request) {
            $isFirstAddress = !$user->addresses()->exists();

            if ($isFirstAddress) {
                $data['is_default'] = true;
            } else {
                $data['is_default'] = $request->boolean('is_default');
            }

            if ($data['is_default']) {
                $user->addresses()->update(['is_default' => false]);
            }

            $user->addresses()->create($data);
        });

        return redirect()->route('profile.addresses')
            ->with('status', 'address-created');
    }

    // Edit alamat
    public function updateAddress(Request $request, $id): RedirectResponse
    {
        $address = $request->user()->addresses()->findOrFail($id);

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:100'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $address, $data) {
            if ($request->boolean('is_default')) {
                $request->user()->addresses()->update(['is_default' => false]);
                $data['is_default'] = true;
            }

            $address->update($data);
        });

        return redirect()->route('profile.addresses')
            ->with('status', 'address-updated');
    }

    // Hapus alamat
    public function deleteAddress(Request $request, $id): RedirectResponse
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;

        DB::transaction(function () use ($request, $address, $wasDefault) {
            $address->delete();

            if ($wasDefault) {
                $nextAddress = $request->user()->addresses()->latest()->first();

                if ($nextAddress) {
                    $nextAddress->update(['is_default' => true]);
                }
            }
        });

        return redirect()->route('profile.addresses')
            ->with('status', 'address-deleted');
    }

    // Jadikan alamat utama
    public function setDefaultAddress(Request $request, $id): RedirectResponse
    {
        $address = $request->user()->addresses()->findOrFail($id);

        DB::transaction(function () use ($request, $address) {
            $request->user()->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return redirect()->route('profile.addresses')
            ->with('status', 'address-default-updated');
    }
}
