<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SalesReportController;

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

Route::middleware(['auth:sanctum'])->group(function(){

   Route::get('/authenticate', [UserController::class, 'authResponse']);

   Route::get('/logout', [UserController::class, 'authRequestLogout']);

   Route::get('/sales/{id}', [SalesReportController::class, 'personalSales']);

}); 