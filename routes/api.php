<?php
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('appointments', AppointmentController::class);

// Additional custom routes
Route::get('products/{id}/image', [ProductController::class, 'getImage']);
Route::get('customers/{id}/orders', [CustomerController::class, 'getOrders']);
Route::get('customers/{id}/appointments', [CustomerController::class, 'getAppointments']);

use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

