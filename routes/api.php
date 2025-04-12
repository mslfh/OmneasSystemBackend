<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleHistoryController;
use App\Http\Controllers\ServiceAppointmentController;
use App\Http\Controllers\SystemSettingController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/packages', [PackageController::class, 'index']); // Public route to fetch packages
Route::get('/packages/{id}', [PackageController::class, 'show']); // Public route to fetch a single package
Route::get('/packages-with-service', [PackageController::class, 'getPackageWithService']); // Public route to fetch packages

Route::get('/services', [ServiceController::class, 'index']); // Public route to fetch services
Route::get('/services/{id}', [ServiceController::class, 'show']); // Public route to fetch a single service

Route::get('get-service-by-package/{id}', [ServiceController::class, 'getServiceByPackage']);

Route::get('/staff', [StaffController::class, 'index']); // Public route to fetch staff
Route::get('/staff/{id}', [StaffController::class, 'show']); // Public route to fetch a single staff
Route::get('/get-available-staff-from-scheduletime', [StaffController::class, 'getAvailableStaffFromScheduletime']);


Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);

Route::get('/get-available-shedules', [ScheduleController::class, 'getAvailableShedules']);
Route::get('/get-unavailable-time-from-date', [ScheduleController::class, 'getUnavailableTimeFromShedules']);

Route::post('/make-appointment', [AppointmentController::class, 'makeAppointment']);
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::get('/getServiceAppointments/{id}', [AppointmentController::class, 'getServiceAppointments']);
Route::put('/cancel-appointments/{id}', [AppointmentController::class, 'cancelAppointments']);

// Route::get('get-available-shedules-by-staff/{id}', [ScheduleController::class, 'getAvailableShedulesByStaff']);
// Route::get('get-available-shedules-by-staff-and-date/{id}', [ScheduleController::class, 'getAvailableShedulesByStaffAndDate']);


// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function () {
    // Protected routes
    Route::apiResource('packages', PackageController::class)->except(['index','show']);
    Route::apiResource('services', ServiceController::class)->except(['index','show']);
    Route::apiResource('schedules', ScheduleController::class)->except(['index','show']);
    Route::apiResource('appointments', AppointmentController::class)->except(['index','show']);
    Route::get('/getBookedServiceByDate', [AppointmentController::class, 'getBookedServiceByDate']);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('schedule-histories', ScheduleHistoryController::class);
    Route::apiResource('service-appointments', ServiceAppointmentController::class);
    Route::apiResource('staff', StaffController::class)->except(['index','show']);
    Route::apiResource('user', UserController::class);
    Route::apiResource('system-setting', SystemSettingController::class);
    Route::post('/importUser', [UserController::class, 'importUser']);
    Route::get('/findUserByField', [UserController::class, 'findByField']);
    Route::post('/insertSchedule', [ScheduleController::class, 'insert']);
});

