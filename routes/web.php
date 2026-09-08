<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/shop/products/{product}', [ShopController::class, 'show'])->name('shop.product');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:Owner,Cashier'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});
