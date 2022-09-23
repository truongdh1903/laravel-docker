<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/order/create', [PackageController::class, 'create']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/order', [PackageController::class, 'index']);
    Route::put('/user/{id}/edit', [UserController::class, 'update']);
    Route::delete('/user/{id}/delete', [UserController::class, 'delete']);
});

