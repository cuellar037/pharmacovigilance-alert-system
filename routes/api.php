<?php

use App\Http\Controllers\Api\AlertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('/orders', [OrderController::class, 'index']); 
    Route::get('/orders/export', [OrderController::class, 'export']);
    Route::get('/orders/{order}', [OrderController::class, 'show']); 
    Route::get('/customer/{customer}', [OrderController::class, 'show']); 

    Route::post('/alerts/send', [AlertController::class, 'send']);



});
