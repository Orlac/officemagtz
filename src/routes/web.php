<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', fn () => redirect()->route('discount.index'));
    Route::get('/discount', [DiscountController::class, 'index'])->name('discount.index');
    Route::post('/discount', [DiscountController::class, 'issue'])->name('discount.issue');
    Route::post('/discount/check', [DiscountController::class, 'check'])->name('discount.check');
});
