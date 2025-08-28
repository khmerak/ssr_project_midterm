<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Session\Middleware\AuthenticateSession;

Route::get('/', function () {
    return view('dashboard');
    Route::get('/', [AuthenticateSession::class, 'showLogin'])->name('login');
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'create'])->name('products.create');
    Route::resource('products', ProductController::class);

    Route::get('/categories/index', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
});

require __DIR__ . '/auth.php';
