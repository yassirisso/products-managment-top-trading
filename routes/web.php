<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('clients', ClientController::class);
Route::get('clients/trash', [ClientController::class, 'trash'])->name('clients.trash');
Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
Route::resource('purchases', PurchaseController::class)->except(['index', 'show']);
Route::post('clients/{client}/purchases', [PurchaseController::class, 'storeForClient'])->name('clients.purchases.store');
Route::resource('clients.purchases', PurchaseController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->shallow();
Route::resource('invoices', InvoiceController::class)->only(['store']);
// Or if you want it nested under clients:
Route::post('clients/{client}/invoices', [InvoiceController::class, 'store'])->name('clients.invoices.store');
Route::get('clients/{client}/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::controller(InvoiceController::class)->group(function () {
    Route::get('clients/{client}/invoices/create', 'create')->name('invoices.create');
    Route::post('clients/{client}/invoices', 'store')->name('invoices.store');
});
Route::get('/products/import', function () {
    return view('products.import');
});
Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
