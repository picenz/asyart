<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('dashboard');
    })->name('admin.dashboard');

    Route::get('/customer/dashboard', function () {
        abort_unless(auth()->user()->role === 'customer', 403);

        return view('dashboard');
    })->name('customer.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/customer/dashboard', function () {
    return view('customer.dashboard');
})->name('customer.dashboard');

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
        Route::post('/cart', function () {
            // Nanti dihubungkan ke proses tambah keranjang.
        })->name('cart.store');

        Route::get('/cart', function () {
            // Nanti menampilkan keranjang customer.
        })->name('cart.index');

        Route::get('/checkout', function () {
            // Nanti dihubungkan ke proses checkout.
        })->name('checkout');
    });
});

Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/products/{id}', function ($id) {
    return view('products.show', compact('id'));
})->name('products.show');


require __DIR__ . '/auth.php';
