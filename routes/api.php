<?php

use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Http\Controllers\Api\Public\Auth\CustomerAuthController;
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

Route::prefix('v1/customer-auth')->group(function(){
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::post('verify-email', [CustomerAuthController::class, 'verify']);
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::post('logout', [CustomerAuthController::class, 'logout']);
});

Route::prefix('v1/admin/auth')->group(function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

