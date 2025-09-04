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

//Client-Side routes
Route::get('/get-products', [ProductController::class, 'getAllProducts']);
Route::get('/get-product/{id}', [ProductController::class, 'getProductById']);
Route::get('/get-product-customization/{id}', [ProductController::class, 'getProductCustomization']);
Route::get('/get-categories', [CategoryController::class, 'getAllCategories']);
Route::get('/get-category/{id}', [CategoryController::class, 'getCategoryById']);

Route::get('/get-attribute-type', [AttributesController::class, 'getAttributeType']);
Route::get('/get-item-type', [ItemController::class, 'getItemType']);
Route::post('/get-bulk-items', [ItemController::class, 'getBulkItemsByIds']);
Route::post('/place-order', [OrderController::class, 'placeOrder']);


// Protected routes
// Route::middleware(['auth:sanctum'])->group(function () {
    // Schedule management
    Route::post('/insert-schedule', [ScheduleController::class, 'insert']);
    Route::get('/getStaffSchedule', [ScheduleController::class, 'getStaffSchedule']);
    Route::get('/getStaffScheduleStatistics', [ScheduleController::class, 'getStaffScheduleStatistics']);
    Route::apiResource('schedules', ScheduleController::class);

    // Order management
    Route::post('/orders/finishOrder', [OrderController::class, 'finishOrder']);

    // Staff management
    Route::apiResource('staff', StaffController::class);

    // User management
    Route::get('/search-user-by-field', [UserController::class, 'getByKeyword']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/change-password/{id}', [UserController::class, 'changeUserPassword']);
    Route::apiResource('user', UserController::class);

    // Voucher management
    Route::post('vouchers/bulk', [VoucherController::class, 'bulkStore']);
    Route::post('vouchers/verify', [VoucherController::class, 'verify']);
    Route::post('vouchers/verifyValidCode', [VoucherController::class, 'verifyValidCode']);
    Route::apiResource('vouchers', VoucherController::class);

    // System settings
    Route::get('/getSystemSettingByKey', [SystemSettingController::class, 'getSystemSettingByKey']);
    Route::apiResource('system-setting', SystemSettingController::class);

    // Category management
    Route::get('/categories/active', [CategoryController::class, 'getActiveCategories']);
    Route::get('/categories/parent/{parentId}', [CategoryController::class, 'getByParentId']);
    Route::get('/categories/field', [CategoryController::class, 'getByField']);
    Route::get('/categories/{id}/exists', [CategoryController::class, 'exists']);
    Route::get('/categories/count', [CategoryController::class, 'count']);
    Route::apiResource('categories', CategoryController::class);


    // Attribute management
    Route::get('/attributes/group', [AttributesController::class, 'getGroupAttributes']);
    Route::get('/attributes/field', [AttributesController::class, 'getByField']);
    Route::get('/attributes/{id}/exists', [AttributesController::class, 'exists']);
    Route::get('/attributes/count', [AttributesController::class, 'count']);
    Route::apiResource('attributes', AttributesController::class);

    // Item management
    Route::get('/items/active', [ItemController::class, 'getActiveItems']);
    Route::get('/items/field', [ItemController::class, 'getByField']);
    Route::get('/items/price-range', [ItemController::class, 'getByPriceRange']);
    Route::get('/items/{id}/exists', [ItemController::class, 'exists']);
    Route::get('/items/count', [ItemController::class, 'count']);
    Route::apiResource('items', ItemController::class);

    // Combo management
    Route::get('/combos/active', [ComboController::class, 'getActiveCombos']);
    Route::apiResource('combos', ComboController::class);

    // Combo Item management
    Route::get('/combo-items/combo/{comboId}', [ComboItemController::class, 'getByComboId']);
    Route::apiResource('combo-items', ComboItemController::class);

    // Combo Product management
    Route::get('/combo-products/combo/{comboId}', [ComboProductController::class, 'getByComboId']);
    Route::apiResource('combo-products', ComboProductController::class);

    // Order Item management
    Route::get('/order-items/order/{orderId}', [OrderItemController::class, 'getByOrderId']);
    Route::apiResource('order-items', OrderItemController::class);

    // Order Payment management
    Route::get('/order-payments/order/{orderId}', [OrderPaymentController::class, 'getByOrderId']);
    Route::apiResource('order-payments', OrderPaymentController::class);

    // Printer management
    Route::get('/printers/active', [PrinterController::class, 'getActivePrinters']);
    Route::apiResource('printers', PrinterController::class);

    // Print Log management
    Route::get('/print-logs/printer/{printerId}', [PrintLogController::class, 'getByPrinterId']);
    Route::apiResource('print-logs', PrintLogController::class);

    // Print Template management
    Route::get('/print-templates/active', [PrintTemplateController::class, 'getActiveTemplates']);
    Route::apiResource('print-templates', PrintTemplateController::class);

    // Product management
    Route::get('/products/active', [ProductController::class, 'getActiveProducts']);
    Route::apiResource('products', ProductController::class);

    // Product Attribute management
    Route::get('/product-attributes/product/{productId}', [ProductAttributeController::class, 'getByProductId']);
    Route::apiResource('product-attributes', ProductAttributeController::class);

    // Product Item management
    Route::get('/product-items/product/{productId}', [ProductItemController::class, 'getByProductId']);
    Route::apiResource('product-items', ProductItemController::class);

    // Product Profile management
    Route::apiResource('product-profiles', ProductProfileController::class);

    // Profile management
    Route::apiResource('profiles', ProfileController::class);


    Route::post('orders/staff-place', [OrderController::class, 'placeStaffOrder']);
    Route::get('orders/fetch-new-order/{latestId}', [OrderController::class, 'fetchNewOrder']);
    Route::apiResource('orders', OrderController::class);

// });

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});
