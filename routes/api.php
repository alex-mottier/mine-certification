<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MineController;
use App\Http\Controllers\Api\UserController;
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
Route::prefix('v1')->middleware('api')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('users')->group(function(){
        Route::controller(UserController::class)->group(function() {
            Route::post('', 'store');
        });
    });

    Route::prefix('mines')->group(function(){
        Route::controller(MineController::class)->group(function() {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('{mineId}', 'view');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('users')->group(function(){
            Route::controller(UserController::class)->group(function() {
                Route::get('', 'index');
                Route::put('{userId}', 'update');
                Route::patch('{userId}', 'validateUser');
                Route::delete('{userId}', 'destroy');
                Route::get('{userId}/mines', 'userMines');
            });
        });
        Route::prefix('mines')->group(function(){
            Route::controller(MineController::class)->group(function() {
                Route::patch('{mineId}', 'validateMine');
                Route::prefix('{mineId}/users')->group(function(){
                    Route::post('', 'assign');
                    Route::delete('{userId}', 'revoke');
                });
            });
        });
    });
});

