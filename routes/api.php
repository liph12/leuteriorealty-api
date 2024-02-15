<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SalesReportController;
use App\Http\Controllers\API\AppSupportController;

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

Route::post('/auth-login', [UserController::class, 'authRequestLogin']);

Route::post('/confirm-email', [UserController::class, 'requestStoreUser']);

Route::get('/referrer/{token}', [UserController::class, 'getReferrer']);

Route::post('/submit-verification', [UserController::class, 'submitVerificationRequest']);

Route::get('/get-municipality-brgy/{cityId}', [AddressController::class, 'getBarangays']);

Route::post('/save-basic-info', [UserController::class, 'saveBasicInfo']);

Route::post('/save-additional-info', [UserController::class, 'saveAdditionalInfo']);

Route::post('/update-account', [UserController::class, 'updateAccount']);

Route::middleware(['auth:sanctum'])->group(function(){

   Route::get('/authenticate', [UserController::class, 'authResponse']);

   Route::get('/logout', [UserController::class, 'authRequestLogout']);

   Route::get('/sales/{id}', [SalesReportController::class, 'personalSales']);

   Route::get('/referral-link/{id}', [UserController::class, 'referralLink']);

   Route::get('/total-sales/{id}', [SalesReportController::class, 'summarySales']);

   Route::get('/yearly-sales/{id}', [SalesReportController::class, 'yearlySales']);

   Route::get('/support-tickets/{id}', [AppSupportController::class, 'get_support_tickets']);

   Route::post('/app-support', [AppSupportController::class, 'create_support_ticket']);
}); 
