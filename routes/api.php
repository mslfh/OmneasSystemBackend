<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\ComboItemController;
use App\Http\Controllers\ComboProductController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\PrintLogController;
use App\Http\Controllers\PrintTemplateController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductItemController;
use App\Http\Controllers\ProductProfileController;
use App\Http\Controllers\ProfileController;

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Schedule management
    Route::apiResource('schedules', ScheduleController::class);
    Route::post('/insert-schedule', [ScheduleController::class, 'insert']);
    Route::get('/getStaffSchedule', [ScheduleController::class, 'getStaffSchedule']);

    // Order management
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/finishOrder', [OrderController::class, 'finishOrder']);

    // Schedule management
    Route::apiResource('schedules', ScheduleController::class);
    Route::post('/insert-schedule', [ScheduleController::class, 'insert']);
    Route::get('/getStaffSchedule', [ScheduleController::class, 'getStaffSchedule']);

    // Staff management
    Route::apiResource('staff', StaffController::class);

    // User management
    Route::apiResource('user', UserController::class);
    Route::get('/search-user-by-field', [UserController::class, 'getByKeyword']);

    // Voucher management
    Route::apiResource('vouchers', VoucherController::class);
    Route::post('vouchers/bulk', [VoucherController::class, 'bulkStore']);
    Route::post('vouchers/verify', [VoucherController::class, 'verify']);
    Route::post('vouchers/verifyValidCode', [VoucherController::class, 'verifyValidCode']);

    // System settings
    Route::apiResource('system-setting', SystemSettingController::class);
    Route::get('/getSystemSettingByKey', [SystemSettingController::class, 'getSystemSettingByKey']);

    // Category management
    Route::apiResource('categories', CategoryController::class);
    Route::get('/categories/active', [CategoryController::class, 'getActiveCategories']);
    Route::get('/categories/parent/{parentId}', [CategoryController::class, 'getByParentId']);
    Route::get('/categories/field', [CategoryController::class, 'getByField']);
    Route::get('/categories/{id}/exists', [CategoryController::class, 'exists']);
    Route::get('/categories/count', [CategoryController::class, 'count']);

    // Attribute management
    Route::apiResource('attributes', AttributesController::class);
    Route::get('/attributes/field', [AttributesController::class, 'getByField']);
    Route::get('/attributes/{id}/exists', [AttributesController::class, 'exists']);
    Route::get('/attributes/count', [AttributesController::class, 'count']);

    // Item management
    Route::apiResource('items', ItemController::class);
    Route::get('/items/field', [ItemController::class, 'getByField']);
    Route::get('/items/price-range', [ItemController::class, 'getByPriceRange']);
    Route::get('/items/{id}/exists', [ItemController::class, 'exists']);
    Route::get('/items/count', [ItemController::class, 'count']);

    // Combo management
    Route::apiResource('combos', ComboController::class);
    Route::get('/combos/active', [ComboController::class, 'getActiveCombos']);

    // Combo Item management
    Route::apiResource('combo-items', ComboItemController::class);
    Route::get('/combo-items/combo/{comboId}', [ComboItemController::class, 'getByComboId']);

    // Combo Product management
    Route::apiResource('combo-products', ComboProductController::class);
    Route::get('/combo-products/combo/{comboId}', [ComboProductController::class, 'getByComboId']);

    // Order Item management
    Route::apiResource('order-items', OrderItemController::class);
    Route::get('/order-items/order/{orderId}', [OrderItemController::class, 'getByOrderId']);

    // Order Payment management
    Route::apiResource('order-payments', OrderPaymentController::class);
    Route::get('/order-payments/order/{orderId}', [OrderPaymentController::class, 'getByOrderId']);

    // Printer management
    Route::apiResource('printers', PrinterController::class);
    Route::get('/printers/active', [PrinterController::class, 'getActivePrinters']);

    // Print Log management
    Route::apiResource('print-logs', PrintLogController::class);
    Route::get('/print-logs/printer/{printerId}', [PrintLogController::class, 'getByPrinterId']);

    // Print Template management
    Route::apiResource('print-templates', PrintTemplateController::class);
    Route::get('/print-templates/active', [PrintTemplateController::class, 'getActiveTemplates']);

    // Product management
    Route::apiResource('products', ProductController::class);

    // Product Attribute management
    Route::apiResource('product-attributes', ProductAttributeController::class);
    Route::get('/product-attributes/product/{productId}', [ProductAttributeController::class, 'getByProductId']);

    // Product Item management
    Route::apiResource('product-items', ProductItemController::class);
    Route::get('/product-items/product/{productId}', [ProductItemController::class, 'getByProductId']);

    // Product Profile management
    Route::apiResource('product-profiles', ProductProfileController::class);

    // Profile management
    Route::apiResource('profiles', ProfileController::class);

    // Order management
    Route::apiResource('orders', OrderController::class);

    // Statistics routes
    Route::get('/getStaffScheduleStatistics', [ScheduleController::class, 'getStaffScheduleStatistics']);
});

Route::get('/phpinfo', function () {
    phpinfo();
});
