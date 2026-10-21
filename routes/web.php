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
use App\Http\Controllers\ShippingCompanyController;

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

    Route::middleware(['auth', 'permission:view dashboard'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });


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

    // Static paths FIRST — must come before any {product} wildcard
    Route::middleware('permission:create products')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');

        Route::get('products/import', function () {
            return view('products.import');
        })->name('products.import-form');

        Route::post('products/import', [ProductController::class, 'import'])
            ->name('products.import');
    });

    // Dynamic paths AFTER
    Route::middleware('permission:view products')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('products/{product}/suppliers/{supplier}/history', [ProductController::class, 'supplierHistory'])->name('products.supplier-history');
    });

    Route::middleware('permission:edit products')->group(function () {
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('products/{product}', [ProductController::class, 'update']);

        Route::post('products/{product}/purchases', [ProductController::class, 'storePurchase'])->name('products.purchases.store');
        Route::get('products/{product}/purchases/create', [ProductController::class, 'createPurchase'])->name('products.purchases.create');
        Route::post('products/{product}/suppliers', [ProductController::class, 'storeSupplier'])->name('products.suppliers.store');
        Route::get('products/{product}/suppliers/create', [ProductController::class, 'createSupplier'])->name('products.suppliers.create');
    });

    Route::middleware('permission:delete products')->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPPLIERS
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {supplier} wildcard
    Route::middleware('permission:create suppliers')->group(function () {
        Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    });

    // View
    Route::middleware('permission:view suppliers')->group(function () {
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('suppliers/{supplier}/clients', [SupplierController::class, 'clients'])->name('suppliers.clients');
    });

    // Edit
    Route::middleware('permission:edit suppliers')->group(function () {
        Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::patch('suppliers/{supplier}', [SupplierController::class, 'update']);

        Route::post('suppliers/{supplier}/products', [SupplierController::class, 'attachProduct'])->name('suppliers.attach-product');
        Route::delete('suppliers/{supplier}/products/{productId}', [SupplierController::class, 'detachProduct'])->name('suppliers.detach-product');
        Route::post('suppliers/{supplier}/contacts', [SupplierController::class, 'storeContact'])->name('suppliers.contacts.store');
        Route::delete('suppliers/{supplier}/contacts/{contact}', [SupplierController::class, 'destroyContact'])->name('suppliers.contacts.destroy');
    });

    // Delete
    Route::middleware('permission:delete suppliers')->group(function () {
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

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

    Route::get(
        'products/{product}/suppliers/{supplier}/history',
        [ProductController::class, 'supplierHistory']
    )
        ->name('products.supplier-history')
        ->middleware('permission:view products');

    Route::post(
        '/suppliers/quick-create',
        [SupplierController::class, 'quickCreate']
    )->name('suppliers.quick-create');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT SUPPLIER PURCHASES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/{product}/purchases/create',
        [ProductController::class, 'createPurchase']
    )
        ->name('products.purchases.create')
        ->middleware('permission:edit products');


    Route::post(
        '/products/{product}/purchases',
        [ProductController::class, 'storePurchase']
    )
        ->name('products.purchases.store')
        ->middleware('permission:edit products');

    /*
    |--------------------------------------------------------------------------
    | CLIENTS
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {client} wildcard
    Route::middleware('permission:create clients')->group(function () {
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    });

    // View (index / show / trash)
    Route::middleware('permission:view clients')->group(function () {
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/trash', [ClientController::class, 'trash'])->name('clients.trash');
        Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    });

    // Edit (edit / update / restore)
    Route::middleware('permission:edit clients')->group(function () {
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::patch('clients/{client}', [ClientController::class, 'update']);

        Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    });

    // Delete (destroy / force-delete)
    Route::middleware('permission:delete clients')->group(function () {
        Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
    });


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

    // Static paths FIRST — must come before {packingList} wildcard
    Route::middleware('permission:create packing lists')->group(function () {
        Route::get('packing-lists/create', [PackingListController::class, 'create'])->name('packing-lists.create');
        Route::post('packing-lists', [PackingListController::class, 'store'])->name('packing-lists.store');
    });

    // View (index / show / download)
    Route::middleware('permission:view packing lists')->group(function () {
        Route::get('packing-lists', [PackingListController::class, 'index'])->name('packing-lists.index');
        Route::get('packing-lists/{packingList}', [PackingListController::class, 'show'])->name('packing-lists.show');
        Route::get('packing-lists/{packingList}/download', [PackingListController::class, 'downloadExcel'])->name('packing-lists.download');
    });

    // Edit
    Route::middleware('permission:edit packing lists')->group(function () {
        Route::get('packing-lists/{packingList}/edit', [PackingListController::class, 'edit'])->name('packing-lists.edit');
        Route::put('packing-lists/{packingList}', [PackingListController::class, 'update'])->name('packing-lists.update');
        Route::patch('packing-lists/{packingList}', [PackingListController::class, 'update']);
    });

    // Delete
    Route::middleware('permission:delete packing lists')->group(function () {
        Route::delete('packing-lists/{packingList}', [PackingListController::class, 'destroy'])->name('packing-lists.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | PROFORMA INVOICES
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {proformaInvoice} wildcard
    Route::middleware('permission:create proforma invoices')->group(function () {
        Route::get('proforma-invoices/create', [ProformaInvoiceController::class, 'create'])->name('proforma-invoices.create');
        Route::post('proforma-invoices', [ProformaInvoiceController::class, 'store'])->name('proforma-invoices.store');
    });

    // View (index / show / download)
    Route::middleware('permission:view proforma invoices')->group(function () {
        Route::get('proforma-invoices', [ProformaInvoiceController::class, 'index'])->name('proforma-invoices.index');
        Route::get('proforma-invoices/{proformaInvoice}', [ProformaInvoiceController::class, 'show'])->name('proforma-invoices.show');
        Route::get('proforma-invoices/{proformaInvoice}/download', [ProformaInvoiceController::class, 'downloadExcel'])->name('proforma-invoices.download');
    });

    // Edit
    Route::middleware('permission:edit proforma invoices')->group(function () {
        Route::get('proforma-invoices/{proformaInvoice}/edit', [ProformaInvoiceController::class, 'edit'])->name('proforma-invoices.edit');
        Route::put('proforma-invoices/{proformaInvoice}', [ProformaInvoiceController::class, 'update'])->name('proforma-invoices.update');
        Route::patch('proforma-invoices/{proformaInvoice}', [ProformaInvoiceController::class, 'update']);
    });

    // Delete
    Route::middleware('permission:delete proforma invoices')->group(function () {
        Route::delete('proforma-invoices/{proformaInvoice}', [ProformaInvoiceController::class, 'destroy'])->name('proforma-invoices.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | COMMERCIAL INVOICES
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {commercialInvoice} wildcard
    Route::middleware('permission:create commercial invoices')->group(function () {
        Route::get('commercial-invoices/create', [CommercialInvoiceController::class, 'create'])->name('commercial-invoices.create');
        Route::post('commercial-invoices', [CommercialInvoiceController::class, 'store'])->name('commercial-invoices.store');
    });

    // View (index / show / download)
    Route::middleware('permission:view commercial invoices')->group(function () {
        Route::get('commercial-invoices', [CommercialInvoiceController::class, 'index'])->name('commercial-invoices.index');
        Route::get('commercial-invoices/{commercialInvoice}', [CommercialInvoiceController::class, 'show'])->name('commercial-invoices.show');
        Route::get('commercial-invoices/{commercialInvoice}/download', [CommercialInvoiceController::class, 'downloadExcel'])->name('commercial-invoices.download');
    });

    // Edit
    Route::middleware('permission:edit commercial invoices')->group(function () {
        Route::get('commercial-invoices/{commercialInvoice}/edit', [CommercialInvoiceController::class, 'edit'])->name('commercial-invoices.edit');
        Route::put('commercial-invoices/{commercialInvoice}', [CommercialInvoiceController::class, 'update'])->name('commercial-invoices.update');
        Route::patch('commercial-invoices/{commercialInvoice}', [CommercialInvoiceController::class, 'update']);
    });

    // Delete
    Route::middleware('permission:delete commercial invoices')->group(function () {
        Route::delete('commercial-invoices/{commercialInvoice}', [CommercialInvoiceController::class, 'destroy'])->name('commercial-invoices.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | BANK ACCOUNTS
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {bankAccount} wildcard
    Route::middleware('permission:create bank accounts')->group(function () {
        Route::get('bank-accounts/create', [BankAccountController::class, 'create'])->name('bank-accounts.create');
        Route::post('bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');
    });

    // View
    Route::middleware('permission:view bank accounts')->group(function () {
        Route::get('bank-accounts', [BankAccountController::class, 'index'])->name('bank-accounts.index');
        Route::get('bank-accounts/{bankAccount}', [BankAccountController::class, 'show'])->name('bank-accounts.show');
    });

    // Edit
    Route::middleware('permission:edit bank accounts')->group(function () {
        Route::get('bank-accounts/{bankAccount}/edit', [BankAccountController::class, 'edit'])->name('bank-accounts.edit');
        Route::put('bank-accounts/{bankAccount}', [BankAccountController::class, 'update'])->name('bank-accounts.update');
        Route::patch('bank-accounts/{bankAccount}', [BankAccountController::class, 'update']);
    });

    // Delete
    Route::middleware('permission:delete bank accounts')->group(function () {
        Route::delete('bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {user} wildcard
    Route::middleware('permission:create users')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });

    // View
    Route::middleware('permission:view users')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // Edit
    Route::middleware('permission:edit users')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}', [UserController::class, 'update']);
    });

    // Delete
    Route::middleware('permission:delete users')->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPPLIER PAYMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/supplier-purchases/{purchase}/payments/create',
        [ProductController::class, 'createPayment']
    )
        ->name('supplier-payments.create')
        ->middleware('permission:edit products');


    Route::post(
        '/supplier-purchases/{purchase}/payments',
        [ProductController::class, 'storePayment']
    )
        ->name('supplier-payments.store')
        ->middleware('permission:edit products');

    /*
    |--------------------------------------------------------------------------
    | SHIPPING COMPANIES
    |--------------------------------------------------------------------------
    */

    // Static paths FIRST — must come before {shippingCompany} wildcard
    Route::middleware('permission:create shipping companies')->group(function () {
        Route::get('shipping-companies/create', [ShippingCompanyController::class, 'create'])->name('shipping-companies.create');
        Route::post('shipping-companies', [ShippingCompanyController::class, 'store'])->name('shipping-companies.store');
    });

    Route::middleware('permission:view shipping companies')->group(function () {
        Route::get('shipping-companies', [ShippingCompanyController::class, 'index'])->name('shipping-companies.index');
        Route::get('shipping-companies/{shippingCompany}', [ShippingCompanyController::class, 'show'])->name('shipping-companies.show');
    });

    Route::middleware('permission:edit shipping companies')->group(function () {
        Route::get('shipping-companies/{shippingCompany}/edit', [ShippingCompanyController::class, 'edit'])->name('shipping-companies.edit');
        Route::put('shipping-companies/{shippingCompany}', [ShippingCompanyController::class, 'update'])->name('shipping-companies.update');
        Route::patch('shipping-companies/{shippingCompany}', [ShippingCompanyController::class, 'update']);

        // Contacts
        Route::post('shipping-companies/{shippingCompany}/contacts', [ShippingCompanyController::class, 'storeContact'])->name('shipping-companies.contacts.store');
        Route::delete('shipping-companies/{shippingCompany}/contacts/{contact}', [ShippingCompanyController::class, 'destroyContact'])->name('shipping-companies.contacts.destroy');

        // Fees
        Route::post('shipping-companies/{shippingCompany}/fees', [ShippingCompanyController::class, 'storeFee'])->name('shipping-companies.fees.store');
        Route::delete('shipping-companies/{shippingCompany}/fees/{fee}', [ShippingCompanyController::class, 'destroyFee'])->name('shipping-companies.fees.destroy');
    });

    Route::middleware('permission:delete shipping companies')->group(function () {
        Route::delete('shipping-companies/{shippingCompany}', [ShippingCompanyController::class, 'destroy'])->name('shipping-companies.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
