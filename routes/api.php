<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;


// Endpoint user Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// READ: bisa diakses tanpa login
Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{id}', [ProductController::class, 'apiShow']);

// CREATE, UPDATE, DELETE: khusus admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'apiStore']);
    Route::put('/products/{id}', [ProductController::class, 'apiUpdate']);
    Route::patch('/products/{id}', [ProductController::class, 'apiUpdate']);
    Route::delete('/products/{id}', [ProductController::class, 'apiDestroy']);
});

// Route::post('/admin/register', [AuthController::class, 'registerAdmin']);
Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (
        !$user ||
        !Hash::check($request->password, $user->password)
    ) {
        return response()->json([
            'message' => 'Email atau password salah.'
        ], 401);
    }

    if ($user->role !== 'admin') {
        return response()->json([
            'message' => 'Akun ini bukan admin.'
        ], 403);
    }

    $user->tokens()->delete();

    $token = $user->createToken('asyart-admin')->plainTextToken;

    return response()->json([
        'message' => 'Login admin berhasil.',
        'token' => $token,
        'user' => $user->only(['id', 'name', 'email', 'role']),
    ]);
});

Route::post('/customer/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'customer',
    ]);

    return response()->json([
        'message' => 'Customer berhasil didaftarkan.',
        'user' => $user->only(['id', 'name', 'email', 'role']),
    ], 201);
});

Route::post('/customer/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = \App\Models\User::where(
        'email',
        $request->email
    )->first();

    if (
        !$user ||
        !\Illuminate\Support\Facades\Hash::check(
            $request->password,
            $user->password
        )
    ) {
        return response()->json([
            'message' => 'Email atau password salah.'
        ], 401);
    }

    if ($user->role !== 'customer') {
        return response()->json([
            'message' => 'Akun ini bukan customer.'
        ], 403);
    }

    $token = $user->createToken('asyart-customer')->plainTextToken;

    return response()->json([
        'message' => 'Login customer berhasil.',
        'token' => $token,
        'user' => $user->only(['id', 'name', 'email', 'role']),
    ]);
});
