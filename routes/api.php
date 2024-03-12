<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
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

Route::post('/auth/register', [AuthController::class, 'store']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {

        return $request->user();
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/barbershops', [BarbershopController::class, 'index']);
    Route::get('/barbershops/{barbershop}', [BarbershopController::class, 'show']);
    Route::post('/barbershops', [BarbershopController::class, 'store']);
    Route::patch('/barbershops/{barbershop}', [BarbershopController::class, 'update']);
    Route::delete('/barbershops/{barbershop}', [BarbershopController::class, 'destroy']);

    Route::get('/barbershops/{barbershop}/icon', [BarbershopController::class, 'getIcon']);
    Route::get('/barbershops/{barbershop}/cover-image', [BarbershopController::class, 'getCoverImage']);
});
