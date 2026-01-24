<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Settings;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\BatchLotController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\InventoryController;
// use App\Http\Controllers\CustomerController; temprory removed
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\CustomerAddressController;
use App\Http\Controllers\AddressLookupController;



use App\Http\Controllers\ReportController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderReturnController;
use App\Http\Controllers\WarehouseController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'can:dashboard.view'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated & Secured Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {


    // 🔽 ADD THIS HERE
    Route::get('address-lookup', [AddressLookupController::class, 'lookup'])
        ->name('address.lookup');


    /* ================= Settings ================= */
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])
        ->middleware('can:settings-profile.view')->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])
        ->middleware('can:settings-profile.edit')->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])
        ->middleware('can:settings-profile.delete')->name('settings.profile.destroy');

    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])
        ->middleware('can:settings-password.view')->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])
        ->middleware('can:settings-password.edit')->name('settings.password.update');

    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])
        ->middleware('can:settings-appearance.view')->name('settings.appearance.edit');

    /* ================= Orders ================= */
    // Route::resource('orders', OrderController::class)->only(['index', 'show'])
    //     ->middleware('can:orders.view');

    // Route::resource('orders', OrderController::class)->only(['create', 'store'])
    //     ->middleware('can:orders.create');

    // Route::resource('orders', OrderController::class)->only(['edit', 'update'])
    //     ->middleware('can:orders.edit');

    // Route::resource('orders', OrderController::class)->only(['destroy'])
    //     ->middleware('can:orders.delete');


    Route::resource('orders', OrderController::class);


    Route::post('orders/{order}/items', [OrderItemController::class, 'store'])
        ->middleware('can:orders.edit')->name('orders.items.store');
    Route::delete('orders/{order}/items/{item}', [OrderItemController::class, 'destroy'])
        ->middleware('can:orders.edit')->name('orders.items.destroy');

    Route::put('orders/{order}/status', [OrderStatusController::class, 'update'])
        ->middleware('can:orders.edit')->name('orders.status.update');
    Route::post('orders/{order}/payments', [PaymentController::class, 'store'])
        ->middleware('can:orders.edit')->name('orders.payments.store');

    Route::get('orders/payments/bulk', [PaymentController::class, 'bulkForm'])
        ->middleware('can:orders.edit')
        ->name('orders.payments.bulk.form');

    Route::post('orders/payments/bulk', [PaymentController::class, 'bulkProcess'])
        ->middleware('can:orders.edit')
        ->name('orders.payments.bulk.process');








    /* ================= Order Returns ================= */

    Route::post('order-returns/bulk-action', [OrderReturnController::class, 'bulkAction'])
        ->middleware('can:order-returns.edit')
        ->name('order-returns.bulkAction');

    Route::post('order-returns/{orderReturn}/approve', [OrderReturnController::class, 'approve'])
        ->middleware('can:order-returns.edit')
        ->name('order-returns.approve');

    Route::post('order-returns/{orderReturn}/reject', [OrderReturnController::class, 'reject'])
        ->middleware('can:order-returns.edit')
        ->name('order-returns.reject');

    Route::post('order-returns/{orderReturn}/refund', [OrderReturnController::class, 'refund'])
        ->middleware('can:order-returns.edit')
        ->name('order-returns.refund');

    Route::resource('order-returns', OrderReturnController::class)
        ->middleware(['auth', 'verified']);




    Route::post('orders/{order}/shipments', [ShipmentController::class, 'store'])
        ->middleware('can:shipments.create')->name('orders.shipments.store');
    Route::post('orders/{order}/invoice', [InvoiceController::class, 'store'])
        ->middleware('can:invoices.create')->name('orders.invoice.store');

    Route::get('orders/{order}/invoice/download', [InvoiceController::class, 'download'])
        ->middleware('can:invoices.view')->name('orders.invoice.download');
    Route::get('orders/{order}/invoice/print', [InvoiceController::class, 'print'])
        ->middleware('can:invoices.view')->name('orders.invoice.print');



    /* ================= Cart & Checkout ================= */
    Route::post('cart/add', [CartController::class, 'add'])
        ->middleware('can:cart.create')->name('cart.add');
    Route::get('cart', [CartController::class, 'view'])
        ->middleware('can:cart.view')->name('cart.view');
    Route::post('checkout/place', [CheckoutController::class, 'place'])
        ->middleware('can:checkout.create')->name('checkout.place');

    /* ================= Inventory ================= */
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/in', [InventoryController::class, 'createIn'])
            ->middleware('can:inventory.create')->name('in.create');
        Route::post('/in', [InventoryController::class, 'storeIn'])
            ->middleware('can:inventory.create')->name('in.store');

        Route::get('/transfer', [InventoryController::class, 'createTransfer'])
            ->middleware('can:inventory.edit')->name('transfer.create');
        Route::post('/transfer', [InventoryController::class, 'transfer'])
            ->middleware('can:inventory.edit')->name('transfer.store');
    });

    Route::get('inventory/available', [InventoryController::class, 'available'])
        ->middleware('can:inventory.view')->name('inventory.available');

    Route::resource('inventory', InventoryController::class)->middleware([
        'index' => 'can:inventory.view',
        'show' => 'can:inventory.view',
        'create' => 'can:inventory.create',
        'store' => 'can:inventory.create',
        'edit' => 'can:inventory.edit',
        'update' => 'can:inventory.edit',
        'destroy' => 'can:inventory.delete',
    ]);

    /* ================= Customers ================= */
    // Route::get('customers/search', [CustomerController::class, 'search'])
    //     ->middleware('can:customers.view')->name('customers.search');
    // Route::get('customers/{customer}/addresses', [CustomerController::class, 'addresses'])
    //     ->middleware('can:customers.view')->name('customers.addresses');

    // Route::resource('customers', CustomerController::class)->middleware([
    //     'index' => 'can:customers.view',
    //     'show' => 'can:customers.view',
    //     'create' => 'can:customers.create',
    //     'store' => 'can:customers.create',
    //     'edit' => 'can:customers.edit',
    //     'update' => 'can:customers.edit',
    //     'destroy' => 'can:customers.delete',
    // ]);

    /* ================= Customers ================= */
    // Route::get('customers/search', [CustomerController::class, 'search'])
    //     ->middleware('can:customers.search')
    //     ->name('customers.search');

    // Route::get('customers/{customer}/addresses', [CustomerController::class, 'addresses'])
    //     ->middleware('can:customers.view')
    //     ->name('customers.addresses');

    // Route::resource('customers', CustomerController::class);

    /* ================= Customers ================= */

    // Search
    Route::get('customers/search', [CustomerSearchController::class, 'search'])
        ->middleware('can:customers.search')
        ->name('customers.search');

    // Addresses API (for Orders UI)
    Route::get('customers/{customer}/addresses', [CustomerAddressController::class, 'addresses'])
        ->middleware('can:customers.view')
        ->name('customers.addresses');

    // CRUD
    Route::resource('customers', CustomerController::class);



    /* ================= Products & Masters ================= */

    Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])
        ->middleware('can:categories.delete')->name('categories.bulkAction');
    Route::resource('categories', CategoryController::class)->middleware('can:categories.view');

    Route::post('subcategories/bulk-action', [SubCategoryController::class, 'bulkAction'])
        ->middleware('can:subcategories.delete')->name('subcategories.bulkAction');
    Route::resource('subcategories', SubCategoryController::class)->middleware('can:subcategories.view');

    Route::resource('products', ProductController::class)->middleware('can:products.view');
    Route::resource('brands', BrandController::class)->middleware('can:brands.view');
    Route::resource('units', UnitController::class)->middleware('can:units.view');
    Route::resource('crops', CropController::class)->middleware('can:crops.view');
    Route::resource('seasons', SeasonController::class)->middleware('can:seasons.view');

    Route::post('product-variants/bulk-action', [ProductVariantController::class, 'bulkAction'])
        ->middleware('can:product-variants.delete')->name('product-variants.bulkAction');
    Route::post('product-variants/{id}/restore', [ProductVariantController::class, 'restore'])
        ->middleware('can:product-variants.edit')->name('product-variants.restore');
    Route::resource('product-variants', ProductVariantController::class)->middleware('can:product-variants.view');

    Route::resource('product-attributes', ProductAttributeController::class)->middleware('can:product-attributes.view');
    Route::resource('product-images', ProductImageController::class)->middleware('can:product-images.view');
    Route::resource('product-tags', ProductTagController::class)->middleware('can:product-tags.view');
    Route::resource('batch-lots', BatchLotController::class)->middleware('can:batch-lots.view');
    Route::resource('expiries', ExpiryController::class)->middleware('can:expiries.view');
    Route::resource('certifications', CertificationController::class)->middleware('can:certifications.view');

    /* ================= Reports ================= */
    Route::prefix('reports')->name('reports.')->middleware('can:reports-sales.view')->group(function () {
        Route::get('sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('customers', [ReportController::class, 'customers'])->name('customers');

        Route::prefix('advanced')->name('advanced.')->group(function () {
            Route::get('performance', [ReportController::class, 'performance'])->name('performance');
            Route::get('conversion', [ReportController::class, 'conversion'])->name('conversion');
        });
    });

    /* ================= Marketing ================= */
    Route::resource('coupons', CouponController::class)->middleware('can:coupons.view');
    Route::resource('campaigns', CampaignController::class)->middleware('can:campaigns.view');

    /* ================= System ================= */
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])
        ->middleware('can:users.edit')->name('users.bulkAction');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])
        ->middleware('can:users.edit')->name('users.restore');
    Route::resource('users', UserController::class)->middleware('can:users.view');

    Route::post('roles/bulk-action', [RoleController::class, 'bulkAction'])
        ->middleware('can:roles.edit')->name('roles.bulkAction');
    Route::resource('roles', RoleController::class)->middleware('can:roles.view');


    Route::resource('warehouses', WarehouseController::class);
    Route::patch('warehouses/{warehouse}/toggle', [WarehouseController::class, 'toggle'])
        ->name('warehouses.toggle');

});

require __DIR__ . '/auth.php';
