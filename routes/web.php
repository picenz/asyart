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

// --- RUTE DASHBOARD & ADMIN ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Group Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            abort_unless(auth()->user()->role === 'admin', 403);
            return view('admin.dashboard');
        })->name('dashboard');

        // Rute Orders Admin
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.detail');
        Route::patch('/orders/{id}/ship', [AdminOrderController::class, 'markAsShipped'])->name('orders.ship');
        Route::patch('/orders/{id}/complete', [AdminOrderController::class, 'markAsCompleted'])->name('orders.complete');
        Route::get('/orders-export', [AdminOrderController::class, 'export'])->name('orders.export');

        // Rute Products Admin (Index, Create, Store, Edit, Destroy)
        Route::get('/products', function () {
            return view('admin.products.index');
        })->name('products.index');

        Route::get('/products/create', function () {
            return view('admin.products.create');
        })->name('products.create');

        Route::post('/products', function (Request $request) {
            return redirect()->route('admin.products.index')->with('success', 'Product saved successfully.');
        })->name('products.store');

        Route::get('/products/{id}/edit', function () {
            return view('admin.products.edit');
        })->name('admin.products.edit');

        // Rute Hapus Produk
        Route::delete('/products/{id}', function ($id) {
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        })->name('admin.products.destroy');

        // Rute QRIS Transactions Admin (Index & Detail)
        Route::get('/qris', function () {
            return view('admin.qris');
        })->name('qris.index');

        Route::get('/qris/{id}', function ($id) {
            return view('admin.qris-detail');
        })->name('qris.detail');

        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings.index');

        Route::get('/account', function () {
            return view('admin.account');
        })->name('account');
    });

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

    // Rute List Addresses (Narik dari ProfileController biar data alamat muncul)
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');

    // Rute Add, Edit, Update, & Destroy Address
    Route::get('/profile/addresses/create', [ProfileController::class, 'create'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [ProfileController::class, 'store'])->name('profile.addresses.store');
    Route::get('/profile/addresses/{id}/edit', [ProfileController::class, 'editAddress'])->name('address.edit');
    Route::put('/profile/addresses/{id}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('address.destroy');
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
