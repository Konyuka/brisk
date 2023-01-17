<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;


// if (App::environment('production')) {
//     URL::forceScheme('https');
// }

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
    Route::get('/reports', [DashboardController::class, 'reports'])
    ->name('reports');
    Route::post('/dashboard/add_product', [DashboardController::class, 'addProduct'])
    ->name('add_product');
    Route::post('/dashboard/upload_product', [DashboardController::class, 'uploadProduct'])
    ->name('upload_product');
    Route::get('/dashboard/download_product', [DashboardController::class, 'downloadProduct'])
    ->name('download_product');
    Route::post('/dashboard/update_product', [DashboardController::class, 'updateProduct'])
    ->name('update_product');
    Route::get('/dashboard/users_clients', [DashboardController::class, 'usersClients'])
    ->name('users_clients');
    Route::post('/dashboard/user_register', [DashboardController::class, 'registerUser'])
    ->name('user_register');
    Route::get('/dashboard/product_inventory', [DashboardController::class, 'inventory'])
    ->name('product_inventory');
    Route::get('/dashboard/product_delivery', [DashboardController::class, 'delivery'])
    ->name('product_delivery');
    Route::post('/dashboard/process_delivery', [DashboardController::class, 'processDelivery'])
    ->name('process_delivery');
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
    Route::post('/dashboard/finish_trip', [DashboardController::class, 'finishTrip'])
    ->name('finish_trip');

    
});




// if (env('APP_ENV') === 'local') {
//     // URL::forceScheme('http');
//     // \URL::forceScheme('http');
// } else {
//     URL::forceScheme('https');
// }
