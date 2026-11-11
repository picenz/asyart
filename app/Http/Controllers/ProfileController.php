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

   public function create(): View
{
    return view('profile.addresses-create');
}

public function storeAddress(Request $request): RedirectResponse
{
    $data = $request->validate([
        'recipient_name' => ['required', 'string', 'max:255'],
        'address_label' => ['required', 'string', 'max:50'],
        'street_address' => ['required', 'string'],
        'city' => ['required', 'string', 'max:100'],
        'province' => ['required', 'string', 'max:100'],
        'postal_code' => ['required', 'string', 'max:10'],
        'country' => ['required', 'string', 'max:100'],
        'phone_number' => ['required', 'string', 'max:20'],
        'is_default' => ['nullable', 'boolean'],
    ]);

    $user = $request->user();

    DB::transaction(function () use ($data, $user) {
        $isDefault = !empty($data['is_default'])
            || !$user->addresses()->exists();

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'recipient_name' => $data['recipient_name'],
            'label' => $data['address_label'],
            'phone' => $data['phone_number'],
            'address' => $data['street_address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'is_default' => $isDefault,
        ]);
    });

    return redirect()->route('profile.addresses')
        ->with('success', 'Address saved successfully.');
}

public function updateAddress(Request $request, Address $address): RedirectResponse
{
    $address = $request->user()->addresses()->findOrFail($address->id);

    $data = $request->validate([
        'recipient_name' => ['required', 'string', 'max:255'],
        'address_label' => ['required', 'string', 'max:50'],
        'street_address' => ['required', 'string'],
        'city' => ['required', 'string', 'max:100'],
        'province' => ['required', 'string', 'max:100'],
        'postal_code' => ['required', 'string', 'max:10'],
        'country' => ['required', 'string', 'max:100'],
        'phone_number' => ['required', 'string', 'max:20'],
        'is_default' => ['nullable', 'boolean'],
    ]);

    DB::transaction(function () use ($data, $address, $request) {
        $isDefault = !empty($data['is_default']);

        if ($isDefault) {
            $request->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update([
            'recipient_name' => $data['recipient_name'],
            'label' => $data['address_label'],
            'phone' => $data['phone_number'],
            'address' => $data['street_address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'is_default' => $isDefault,
        ]);
    });

    return redirect()->route('profile.addresses')
        ->with('success', 'Address updated successfully.');
}

public function deleteAddress(Request $request, Address $address): RedirectResponse
{
    $address = $request->user()->addresses()->findOrFail($address->id);

    DB::transaction(function () use ($address, $request) {
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $request->user()->addresses()
                ->latest()
                ->first()
                ?->update(['is_default' => true]);
        }
    });

    return redirect()->route('profile.addresses')
        ->with('success', 'Address deleted successfully.');
}

public function setDefaultAddress(Request $request, Address $address): RedirectResponse
{
    $address = $request->user()->addresses()->findOrFail($address->id);

    DB::transaction(function () use ($request, $address) {
        $request->user()->addresses()
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);
    });

    return redirect()->route('profile.addresses')
        ->with('success', 'Default address updated successfully.');
}
    public function orderDetail($id)
    {
        $order = Orders::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('profile.order-detail', compact('order'));
    }
}
