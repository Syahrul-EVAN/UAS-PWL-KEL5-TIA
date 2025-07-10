<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

// Ganti halaman utama ke halaman transaksi
Route::get('/', [TransactionController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');
Route::resource('transaksi', TransactionController::class);

// Atur root "/" ke index transaksi
Route::get('/', [TransactionController::class, 'index']);



