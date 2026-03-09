<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/transactions', [TransactionController::class, 'get']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::delete('/transactions/{uuid}', [TransactionController::class, 'delete']);

    Route::get('/categories', [CategoryController::class, 'get']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{uuid}', [CategoryController::class, 'delete']);

});