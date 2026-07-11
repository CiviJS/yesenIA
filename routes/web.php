<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;


Route::view('/', 'welcome')->name('home');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ==========================================
    // RUTAS LIBRES (GET - Solo lectura)
    // ==========================================
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{order}', [OrderController::class, 'detail'])->name('orders.detail')->withTrashed();

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    Route::get('/productsCategory', [ProductCategoryController::class, 'index'])->name('products-category.index');
    Route::get('/productsCategory/{productCategory}/edit', [ProductCategoryController::class, 'edit'])->name('products-category.edit');

    Route::get('/limpiar-todo', function () {
     
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('status', '¡Todo limpio, compae!');
    })->name('cache.limpiar');
    // ==========================================
    // RUTAS PROTEGIDAS POR MI MIDDELWARE DE IDEMPOTENCIA (POST, PUT, DELETE)
    // ==========================================
    Route::middleware(['idempotencia'])->group(function () {

        // Ventas
        Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
        Route::delete('sales/{sale}', [SaleController::class, 'softDelete'])->name('sales.delete');
        Route::put('sales/{sale}/restore', [SaleController::class, 'restore'])->name('sales.restore')->withTrashed();

        // Ordenes
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::delete('/orders/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::put('/orders/{order}/restore', [OrderController::class, 'restore'])->name('orders.restore')->withTrashed();

        // Items de Orden
        Route::put('/order/item/{orderItem}', [OrderController::class, 'restoreOrderItem'])->name('orderItem.restore')->withTrashed();
        Route::delete('/order/item/{orderItem}', [OrderController::class, 'cancelOrderItem'])->name('orderItem.cancel');

        // Pagos
        Route::post('/payments/{order}', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::delete('/payments/{payment}', [PaymentController::class, 'cancelPay'])->name('payments.cancel');

        // Clientes
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/client/{client}', [ClientController::class, 'softDelete'])->name('clients.delete');

        // Productos
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::delete('/products/{product}', [ProductController::class, 'softDelete'])->name('products.delete');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

        // Categorías
        Route::post('/productsCategory', [ProductCategoryController::class, 'store'])->name('products-category.store');
        Route::put('/productsCategory/{productCategory}', [ProductCategoryController::class, 'update'])->name('products-category.update');
        Route::delete('products/category/{productCategory}', [ProductCategoryController::class, 'softDelete'])->name('products-category.delete');
    });
});

require __DIR__ . '/settings.php';
