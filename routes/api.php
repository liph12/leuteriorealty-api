<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SalesReportController;
use App\Http\Controllers\API\AppSupportController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\DeveloperController;
use App\Http\Controllers\API\ProjectsController;

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

Route::middleware('frameguard')->group(function () {

   Route::post('/auth-login', [UserController::class, 'authRequestLogin']);

   Route::post('/confirm-email', [UserController::class, 'requestStoreUser']);
   
   Route::get('/referrer/{token}', [UserController::class, 'getReferrer']);
   
   Route::post('/submit-verification', [UserController::class, 'submitVerificationRequest']);
   
   Route::get('/get-municipality-brgy/{cityId}', [AddressController::class, 'getBarangays']);
   
   Route::post('/save-basic-info', [UserController::class, 'saveBasicInfo']);
   
   Route::post('/save-additional-info', [UserController::class, 'saveAdditionalInfo']);
   
   Route::post('/update-account', [UserController::class, 'updateAccount']);

   Route::get('/user/{email}', [UserController::class, 'getUser']);
   
   Route::middleware(['auth:sanctum'])->group(function(){
   
      Route::get('/authenticate', [UserController::class, 'authResponse']);
   
      Route::get('/logout', [UserController::class, 'authRequestLogout']);
   
      Route::get('/inviter-id/{id}', [UserController::class, 'getInviterName']);
   
      Route::get('/sales/{id}/{search}', [SalesReportController::class, 'personalSales']);
   
      Route::get('/referral-link/{id}', [UserController::class, 'referralLink']);
   
      Route::get('/specific-sales/{id}', [SalesReportController::class, 'specificSale']);
   
      Route::get('/total-sales/{id}', [SalesReportController::class, 'summarySales']);
   
      Route::get('/yearly-sales/{id}', [SalesReportController::class, 'yearlySales']);
   
      Route::get('/support-tickets/{id}', [AppSupportController::class, 'get_support_tickets']);
   
      Route::post('/app-support', [AppSupportController::class, 'create_support_ticket']);
   
      Route::get('/get-developer-list', [DeveloperController::class, 'get_developer_list']);
   
      Route::get('/get-brokerage-list', [DeveloperController::class, 'get_brokerage_list']);
   
      Route::get('/get-property-type', [DeveloperController::class, 'get_categories']);
   
      Route::get('/get-developer-projects-list/{dev_id}', [ProjectsController::class, 'get_developer_projects_list']);
   });    

});

