
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ApparelController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\QrisController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/

Route::get('/', [ApparelController::class, 'index'])->name('home');
Route::get('/search', [ApparelController::class, 'search'])->name('search');
Route::get('/product/{id}', [ApparelController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| Google authentication
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

/*
|--------------------------------------------------------------------------
| Authenticated users
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        return $request->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:customer')->group(function () {

        // Cart
        Route::get('/bag', [CartController::class, 'index'])
            ->name('cart.page');

        Route::get('/cart/count', [CartController::class, 'count'])
            ->name('cart.count');

        Route::post('/cart/add', [CartController::class, 'add'])
            ->name('cart.add');

        Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])
            ->name('cart.items.update');

        Route::patch('/cart/items/{cartItem}/variant', [CartController::class, 'updateVariant'])
            ->name('cart.items.variant');

        Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove'])
            ->name('cart.items.remove');

        Route::delete('/cart/clear', [CartController::class, 'clear'])
            ->name('cart.clear');

        // Checkout & payment
        Route::get('/checkout', [CheckoutController::class, 'index'])
            ->name('checkout');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.process');

        Route::get('/payment', [CheckoutController::class, 'paymentPage'])
            ->name('payment');

        Route::post('/payment/success', [CheckoutController::class, 'demoPaymentSuccess'])
            ->name('payment.success');

        // Orders
        Route::get('/orders', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/profile/orders', [ProfileController::class, 'orders'])
            ->name('profile.orders');

        Route::get('/profile/orders/{id}', [ProfileController::class, 'orderDetail'])
            ->name('profile.order.detail');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Saved addresses
        Route::get('/profile/addresses', [ProfileController::class, 'addresses'])
            ->name('profile.addresses');

        Route::get('/profile/addresses/create', [ProfileController::class, 'create'])
            ->name('profile.addresses.create');

        Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])
            ->name('profile.addresses.store');

        Route::patch('/profile/addresses/{address}', [ProfileController::class, 'updateAddress'])
            ->name('profile.addresses.update');

        Route::get('/profile/addresses/{address}/edit', [ProfileController::class, 'editAddress'])
            ->name('profile.addresses.edit');

        Route::delete('/profile/addresses/{address}', [ProfileController::class, 'deleteAddress'])
            ->name('profile.addresses.delete');

        Route::patch('/profile/addresses/{address}/default', [ProfileController::class, 'setDefaultAddress'])
            ->name('profile.addresses.default');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])
            ->name('orders.detail');

        Route::patch('/orders/{id}/ship', [AdminOrderController::class, 'markAsShipped'])
            ->name('orders.ship');

        Route::patch('/orders/{id}/shipment', [AdminOrderController::class, 'updateShipment'])
            ->name('orders.shipment.update');

        Route::patch('/orders/{id}/complete', [AdminOrderController::class, 'markAsCompleted'])
            ->name('orders.complete');

        Route::get('/orders-export', [AdminOrderController::class, 'export'])
            ->name('orders.export');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [AdminProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [AdminProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{id}', [AdminProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])
            ->name('products.destroy');

        // QRIS
        Route::get('/qris', [QrisController::class, 'index'])
            ->name('qris.index');

        Route::get('/qris/{id}', [QrisController::class, 'show'])
            ->name('qris.detail');
        // Settings & account
        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');
Route::get('/account', [AccountController::class, 'index'])
    ->name('account');

Route::put('/account/profile', [AccountController::class, 'updateProfile'])
    ->name('account.profile.update');

Route::put('/account/password', [AccountController::class, 'updatePassword'])
    ->name('account.password.update');e('account');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
