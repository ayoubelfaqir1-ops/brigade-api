<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\api\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Public routes — no token needed
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/{category}/plats', [CategoryController::class, 'plats']);
    Route::get('/profile',[ProfileController::class, 'show']);
    Route::patch('/profile',[ProfileController::class, 'update']);
    Route::apiResource('plats', PlatController::class);
    Route::post('plats/{plat}/image', [PlatController::class, 'updateImage']);
});