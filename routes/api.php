<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('resquests-history', [RequestController::class, 'history']); 
Route::post('create-new-resquest', [RequestController::class, 'createNewRequest']); 

Route::post('get-request-details', [RequestController::class, 'getRequestDetails']); 
Route::post('get-request-images', [RequestController::class, 'getRequestImages']); 


Route::post('get-client-contracts', [RequestController::class, 'getClientContracts']); 
Route::post('get-zones', [RequestController::class, 'getZones']); 
Route::post('get-sub-zones', [RequestController::class, 'getSubZones']); 
Route::post('get-base-units', [RequestController::class, 'getBaseUnits']); 
Route::post('get-service-groups', [RequestController::class, 'getServiceGroups']); 
Route::post('get-service-group-details', [RequestController::class, 'getServiceGroupDetails']); 

// ------------- user controller routes

Route::post('login', [UserController::class, 'login']); 

Route::post('get-user-profile', [UserController::class, 'getUserProfile']); 




