<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BusController;
use App\Http\Middleware\isOwner;

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

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


Route::group(['middleware' => ['auth:sanctum', isOwner::class]], function () {
    Route::get('buses', [BusController::class, 'index']);
    Route::post('buses', [BusController::class, 'store']);
    Route::put('buses/{bus}', [BusController::class, 'update']);
    Route::delete('buses/{bus}', [BusController::class, 'destroy']);
});


Route::get('buses/{bus}', [BusController::class, 'show'])->middleware('auth:sanctum');
