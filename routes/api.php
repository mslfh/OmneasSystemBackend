<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
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
use App\Http\Controllers\SpecialRoleController;

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes that don't require authentication

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Schedule management
    Route::apiResource('schedules', ScheduleController::class);
    Route::post('/insert-schedule', [ScheduleController::class, 'insert']);
    Route::get('/getStaffSchedule', [ScheduleController::class, 'getStaffSchedule']);

    // Order management
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/finishOrder', [OrderController::class, 'finishOrder']);

    // Staff management
    Route::apiResource('staff', StaffController::class)->except(['index', 'show']);

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
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::get('/categories/active', [CategoryController::class, 'getActiveCategories']);
    Route::get('/categories/parent/{parentId}', [CategoryController::class, 'getByParentId']);

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

    // Statistics routes
    Route::get('/getStaffScheduleStatistics', [ScheduleController::class, 'getStaffScheduleStatistics']);
});

Route::get('/phpinfo', function () {
    phpinfo();
});
