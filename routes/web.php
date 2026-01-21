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
use App\Http\Controllers\CustomerController;
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');

    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');

    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    /*
    |--------------------------------------------------------------------------
    | Orders – Core
    |--------------------------------------------------------------------------
    */
    Route::resource('orders', OrderController::class);

    /*
    |--------------------------------------------------------------------------
    | Orders – Enterprise Sub Modules
    |--------------------------------------------------------------------------
    */
    Route::post('orders/{order}/items', [OrderItemController::class, 'store'])->name('orders.items.store');
    Route::delete('orders/{order}/items/{item}', [OrderItemController::class, 'destroy'])->name('orders.items.destroy');

    Route::put('orders/{order}/status', [OrderStatusController::class, 'update'])->name('orders.status.update');
    Route::post('orders/{order}/payments', [PaymentController::class, 'store'])->name('orders.payments.store');
    Route::post('orders/{order}/shipments', [ShipmentController::class, 'store'])->name('orders.shipments.store');
    Route::post('orders/{order}/invoice', [InvoiceController::class, 'store'])->name('orders.invoice.store');
Route::get('orders/{order}/invoice/download', [InvoiceController::class, 'download'])
    ->name('orders.invoice.download');

Route::get('orders/{order}/invoice/print', [InvoiceController::class, 'print'])
    ->name('orders.invoice.print');

    /*
    |--------------------------------------------------------------------------
    | Cart & Checkout
    |--------------------------------------------------------------------------
    */
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('cart', [CartController::class, 'view'])->name('cart.view');
    Route::post('checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

    /*
    |--------------------------------------------------------------------------
    | Inventory (Enterprise Extensions)
    |--------------------------------------------------------------------------
    */
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/in', [InventoryController::class, 'createIn'])->name('in.create');
        Route::post('/in', [InventoryController::class, 'storeIn'])->name('in.store');

        Route::get('/transfer', [InventoryController::class, 'createTransfer'])->name('transfer.create');
        Route::post('/transfer', [InventoryController::class, 'transfer'])->name('transfer.store');
    });

    Route::resource('inventory', InventoryController::class);

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{customer}/addresses', [CustomerController::class, 'addresses'])
    ->name('customers.addresses');


    /*
    |--------------------------------------------------------------------------
    | Product Master & Catalog
    |--------------------------------------------------------------------------
    */
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubCategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('units', UnitController::class);
    Route::resource('crops', CropController::class);
    Route::resource('seasons', SeasonController::class);

    Route::resource('product-variants', ProductVariantController::class);
    Route::resource('product-attributes', ProductAttributeController::class);
    Route::resource('product-images', ProductImageController::class);
    Route::resource('product-tags', ProductTagController::class);

    Route::resource('batch-lots', BatchLotController::class);
    Route::resource('expiries', ExpiryController::class);
    Route::resource('certifications', CertificationController::class);

    /*
    |--------------------------------------------------------------------------
    | Reports & Analytics
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('customers', [ReportController::class, 'customers'])->name('customers');

        Route::prefix('advanced')->name('advanced.')->group(function () {
            Route::get('performance', [ReportController::class, 'performance'])->name('performance');
            Route::get('conversion', [ReportController::class, 'conversion'])->name('conversion');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Marketing
    |--------------------------------------------------------------------------
    */
    Route::resource('coupons', CouponController::class);
    Route::resource('campaigns', CampaignController::class);

    /*
    |--------------------------------------------------------------------------
    | System & Access Control
    |--------------------------------------------------------------------------
    */

    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])
    ->name('users.bulkAction');

    Route::post('users/{id}/restore', [UserController::class, 'restore'])
    ->name('users.restore');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});

require __DIR__ . '/auth.php';
