<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApparelController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

// Katalog publik
Route::get('/', [ApparelController::class, 'index'])->name('home');
Route::get('/search', [ApparelController::class, 'search'])->name('search.page');
Route::get('/product/{id}', [ApparelController::class, 'show'])->name('product.detail');

// Customer harus login untuk cart dan checkout

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/bag', [CartController::class, 'index'])
        ->name('cart.page');

    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

Route::get('/cart/count', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'count' => \App\Models\CartItem::where(
            'user_id',
            $request->user()->id
        )->count(),
    ]);
})->name('cart.count');

    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])
        ->name('cart.items.update');

    Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove'])
        ->name('cart.items.remove');

    Route::delete('/cart/clear', [CartController::class, 'clear'])
        ->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout');

Route::post('/checkout/process', [CheckoutController::class, 'store'])
    ->name('checkout.process');

Route::get('/payment', [CheckoutController::class, 'paymentPage'])
    ->name('payment.page');

});
// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.orders'))->name('admin.dashboard');
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
});

// Redirect dashboard berdasarkan role
Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => abort(403),
    };
})->name('dashboard');


 // Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Riwayat pesanan customer
    Route::get('/profile/orders', [CustomerOrderController::class, 'index'])
        ->middleware('role:customer')
        ->name('profile.orders');

        // Saved Addresses
Route::get('/profile/addresses', function () {
    return view('profile.addresses');
})->middleware('role:customer')->name('profile.addresses');
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('guest')->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

require __DIR__.'/auth.php';
