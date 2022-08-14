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
    Route::get('/dashboard', [DashboardController::class, 'inventory'])
    ->name('dashboard');
    Route::get('/dashboard/add_client', [DashboardController::class, 'addClient'])
    ->name('add_client');
    Route::post('/dashboard/register_client', [DashboardController::class, 'registerClient'])
    ->name('register_client');
    Route::post('/dashboard/register_sale', [DashboardController::class, 'registerSale'])
    ->name('register_sale');
    Route::post('/add_product', [DashboardController::class, 'addProduct'])
    ->name('add_product');
});
