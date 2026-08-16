<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Kitchen\KitchenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Customer Routes (QR Code Table Ordering System)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('customer.menu', ['table' => '01']);
});

Route::get('/order', [CustomerOrderController::class, 'index'])->name('customer.menu');
Route::post('/order/checkout', [CustomerOrderController::class, 'storeOrder'])->name('customer.order.checkout');
Route::get('/order/payment/{order_number}', [CustomerOrderController::class, 'qrisPayment'])->name('customer.payment.qris');
Route::post('/order/payment/{order_number}/simulate', [CustomerOrderController::class, 'simulateQrisPayment'])->name('customer.payment.simulate');
Route::get('/order/status/{order_number}', [CustomerOrderController::class, 'orderStatus'])->name('customer.order.status');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Kasir Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir,admin'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
    Route::post('/orders/{id}/confirm-payment', [CashierController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{id}/send-kitchen', [CashierController::class, 'sendToKitchen'])->name('orders.send-kitchen');
    Route::post('/orders/{id}/cancel', [CashierController::class, 'cancelOrder'])->name('orders.cancel');
    Route::delete('/orders/{id}', [CashierController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/clear-completed', [CashierController::class, 'clearCompleted'])->name('orders.clear-completed');
});

/*
|--------------------------------------------------------------------------
| Dapur / Barista Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dapur,admin'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/dashboard', [KitchenController::class, 'dashboard'])->name('dashboard');
    Route::post('/orders/{id}/process', [KitchenController::class, 'startProcess'])->name('orders.process');
    Route::post('/orders/{id}/complete', [KitchenController::class, 'completeOrder'])->name('orders.complete');
});

/*
|--------------------------------------------------------------------------
| Admin Management Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/products/{product}/toggle', [ProductController::class, 'toggleAvailability'])->name('products.toggle');
    Route::resource('products', ProductController::class)->except(['show']);

    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    Route::get('/tables/{table}/qr', [TableController::class, 'showQr'])->name('tables.qr');
    Route::post('/tables/{table}/toggle', [TableController::class, 'toggleStatus'])->name('tables.toggle');
    Route::resource('tables', TableController::class)->except(['show', 'create', 'edit']);

    Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
});
