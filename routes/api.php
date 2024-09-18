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
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\DashboardInvestmentController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MentorDirectoryController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MatchingController;




// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

//
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
    Route::get('startups', 'getAllStartups'); 
    Route::get('startup/{id}', 'getStartupById');
    Route::get('startup', 'getstartup'); 
    Route::post('startup', 'createOrUpdateStartup'); 
    Route::put('startup/{id}', 'createOrUpdateStartup'); 
    Route::post('startup/investinfo/{id}', 'type_size_Invest'); 
    Route::delete('startup/investinfo/{id}', 'deleteTypeSizeInvest'); 
});

Route::post('investments', [InvestmentController::class, 'createInvestment']);

Route::group([
    "middleware" => "authenticated",
    "controller" => InvestmentController::class
], function () {
   Route::get('getInformationPayment','getInformationPayment');
    Route::get('investments/{startup_id}', 'getStartupInvestmentSum');
   
});
Route::group([
    "middleware" => "authenticated",
    "controller" => DashboardInvestmentController::class
], function () {
   Route::get('summary','getInvestorSummary');
 Route::get('getlaststartup','getLastFiveStartups');
  Route::get('getstartups','getAllStartupsInvested');
});



Route::group([
    "middleware" => "authenticated",
    "controller" => InvestorController::class
], function () {
    Route::get('investor', 'getInvestor'); 
    Route::post('investor', 'createOrUpdateInvestor');  
     Route::put('investor/{id}', 'createOrUpdateInvestor');  
});


Route::group([
    'middleware' => 'authenticated',
    'controller' => StartupSectorController::class
], function () {
    Route::get('sectors/{startupId}', 'getSectors');  
    Route::post('sectors/{startupId}', 'addSectors'); 
   Route::put('sectors/{startupId}', 'createOrUpdateSectors');
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
    Route::get('team-members/total-salaries/{startupId}', 'getTotalSalaries');
});


Route::group([
    'middleware' => 'authenticated',
    'controller' => ExpenseController::class
], function () {
    Route::get('expenses', 'index');
    Route::post('expenses', 'store');

  });  


 





//  Route::group([
//     'middleware' => 'authenticated',
//     'controller' => ChatController::class
// ], function () {
// Route::post('chat/send', 'sendMessage');
// Route::get('chat/messages', 'getMessages');
 
// });

Route::post('chat/send',[ChatController::class, 'sendMessage']);
Route::get('chat/messages',[ChatController::class,  'getMessages']);
 
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
Route::post('match', [MatchingController::class, 'match']);