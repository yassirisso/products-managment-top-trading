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
use App\Http\Controllers\UserController;

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



    Route::get('/debug-permission-check', function () {

        return [
            'create_clients' => auth()->user()->can('create clients'),
            'view_clients' => auth()->user()->can('view clients'),
        ];

    });


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

    Route::resource('products', ProductController::class)
        ->middleware('permission:view products');

    Route::get('/products/import', function () {

        return view('products.import');

    })->middleware('permission:create products');

    Route::post(
        '/products/import',
        [ProductController::class, 'import']
    )->name('products.import')
    ->middleware('permission:create products');


    /*
    |--------------------------------------------------------------------------
    | SUPPLIERS
    |--------------------------------------------------------------------------
    */

    Route::resource('suppliers', SupplierController::class)
        ->middleware('permission:view suppliers');

    Route::post(
        '/suppliers/{supplier}/products',
        [SupplierController::class, 'attachProduct']
    )->name('suppliers.attach-product')
    ->middleware('permission:edit suppliers');

    Route::delete(
        '/suppliers/{supplier}/products/{productId}',
        [SupplierController::class, 'detachProduct']
    )->name('suppliers.detach-product')
    ->middleware('permission:edit suppliers');

    Route::get('/suppliers/{supplier}/clients', [SupplierController::class, 'clients'])
        ->name('suppliers.clients')
        ->middleware('permission:view suppliers');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT SUPPLIERS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/{product}/suppliers/create',
        [ProductController::class, 'createSupplier']
    )
    ->name('products.suppliers.create')
    ->middleware('permission:edit products');


    Route::post(
        '/products/{product}/suppliers',
        [ProductController::class, 'storeSupplier']
    )
    ->name('products.suppliers.store')
    ->middleware('permission:edit products');

    /*
    |--------------------------------------------------------------------------
    | CLIENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        'clients/create',
        [ClientController::class, 'create']
    )
    ->middleware('permission:create clients')
    ->name('clients.create');

    Route::resource('clients', ClientController::class)
        ->middleware('permission:view clients')
        ->except([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
    ]);


    Route::post(
        'clients',
        [ClientController::class, 'store']
    )
    ->middleware('permission:create clients')
    ->name('clients.store');


    Route::get(
        'clients/{client}/edit',
        [ClientController::class, 'edit']
    )
    ->middleware('permission:edit clients')
    ->name('clients.edit');


    Route::put(
        'clients/{client}',
        [ClientController::class, 'update']
    )
    ->middleware('permission:edit clients')
    ->name('clients.update');


    Route::delete(
        'clients/{client}',
        [ClientController::class, 'destroy']
    )
    ->middleware('permission:delete clients')
    ->name('clients.destroy');

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

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->middleware('permission:view users');

});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';