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
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\DashboardFinanceController;




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
    'controller' => TeamMemberController::class
], function () {
    Route::get('team-members/{startupId}', 'index');
    Route::post('team-members/{startupId}', 'store');
    Route::put('team-members/{startupId}/{teamMemberId}', 'update');
    Route::delete('team-members/{startupId}/{teamMemberId}', 'destroy');
});



Route::group([
    'middleware' => 'authenticated',
    'controller' => ExpenseController::class
], function () {
    Route::get('expenses', 'index');
    Route::post('expenses', 'store');
    Route::put('expenses', 'update');
    Route::delete('expenses', 'destroy');
  });  
Route::group([
    'middleware' => 'authenticated',
    'controller' => DashboardFinanceController::class
], function () {
    Route::get('total-expenses-incomes','getTotalForCurrentYear');
    Route::get('monthly-breakdown','getMonthlyBreakdown');
  });  


  Route::group([
    'middleware' => 'authenticated',
    'controller' => IncomeController::class
], function () {
    Route::get('incomes', 'index');     
    Route::post('incomes', 'store');    
    Route::put('incomes', 'update');    
    Route::delete('incomes', 'destroy'); 
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
