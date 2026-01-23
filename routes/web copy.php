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
Route::get('/', fn () => redirect()->route('login'));

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
| Authenticated + Permission Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    | Settings
    */
    Route::middleware('permission:settings-profile.edit')->group(function () {
        Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
        Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
        Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    });

    Route::middleware('permission:settings-password.edit')->group(function () {
        Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
        Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    });

    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])
        ->middleware('permission:settings-appearance.view')
        ->name('settings.appearance.edit');

    /*
    | Orders
    */
    Route::resource('orders', OrderController::class)
        ->middleware([
            'index'   => 'permission:orders.view',
            'show'    => 'permission:orders.view',
            'create'  => 'permission:orders.create',
            'store'   => 'permission:orders.create',
            'edit'    => 'permission:orders.edit',
            'update'  => 'permission:orders.edit',
            'destroy' => 'permission:orders.delete',
        ]);

    Route::post('orders/{order}/items', [OrderItemController::class, 'store'])
        ->middleware('permission:orders.edit');

    Route::delete('orders/{order}/items/{item}', [OrderItemController::class, 'destroy'])
        ->middleware('permission:orders.edit');

    Route::put('orders/{order}/status', [OrderStatusController::class, 'update'])
        ->middleware('permission:orders.edit');

    Route::post('orders/{order}/payments', [PaymentController::class, 'store'])
        ->middleware('permission:orders.edit');

    Route::post('orders/{order}/shipments', [ShipmentController::class, 'store'])
        ->middleware('permission:orders.edit');

    Route::post('orders/{order}/invoice', [InvoiceController::class, 'store'])
        ->middleware('permission:orders.edit');

    Route::get('orders/{order}/invoice/download', [InvoiceController::class, 'download'])
        ->middleware('permission:orders.view')
        ->name('orders.invoice.download');

    Route::get('orders/{order}/invoice/print', [InvoiceController::class, 'print'])
        ->middleware('permission:orders.view')
        ->name('orders.invoice.print');

    /*
    | Inventory
    */
    Route::prefix('inventory')->name('inventory.')->middleware('permission:inventory.view')->group(function () {
        Route::get('/in', [InventoryController::class, 'createIn'])->name('in.create');
        Route::post('/in', [InventoryController::class, 'storeIn'])->middleware('permission:inventory.create')->name('in.store');

        Route::get('/transfer', [InventoryController::class, 'createTransfer'])->name('transfer.create');
        Route::post('/transfer', [InventoryController::class, 'transfer'])->middleware('permission:inventory.edit')->name('transfer.store');
    });

    Route::resource('inventory', InventoryController::class)
        ->middleware('permission:inventory.view');

    /*
    | Customers
    */
    Route::resource('customers', CustomerController::class)
        ->middleware('permission:customers.view');

    /*
    | Products & Masters
    */
    Route::resource('products', ProductController::class)->middleware('permission:products.view');
    Route::resource('categories', CategoryController::class)->middleware('permission:categories.view');
    Route::resource('subcategories', SubCategoryController::class)->middleware('permission:subcategories.view');
    Route::resource('brands', BrandController::class)->middleware('permission:brands.view');
    Route::resource('units', UnitController::class)->middleware('permission:units.view');
    Route::resource('crops', CropController::class)->middleware('permission:crops.view');
    Route::resource('seasons', SeasonController::class)->middleware('permission:seasons.view');

    /*
    | Marketing
    */
    Route::resource('coupons', CouponController::class)->middleware('permission:coupons.view');
    Route::resource('campaigns', CampaignController::class)->middleware('permission:campaigns.view');

    /*
    | System
    */
    Route::resource('users', UserController::class)->middleware('permission:users.view');
    Route::resource('roles', RoleController::class)->middleware('permission:roles.view');
});

require __DIR__ . '/auth.php';
