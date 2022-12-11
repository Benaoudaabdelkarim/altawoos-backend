<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RealestateController;
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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::get('/product/guest', [RealestateController::class, 'getRealestateForGuest']);
Route::get('/product/featred', [RealestateController::class, 'getFeatered']);
Route::get('/product/details/guest/{realestate}', [RealestateController::class, 'showForGuest']);//this one

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/plugin', [AuthController::class, 'pluginToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    Route::resource('/product', RealestateController::class);

});
