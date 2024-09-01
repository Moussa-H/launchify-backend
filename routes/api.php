<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\StartupSectorController;
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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


Route::get('/startups/{startupId}/sectors', [StartupSectorController::class, 'getSectors']);
Route::post('/startups/{startupId}/sectors', [StartupSectorController::class, 'addSectors']);
Route::put('/startups/{startupId}/sectors', [StartupSectorController::class, 'updateSectors']);
Route::delete('/startups/{startupId}/sectors/{sectorId}', [StartupSectorController::class, 'removeSector']);



Route::get('/sectors', [SectorController::class, 'getAllSectors']);
Route::apiResource('startups', StartupController::class);
Route::apiResource('startup-sectors', StartupSectorController::class);
Route::put('/startups/{id}', [StartupController::class, 'update']);
Route::middleware('auth:api')->get('/getRole', [UserController::class, 'getRole']);
Route::get('/users', [UserController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

