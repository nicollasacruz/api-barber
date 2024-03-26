<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\ScheduleController;
use App\Models\Barbershop;
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
    Route::prefix('barbershops')->group(function () {
        Route::get('/', [BarbershopController::class, 'index']);
        Route::post('/', [BarbershopController::class, 'store']);
        Route::get('/{barbershop}', [BarbershopController::class, 'show']);
        Route::patch('/{barbershop}', [BarbershopController::class, 'update'])->can('update', 'barbershop');
        Route::delete('/{barbershop}', [BarbershopController::class, 'destroy'])->can('destroy', 'barbershop');
        Route::delete('/forceDelete/{barbershop}', [BarbershopController::class, 'forceDestroy'])->can('forceDestroy', 'barbershop');
        Route::post('/restore/{barbershop}', [BarbershopController::class, 'restore'])->can('restore', 'barbershop');
    });
    Route::prefix('schedules')->group(function () {
        Route::get('/', [ScheduleController::class, 'index']);
        Route::post('/', [ScheduleController::class, 'store']);
        Route::get('/{schedule}', [ScheduleController::class, 'show']);
        Route::patch('/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->can('destroy', 'schedule');
        Route::delete('/forceDelete/{schedule}', [ScheduleController::class, 'forceDestroy']);
        Route::post('/restore/{schedule}', [ScheduleController::class, 'restore']);
    });
});
