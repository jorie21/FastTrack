<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TransactionIndexController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'finance/Dashboard/Index')->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/today', [DashboardController::class, 'todayStats']);
    Route::get('/dashboard/cash-flow', [DashboardController::class, 'cashFlow']);
    Route::get('/dashboard/top-spending', [DashboardController::class, 'topSpending']);

    Route::get('/dashboard/recent-transactions', [DashboardController::class, 'recentTransactions']);
    Route::get('/transaction',[TransactionIndexController::class , 'index'])->name('transaction.index');
    Route::get('/transactions', [TransactionController::class, 'get']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{uuid}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{uuid}', [TransactionController::class, 'delete']);

    Route::get('/categories', [CategoryController::class, 'get']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{uuid}', [CategoryController::class, 'update']);
    Route::delete('/categories/{uuid}', [CategoryController::class, 'delete']);

    Route::inertia('wallet', 'finance/Wallet/Index')->name('wallet');
    Route::get('/wallets', [WalletController::class, 'get']);
    Route::post('/wallets', [WalletController::class, 'store']);
    Route::put('/wallets/{uuid}', [WalletController::class, 'update']);
    Route::delete('/wallets/{uuid}', [WalletController::class, 'delete']);
});

require __DIR__.'/settings.php';
