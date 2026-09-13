<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

// Redirect ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Otentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang membutuhkan Login & Pengecekan Izin Spesifik
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::middleware('izin:dashboard')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Manajemen Pengguna
    Route::middleware('izin:pengguna')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Manajemen Kategori
    Route::middleware('izin:kategori')->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Manajemen Produk
    Route::middleware('izin:produk')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Kasir (POS)
    Route::middleware('izin:pos')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    });

    // Transaksi (OrderController)
    Route::middleware('izin:transaksi')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{id}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    });

});