<?php

use Illuminate\Http\Request;
use App\Http\Middleware\isOwner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BusController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\API\ScheduleController;


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

// {{ Sanctum Authentication}}
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


// Bus routes
Route::group(['middleware' => ['auth:sanctum', isOwner::class]], function () {
    Route::get('buses', [BusController::class, 'index']);
    Route::post('buses', [BusController::class, 'store']);
    Route::put('buses/{bus}', [BusController::class, 'update']);
    Route::delete('buses/{bus}', [BusController::class, 'destroy']);
    Route::get('buses/{bus}/students', [BusController::class, 'allStudents']);
});
Route::get('buses/{bus}', [BusController::class, 'show'])->middleware('auth:sanctum');
Route::post('buses/join', [BusController::class, 'joinBus'])->middleware('auth:sanctum');

// Schedule routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('schedules', [ScheduleController::class, 'index']);
    Route::post('schedules', [ScheduleController::class, 'store']);
    Route::put('schedules/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy']);
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show']);
    Route::get('schedules/s/next-day', [ScheduleController::class, 'getNextSchedule']);

    //{{ User }}
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'delete']);
    Route::get('/user', [UserController::class, 'getLoggedInUser']);

    //{{ Location }}
    Route::get('/locations', [LocationController::class, 'index']);// Retrieve all locations
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('/locations/{id}', [LocationController::class, 'show']);// Retrieve a specific location
    Route::put('/locations/{id}', [LocationController::class, 'update']);
    Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
});
