<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TopUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/payment/cleanup-expired', [TopUpController::class, 'cleanupExpiredPayments']);