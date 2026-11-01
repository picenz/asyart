<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApparelController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

// ========================================
// PUBLIC ROUTES (BISA DIAKSES GUEST)
// ========================================

Route::get('/', [ApparelController::class, 'index'])
    ->name('home');

Route::get('/search', [ApparelController::class, 'search'])
    ->name('search.page');

Route::get('/bag', [ApparelController::class, 'cart'])
    ->name('cart.page');

Route::get('/product/{id}', [ApparelController::class, 'show'])
    ->name('product.detail');


// ========================================
// ROUTES KHUSUS CUSTOMER YANG SUDAH LOGIN
// ========================================

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout');

    Route::post('/checkout/process', [CheckoutController::class, 'store'])
        ->name('checkout.process');
    Route::get('/payment', function () {
        $orderId = session('order_summary.order_id');

        $order = \App\Models\Order::with('items')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('payment', compact('order'));
    })->name('payment.page');
    Route::get('/payment/expired', function () {
        return view('payment-expired');
    })->name('payment.expired');

    Route::get('/payment/success', function () {
        $orderId = session('order_summary.order_id');

        $order = \App\Models\Order::with('items')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('payment-success', compact('order'));
    })->name('payment.success');

    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});


// ========================================
// ROUTES KHUSUS ADMIN
// ========================================

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders');
});


// ========================================
// DASHBOARD PENGARAH BERDASARKAN ROLE
// ========================================

Route::middleware('auth')->get('/dashboard', function () {

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()->role === 'customer') {
        return redirect()->route('customer.dashboard');
    }

    abort(403);
})->name('dashboard');


// ========================================
// PROFILE (ADMIN DAN CUSTOMER)
// ========================================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/profile/orders', [CustomerOrderController::class, 'index'])
        ->name('profile.orders');

    Route::get('/profile/addresses', function () {
        return view('addresses');
    })->name('profile.addresses');
});

Route::get('/product/{id}', [ApparelController::class, 'show'])
    ->name('product.detail');




// ========================================
// BREEZE AUTH ROUTES
// ========================================

require __DIR__ . '/auth.php';
