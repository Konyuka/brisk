<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Inventory');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'stock'])
    ->name('dashboard');
    Route::post('/dashboard/add_product', [DashboardController::class, 'addProduct'])
    ->name('add_product');
    Route::get('/dashboard/users_clients', [DashboardController::class, 'usersClients'])
    ->name('users_clients');
    Route::post('/dashboard/user_register', [DashboardController::class, 'registerUser'])
    ->name('user_register');
    Route::get('/dashboard/product_inventory', [DashboardController::class, 'inventory'])
    ->name('product_inventory');
    Route::get('/dashboard/product_delivery', [DashboardController::class, 'delivery'])
    ->name('product_delivery');
    Route::post('/dashboard/register_client', [DashboardController::class, 'registerClient'])
    ->name('register_client');
    Route::post('/dashboard/register_sale', [DashboardController::class, 'registerSale'])
    ->name('register_sale');
    Route::get('/dashboard/process_order', [DashboardController::class, 'processOrder'])
    ->name('process_order');
    Route::get('/dashboard/invoice_number', [DashboardController::class, 'invoiceNumber'])
    ->name('invoice_number');
    Route::get('/dashboard/display_receipt', [DashboardController::class, 'displayReceipt'])
    ->name('display_receipt');
    Route::post('/dashboard/finish_sale', [DashboardController::class, 'finishSale'])
    ->name('finish_sale');

    
});
