<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route Halaman Utama / Katalog Publik
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/product/{id}', [UserController::class, 'showProduct'])->name('product.detail');

// Route Checkout, Payment, Dashboard User, dan Profile
Route::middleware('auth')->group(function () {
    Route::post('/order/create', [UserController::class, 'createOrder'])->name('order.create');
    Route::get('/payment/{id}', [UserController::class, 'payment'])->name('payment');
    Route::post('/payment/{id}/upload', [UserController::class, 'uploadPayment'])->name('payment.upload');
    
    // Rute Pesanan Pengguna
    Route::get('/order/detail/{id}', [UserController::class, 'orderDetail'])->name('order.detail.user');
    
    Route::get('/dashboard', [UserController::class, 'myOrders'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTE KHUSUS ADMIN (Modular)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Rute Kelola Etalase Produk
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/product', [AdminController::class, 'store'])->name('product.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('product.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('product.update');
    Route::delete('/product/{id}', [AdminController::class, 'destroy'])->name('product.destroy');
    
    // --- RUTE CRUD KATEGORI BARU ---
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('category.store');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('category.destroy');
    
    // Rute Pesanan & Customer
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('order.detail');
    Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('order.updateStatus');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    
    // Rute Stok Gudang (Umum)
    Route::post('/stock', [AdminController::class, 'storeStock'])->name('stock.store');
    Route::delete('/stock/{id}', [AdminController::class, 'destroyStock'])->name('stock.destroy');

    // Rute Khusus Kelola Stok Varian
    Route::get('/variants/{id}/stocks', [AdminController::class, 'manageStocks'])->name('variant.stocks');
    Route::post('/variants/{id}/stocks', [AdminController::class, 'storeVariantStock'])->name('variant.stocks.store');
});

require __DIR__.'/auth.php';