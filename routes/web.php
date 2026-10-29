<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApparelController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --- RUTE UTAMA E-COMMERCE (APPAREL) ---
Route::get('/', [ApparelController::class, 'index'])->name('home');
Route::get('/search', [ApparelController::class, 'search'])->name('search.page');
Route::get('/bag', [ApparelController::class, 'cart'])->name('cart.page');
Route::get('/product/{id}', [ApparelController::class, 'show'])->name('product.detail');

// --- RUTE CHECKOUT & PAYMENT ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.process');

Route::get('/payment', function () {
    return view('payment');
})->name('payment.page');
Route::get('/payment/expired', function () {
    return view('payment-expired');
})->name('payment.expired');
Route::get('/payment/success', function () {
    return view('payment-success');
})->name('payment.success');

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

    // RUTE ADMIN KELOLA PESANAN MASUK
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

    // Rute halaman riwayat pesanan (Orders)
    Route::get('/profile/orders', function () {
        return view('orders');
    })->name('profile.orders');

    // Rute halaman daftar alamat (Addresses)
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
