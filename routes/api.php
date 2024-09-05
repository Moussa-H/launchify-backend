<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\StartupSectorController;
use App\Http\Controllers\StartupInvestmentSourceController;

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

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// // Startup sectors routes
// Route::prefix('startups/{startupId}/sectors')->group(function () {
//     Route::get('/', [StartupSectorController::class, 'getSectors']);
//     Route::post('/', [StartupSectorController::class, 'addSectors']);
//     Route::put('/', [StartupSectorController::class, 'updateSectors']);
//     Route::delete('/{sectorId}', [StartupSectorController::class, 'removeSector']);
// });
Route::group([
    "middleware" => "authenticated",
    "controller" => SectorController::class
], function () {
   // Route::get('/sectors', 'getAllSectors');
     Route::get('sectors/startup/{startupId}', 'getSectorsByStartup');
      Route::put('sectors/startup/{startupId}', 'updateSectorsForStartup');
     Route::post('sectors/startup/{startupId}', 'createSectorsForStartup');
       Route::delete('sectors/startup/{startupId}', 'deleteSectorFromStartup');
    
});
Route::group([
    "middleware" => "authenticated",
    "controller" => StartupController::class
], function () {
    Route::get('startup', 'getstartup');   
});



Route::group([
    'middleware' => 'authenticated',
    'controller' => StartupSectorController::class
], function () {
    Route::get('sectors/{startupId}', 'getSectors');  // GET sectors for a startup
    Route::post('sectors/{startupId}', 'addSectors'); // Add sectors to a startup
  
});

Route::group([
    'middleware' => 'authenticated',
    'controller' => StartupInvestmentSourceController::class
], function () {
    Route::get('investment-sources/{startupId}', 'getInvestmentSources');
    Route::post('investment-sources/{startupId}', 'addInvestmentSources');
 
    
});
  // Route::get('/', 'index');
    // Route::get('/{id}',  'readMessage');
    // Route::post('/', 'store');
    // Route::delete('/{id}',  'destroy');
    // Route::put('/{id}',  'update');
    // Route::get('startups/user', 'getByUser');
// Test route to check authenticated user

// Route::middleware('auth.token')->group(function () {
//     Route::get('/startups', [StartupController::class, 'index']);
//     Route::get('/startups', [StartupController::class, 'show']);
//     Route::post('/startups', [StartupController::class, 'store']);
//     Route::put('/startups/{id}', [StartupController::class, 'update']);
//     Route::delete('/startups/{id}', [StartupController::class, 'destroy']);
//      Route::get('/startups/user', [StartupController::class, 'getByUser']);
// });

// Sectors route (no authentication required)
Route::get('/sectors', [SectorController::class, 'getAllSectors']);

// User routes with authentication
Route::middleware('auth:api')->group(function () {
    Route::get('/getRole', [UserController::class, 'getRole']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Startup sector API resource routes (with authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('startup-sectors', StartupSectorController::class);
});
