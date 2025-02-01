<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TestController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'registration']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::resource('orders', OrderController::class);

// web.php


Route::apiResource('orders', OrderController::class);
Route::get('/completeOrders', [OrderController::class, 'complete']);


Route::resource('food-items', FoodItemController::class);

Route::apiResource('categories', CategoryController::class);


Route::post('/test/store', [TestController::class, 'store']); // For storing data
Route::get('/test/show', [TestController::class, 'show']);   // For showing data
