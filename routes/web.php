<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Rute Beranda Utama: Pengalihan Instan Saat Pertama Kali Buka Web
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'Kasir') {
            return redirect()->route('sales.create');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// 2. Kelompok Rute Aplikasi (Wajib Auth & Terverifikasi)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ==========================================
    // 🚨 KELOMPOK HAK AKSES: ADMIN & MANAJER SAJA (Kasir DILARANG MASUK)
    // ==========================================
    Route::middleware(['role:Admin,Manajer'])->group(function () {
        // Halaman Dashboard Utama dikunci hanya untuk Admin & Manajer
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Navigasi Profil juga dikunci hanya untuk Admin & Manajer
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/distributors', [DistributorController::class, 'index'])->name('distributors.index');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/stock/adjustment', [StockAdjustmentController::class, 'index'])->name('stock.adjustment');
    });

    // ==========================================
    // 🚨 KELOMPOK KHUSUS AKSI ACTION ADMIN SAJA
    // ==========================================
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/distributors/create', [DistributorController::class, 'create'])->name('distributors.create');
        Route::post('/distributors', [DistributorController::class, 'store'])->name('distributors.store');
        Route::delete('/distributors/{id}', [DistributorController::class, 'destroy'])->name('distributors.destroy');

        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::post('/stock/adjustment', [StockAdjustmentController::class, 'store'])->name('stock.adjustment.store');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::patch('/purchases/{id}/status/{status}', [PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');
    });

    // ==========================================
    // 🚨 KELOMPOK HAK AKSES KASIR & ADMIN (Satu-Satunya Tempat yang Bisa Diakses Kasir)
    // ==========================================
    Route::middleware(['role:Admin,Kasir'])->group(function () {
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    });

});

// 3. RUTE OTENTIKASI BAWAAN LARAVEL BREEZE
require __DIR__.'/auth.php';