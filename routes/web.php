<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductRegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro-producto', [ProductRegisterController::class, 'create'])->name('registro-producto.create');
Route::post('/registro-producto', [ProductRegisterController::class, 'store'])->name('registro-producto.store');
Route::get('/api/modelos/{model}/colores', [ProductRegisterController::class, 'colors'])->name('api.modelos.colores');
Route::get('/compras', [ProductRegisterController::class, 'index'])->name('purchases.index');
Route::get('/api/purchases/chart', [ProductRegisterController::class, 'chart'])->name('api.purchases.chart');
Route::get('/compras/{id}', [ProductRegisterController::class, 'show'])->name('purchases.show');
Route::get('/compras/{id}/editar', [ProductRegisterController::class, 'edit'])->name('purchases.edit');
Route::delete('/compras/{id}', [ProductRegisterController::class, 'destroy'])->name('purchases.destroy');
Route::put('/compras/{id}', [ProductRegisterController::class, 'update'])->name('purchases.update');