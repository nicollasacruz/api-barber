<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedImageController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
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
        Route::get('/{barbershop}', [BarbershopController::class, 'show'])->where('barbershop', '[0-9]+');
        Route::patch('/{barbershop}', [BarbershopController::class, 'update'])->where('barbershop', '[0-9]+')->can('update', 'barbershop');
        Route::delete('/{barbershop}', [BarbershopController::class, 'destroy'])->where('barbershop', '[0-9]+')->can('destroy', 'barbershop');
        Route::delete('/forceDelete/{barbershop}', [BarbershopController::class, 'forceDestroy'])->where('barbershop', '[0-9]+')->can('forceDestroy', 'barbershop');
        Route::post('/restore/{barbershop}', [BarbershopController::class, 'restore'])->where('barbershop', '[0-9]+')->can('restore', 'barbershop');
        Route::get('/{barbershop}/barbers', [BarbershopController::class, 'getBarbers'])->where('barbershop', '[0-9]+');

        Route::prefix('/{barbershop}/barbers')->group(function () {
            Route::post('/', [UserController::class, 'storeBarber'])->where('barbershop', '[0-9]+');
            Route::get('/{barber}', [UserController::class, 'showBarber'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::patch('/{barber}', [UserController::class, 'updateBarber'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::post('/{barber}/schedule', [UserController::class, 'scheduleService'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::post('/{barber}/available-schedules', [UserController::class, 'getAvailableSchedules'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::delete('/{barber}', [UserController::class, 'destroy'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::delete('/forceDelete/{barber}', [UserController::class, 'forceDestroy'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
            Route::post('/restore/{barber}', [UserController::class, 'restore'])->where('barbershop', '[0-9]+')->where('barber', '[0-9]+');
        });

        Route::prefix('/{barbershop:[0-9]+}/services')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->can('showAny', ['service'])->where('barbershop', '[0-9]+');
            Route::post('/', [ServiceController::class, 'store'])->can('store', ['service'])->where('barbershop', '[0-9]+');
            Route::get('/{service:[0-9]+}', [ServiceController::class, 'show'])->can('show', ['service'])->where('barbershop', '[0-9]+')->where('service', '[0-9]+');
            Route::patch('/{service:[0-9]+}', [ServiceController::class, 'update'])->can('uodate', ['service'])->where('barbershop', '[0-9]+')->where('service', '[0-9]+');
            Route::delete('/{service:[0-9]+}', [ServiceController::class, 'destroy'])->can('destroy', ['service'])->where('barbershop', '[0-9]+')->where('service', '[0-9]+');
            Route::delete('/forceDelete/{service:[0-9]+}', [ServiceController::class, 'forceDestroy'])->can('forceDestroy', ['service'])->where('barbershop', '[0-9]+')->where('service', '[0-9]+');
            Route::post('/restore/{service:[0-9]+}', [ServiceController::class, 'restore'])->can('restore', ['service'])->where('barbershop', '[0-9]+')->where('service', '[0-9]+');
        });
    });
    Route::prefix('barbershops/{barbershop:[0-9]+}/schedules')->group(function () {
        Route::get('/getAvailableSchedules', [ScheduleController::class, 'getAvailableSchedules'])->where('barbershop', '[0-9]+');
        Route::get('/', [ScheduleController::class, 'index'])->where('barbershop', '[0-9]+');
        Route::post('/', [ScheduleController::class, 'store'])->where('barbershop', '[0-9]+');
        Route::get('/{schedule}', [ScheduleController::class, 'show'])->where('barbershop', '[0-9]+');
        Route::patch('/{schedule}', [ScheduleController::class, 'update'])->where('barbershop', '[0-9]+');
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->can('destroy', ['schedule']);
        Route::delete('/forceDelete/{schedule}', [ScheduleController::class, 'forceDestroy']);
        Route::post('/restore/{schedule}', [ScheduleController::class, 'restore']);
    });

    Route::prefix('feed')->group(function () {
        Route::get('barbershop/{barbershop}/all', [FeedImageController::class, 'getAllFeedImagesByBarbershop'])->where('barbershop', '[0-9]+');
        Route::post('/barber/{barber}', [FeedImageController::class, 'store'])->where('barber', '[0-9]+');
        Route::get('/{feedImage}', [FeedImageController::class, 'show'])->where('feedImage', '[0-9]+');
        Route::patch('/{feedImage}', [FeedImageController::class, 'update'])->where('feedImage', '[0-9]+');
        Route::delete('/{barber}', [FeedImageController::class, 'destroy'])->can('destroy', ['barber']);
        Route::delete('/forceDelete/{barber}', [FeedImageController::class, 'forceDestroy']);
        Route::post('/restore/{barber}', [FeedImageController::class, 'restore']);

        Route::get('/{feedImage}/commenters', [CommentController::class, 'index'])->where('feedImage', '[0-9]+');
        Route::post('/{feedImage}/commenters', [CommentController::class, 'store'])->where('feedImage', '[0-9]+');
    });

    
});
