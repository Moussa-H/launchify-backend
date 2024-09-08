<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\StartupSectorController;
use App\Http\Controllers\StartupInvestmentSourceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;


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
    Route::post('startup', 'createOrUpdateStartup'); 
    Route::put('startup/{id}', 'createOrUpdateStartup'); 
    Route::post('startup/investinfo/{id}', 'type_size_Invest'); 
    Route::delete('startup/investinfo/{id}', 'deleteTypeSizeInvest'); 
});


Route::group([
    'middleware' => 'authenticated',
    'controller' => StartupSectorController::class
], function () {
    Route::get('sectors/{startupId}', 'getSectors');  
    Route::post('sectors/{startupId}', 'add_updateSectors'); 
   Route::put('sectors/{startupId}', 'add_updateSectors');
     Route::delete('sectors/{startupId}/{sectorId}', 'removeSector');
});



Route::group([
    'middleware' => 'authenticated',
    'controller' => StartupInvestmentSourceController::class
], function () {
    Route::get('investment-sources/{startupId}', 'getInvestmentSources');
    Route::post('investment-sources/{startupId}', 'createOrUpdateInvestmentSources');
     Route::put('investment-sources/{startupId}', 'add_updateInvestmentSources');
    Route::delete('investment-sources/{startupId}', 'removeInvestmentSource');
 

});



Route::group([
    'middleware' => 'authenticated',
    'controller' => ExpenseController::class
], function () {
    Route::get('expenses/{startupId}', 'index');
    Route::post('expenses/{startupId}', 'store');
    Route::put('expenses/{startupId}', 'update');
    Route::delete('expenses/{startupId}', 'destroy');
 
  });  


  Route::group([
    'middleware' => 'authenticated',
    'controller' => IncomeController::class
], function () {
    Route::get('incomes/{startupId}', 'index');     // Get incomes for the startup by year and month
    Route::post('incomes/{startupId}', 'store');    // Create or update income for the startup
    Route::put('incomes/{startupId}', 'update');    // Update income for the startup
    Route::delete('incomes/{startupId}', 'destroy'); // Delete income for the startup
});


 
Route::get('/sectors', [SectorController::class, 'getAllSectors']);


Route::middleware('auth:api')->group(function () {
    Route::get('/getRole', [UserController::class, 'getRole']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('startup-sectors', StartupSectorController::class);
});
