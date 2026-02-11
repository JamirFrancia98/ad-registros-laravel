<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProductRegisterController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| PRODUCTOS
|--------------------------------------------------------------------------
| - Lista de productos        -> /compras
| - Agregar producto         -> /registro-producto
| - Productos vendidos       -> placeholder
*/

/** LISTA DE PRODUCTOS (antes: compras) */
Route::get('/compras', [ProductRegisterController::class, 'index'])
    ->name('purchases.index');

/** AGREGAR PRODUCTO */
Route::get('/registro-producto', [ProductRegisterController::class, 'create'])
    ->name('registro-producto.create');

Route::post('/registro-producto', [ProductRegisterController::class, 'store'])
    ->name('registro-producto.store');

/** PRODUCTOS VENDIDOS (placeholder por ahora) */
Route::get('/productos-vendidos', function () {
    return redirect()
        ->route('purchases.index')
        ->with('ok', 'Productos vendidos en construcción');
})->name('products.sold');

/*
|--------------------------------------------------------------------------
| API / AJAX
|--------------------------------------------------------------------------
*/
Route::get('/api/modelos/{model}/colores', [ProductRegisterController::class, 'colors'])
    ->name('api.modelos.colores');

Route::get('/api/purchases/chart', [ProductRegisterController::class, 'chart'])
    ->name('api.purchases.chart');

/*
|--------------------------------------------------------------------------
| COMPRAS / PRODUCTOS (acciones)
|--------------------------------------------------------------------------
*/
Route::get('/compras/{id}', [ProductRegisterController::class, 'show'])
    ->name('purchases.show');

Route::get('/compras/{id}/editar', [ProductRegisterController::class, 'edit'])
    ->name('purchases.edit');

Route::put('/compras/{id}', [ProductRegisterController::class, 'update'])
    ->name('purchases.update');

Route::delete('/compras/{id}', [ProductRegisterController::class, 'destroy'])
    ->name('purchases.destroy');

Route::resource('proveedores', SupplierController::class);

/*
|--------------------------------------------------------------------------
| VENTAS (acciones)
|--------------------------------------------------------------------------
*/ 

Route::get('/ventas', [SaleController::class, 'index'])->name('sales.index');
Route::get('/ventas/crear', [SaleController::class, 'create'])->name('sales.create');
Route::post('/ventas', [SaleController::class, 'store'])->name('sales.store');
Route::get('/ventas/{id}/editar', [SaleController::class, 'edit'])->name('sales.edit');
Route::put('/ventas/{id}', [SaleController::class, 'update'])->name('sales.update');
Route::delete('/ventas/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
Route::get('/ventas/analisis', [SaleController::class, 'analysis'])->name('sales.analysis');
/*
|--------------------------------------------------------------------------
| Analisis
|--------------------------------------------------------------------------
*/ 
Route::get('/analisis', [AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('/api/analisis/summary', [AnalyticsController::class, 'summary'])->name('api.analytics.summary');
Route::get('/api/analisis/efficiency', [AnalyticsController::class, 'efficiency'])->name('api.analytics.efficiency');

Route::get('/api/analisis/top-modelos', [AnalyticsController::class, 'topModels']);
Route::get('/api/analisis/task-summary', [AnalyticsController::class, 'taskSummary']);