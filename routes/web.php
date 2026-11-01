<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApparelController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Orders;

// --- RUTE UTAMA E-COMMERCE (APPAREL) ---
Route::get('/', [ApparelController::class, 'index'])->name('home');
Route::get('/search', [ApparelController::class, 'search'])->name('search.page');
Route::get('/bag', [ApparelController::class, 'cart'])->name('cart.page');
Route::get('/product/{id}', [ApparelController::class, 'show'])->name('product.detail');

// --- RUTE CHECKOUT & PAYMENT (Wajib Login / Auth) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.process');

    // Rute Payment
    Route::get('/payment', function () {
        $shippingCost = (int) session('shipping_cost', 0);
        $deliveryMethod = session('delivery_method', 'Standard delivery');
        return view('payment', compact('shippingCost', 'deliveryMethod'));
    })->name('payment.page');

    Route::get('/payment/expired', function () {
        return view('payment-expired');
    })->name('payment.expired');

    // Rute Payment Success & Simpan ke Database
    Route::any('/payment/success', function (Request $request) {
        $addressData = session('address_data');
        if (!$addressData) {
            return redirect()->route('home');
        }

        $shippingCost = (int) session('shipping_cost', 0);
        $deliveryMethod = session('delivery_method', 'Standard delivery');

        // Ambil data keranjang dari input form POST atau fallback ke session
        $cartDataJson = $request->input('cart_data') ?? session('cart_data', '[]');
        $cartItems = json_decode($cartDataJson, true);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $harga = (int) preg_replace('/[^0-9]/', '', $item['harga']);
            $subtotal += $harga * $item['qty'];
        }

        $totalPrice = $subtotal + $shippingCost;
        $orderNumber = session('order_number', 'RN-' . rand(10000, 99999));

        Orders::create([
            'user_id' => auth()->id(),
            'order_number' => $orderNumber,
            'status' => 'Processing',
            'total_price' => $totalPrice,
            'delivery_method' => $deliveryMethod,
            'first_name' => $addressData['first_name'],
            'last_name' => $addressData['last_name'],
            'email' => $addressData['email'],
            'phone' => $addressData['phone'],
            'address' => $addressData['address'],
            'city' => $addressData['city'],
            'postal_code' => $addressData['postal_code'],
            'province' => $addressData['province'],
            'items_json' => $cartDataJson,
        ]);

        return view('payment-success');
    })->name('payment.success');
});

// --- RUTE DASHBOARD & AUTH (LARAVEL BREEZE) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        abort_unless(auth()->user()->role === 'admin', 403);
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');

    Route::get('/customer/dashboard', function () {
        abort_unless(auth()->user()->role === 'customer', 403);
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

// --- RUTE PROFILE USER, ORDERS, & ADDRESSES ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{id}', [ProfileController::class, 'orderDetail'])->name('profile.order.detail');

    Route::get('/profile/addresses', function () {
        return view('addresses');
    })->name('profile.addresses');
});

// --- RUTE LOGOUT MANUAL ---
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// --- REQUIRE AUTH ROUTES BREEZE ---
require __DIR__ . '/auth.php';
