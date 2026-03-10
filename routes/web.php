<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TransactionIndexController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'finance/Dashboard/Index')->name('dashboard');
    Route::get('/transaction',[TransactionIndexController::class , 'index'])->name('transaction.index');

    Route::get('/transactions', [TransactionController::class, 'get']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{uuid}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{uuid}', [TransactionController::class, 'delete']);

    Route::get('/categories', [CategoryController::class, 'get']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{uuid}', [CategoryController::class, 'update']);
    Route::delete('/categories/{uuid}', [CategoryController::class, 'delete']);
});

require __DIR__.'/settings.php';
