<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PackingListController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\CommercialInvoiceController;
use App\Http\Controllers\BankAccountController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('dashboard');

});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        return view('dashboard');

    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class);

    Route::get('/products/import', function () {

        return view('products.import');

    });

    Route::post(
        '/products/import',
        [ProductController::class, 'import']
    )->name('products.import');


    /*
    |--------------------------------------------------------------------------
    | SUPPLIERS
    |--------------------------------------------------------------------------
    */

    Route::resource('suppliers', SupplierController::class);

    Route::post(
        '/suppliers/{supplier}/products',
        [SupplierController::class, 'attachProduct']
    )->name('suppliers.attach-product');

    Route::delete(
        '/suppliers/{supplier}/products/{productId}',
        [SupplierController::class, 'detachProduct']
    )->name('suppliers.detach-product');


    /*
    |--------------------------------------------------------------------------
    | CLIENTS
    |--------------------------------------------------------------------------
    */

    Route::resource('clients', ClientController::class);

    Route::get(
        'clients/trash',
        [ClientController::class, 'trash']
    )->name('clients.trash');

    Route::post(
        'clients/{id}/restore',
        [ClientController::class, 'restore']
    )->name('clients.restore');

    Route::delete(
        'clients/{id}/force-delete',
        [ClientController::class, 'forceDelete']
    )->name('clients.force-delete');


    /*
    |--------------------------------------------------------------------------
    | PURCHASES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'purchases',
        PurchaseController::class
    )->except(['index', 'show']);

    Route::post(
        'clients/{client}/purchases',
        [PurchaseController::class, 'storeForClient']
    )->name('clients.purchases.store');

    Route::resource(
        'clients.purchases',
        PurchaseController::class
    )
    ->only([
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ])
    ->shallow();


    /*
    |--------------------------------------------------------------------------
    | INVOICES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'invoices',
        InvoiceController::class
    )->only(['store']);

    Route::post(
        'clients/{client}/invoices',
        [InvoiceController::class, 'store']
    )->name('clients.invoices.store');

    Route::get(
        'clients/{client}/invoices/create',
        [InvoiceController::class, 'create']
    )->name('invoices.create');

    Route::controller(InvoiceController::class)->group(function () {

        Route::get(
            'clients/{client}/invoices/create',
            'create'
        )->name('invoices.create');

        Route::post(
            'clients/{client}/invoices',
            'store'
        )->name('invoices.store');

    });


    /*
    |--------------------------------------------------------------------------
    | PACKING LISTS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'packing-lists',
        PackingListController::class
    );

    Route::get(
        'packing-lists/{packingList}/download',
        [PackingListController::class, 'downloadExcel']
    )->name('packing-lists.download');


    /*
    |--------------------------------------------------------------------------
    | PROFORMA INVOICES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'proforma-invoices',
        ProformaInvoiceController::class
    );

    Route::get(
        'proforma-invoices/{proformaInvoice}/download',
        [ProformaInvoiceController::class, 'downloadExcel']
    )->name('proforma-invoices.download');

    /*
    |--------------------------------------------------------------------------
    | COMMERCIAL INVOICES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'commercial-invoices',
        CommercialInvoiceController::class
    );

    Route::get(
        'commercial-invoices/{commercialInvoice}/download',
        [CommercialInvoiceController::class, 'downloadExcel']
    )->name('commercial-invoices.download');

    /*
    |--------------------------------------------------------------------------
    | BANK ACCOUNTS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'bank-accounts',
        BankAccountController::class
    );

});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';