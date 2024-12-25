<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//API Routes
// Public Routes
Route::post("auth/register", [APIController::class, "register"]);
Route::post("auth/login", [APIController::class, "login"]);
Route::post("auth/resetPassword", [APIController::class, "sendResetLinkEmail"]);

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'getProductById']);
});

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Customer Routes
    Route::group(['middleware' => ['role:customer']], function () {
        Route::get("users/profile", [APIController::class, "profile"]);
        Route::put("users/profile", [APIController::class, "updateProfile"]);
        Route::get("logout", [APIController::class, "logout"]);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
    });

    // Admin Routes
    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });

        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });
});