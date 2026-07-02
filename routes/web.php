<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
Route::view('/', 'welcome')->name('home');

// Nombre de la ruta, según el método es la vista a mostrar, si es get o post o delete
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Rutas de Ventas (Estructura correcta en plural)
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');

    // Rutas de Clientes (Corregido a plural 'clients.index')
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/client/{client}', [ClientController::class, 'softDelete'])->name('clients.delete');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class , 'store'])->name('products.store');

    Route::get('/productsCategory', [ProductCategoryController::class, 'index'])->name('products-category.index');
    Route::get('/productsCategory/{productCategory}/edit', [ProductCategoryController::class, 'edit'])->name('products-category.edit');
    Route::put('/productsCategory/{productCategory}', [ProductCategoryController::class, 'update'])->name('products-category.update');
    Route::post('/productsCategory', [ProductCategoryController::class, 'store'])->name('products-category.store');
    Route::delete('products/category/{productCategory}', [ProductCategoryController::class, 'softDelete'])->name('products-category.delete');

});

require __DIR__ . '/settings.php';
