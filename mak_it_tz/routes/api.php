<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::middleware('admin.permission')->group(function () {
            Route::post('', [ProductController::class, 'create']);
            Route::delete('/{id}', [ProductController::class, 'delete']);
            Route::put('/{id}/edit', [ProductController::class, 'edit']);
        });
    });

    Route::prefix('orders')->group(function () {
        Route::get('', [OrderController::class, 'index']);
        Route::post('', [OrderController::class, 'create']);
        Route::delete('/{id}', [OrderController::class, 'delete']);
        Route::put('/{id}/edit', [OrderController::class, 'update']);
    });
});
