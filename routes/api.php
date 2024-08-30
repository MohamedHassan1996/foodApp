<?php

use App\Http\Controllers\Api\Private\Product\CategoryController;
use App\Http\Controllers\Api\Private\User\UserController;
use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Http\Controllers\Api\Public\Auth\CustomerAuthController;
use App\Models\Product\Category;
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
    Route::post('send-otp', [CustomerAuthController::class, 'sendOtp']);

});

Route::prefix('v1/admin/auth')->group(function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('v1/admin/users')->group(function(){
    Route::get('', [UserController::class, 'allUsers']);
    Route::post('create', [UserController::class, 'create']);
    Route::get('edit', [UserController::class, 'edit']);
    Route::put('update', [UserController::class, 'update']);
    Route::delete('delete', [UserController::class, 'delete']);
    Route::put('changestatus', [UserController::class, 'changeStatus']);
});

Route::prefix('v1/admin/categories')->group(function(){
    Route::get('', [CategoryController::class, 'allCategories']);
    Route::post('create', [CategoryController::class, 'create']);
    Route::get('edit', [CategoryController::class, 'edit']);
    Route::put('update', [CategoryController::class, 'update']);
    Route::delete('delete', [CategoryController::class, 'delete']);
});

