<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RMController;
use App\Http\Controllers\PPMController;
use App\Http\Controllers\OthersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;



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

Route::post('login', [UserController::class,'login']); 

// Route::post('/login', function () {
//     // return "hare";

    
//     abort(404, 'not found'); 
//     return "caches cleared";

// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum','isActive']], function(){

// ----------- RM routes
Route::post('get-pending-rm', [RMController::class, 'getPendingRM']);  
Route::post('get-wo-images', [RMController::class, 'getWOImage']);  
Route::post('get-wo-status', [RMController::class, 'getWOStatus']);  
Route::post('get-rm-history', [RMController::class, 'getRMHistory']);  
Route::post('update-wo-status', [RMController::class, 'updateWOStatus']);  
Route::post('get-all-reasons', [RMController::class, 'getAllReasons']);  
Route::post('save-wo-image', [RMController::class, 'saveWOImage']);  

Route::post('get-material-history', [RMController::class, 'getMaterialHistory']);  
Route::post('get-rm-wo-history', [RMController::class, 'getRMWOHistory']);  
Route::post('get-wo-count', [RMController::class, 'getWOCount']);  
Route::post('get-single-wo-history', [RMController::class, 'getSingleWOHistory']);  
Route::get('test-canias-login', [RMController::class, 'caniasLogin']);  
Route::get('test-wsdl-web', [RMController::class, 'testWSDlWeb']);  



// ----------- PPM routes
Route::post('get-pending-ppm', [PPMController::class, 'getPendingPPM']);  
Route::post('get-ppm-tasks-list', [PPMController::class, 'getPPMWOTasks']);  
Route::post('get-metering-parameters', [PPMController::class, 'getMeteringParametesLOV'])->withoutMiddleware("throttle:api")
->middleware("throttle:900:1");  
Route::post('update-wo-task', [PPMController::class, 'updateWOTask']);  
Route::post('get-ppm-history', [PPMController::class, 'getPPMHistory']);  

// ------------- inventory routes
Route::post('get-all-stores', [InventoryController::class, 'getAllStores']);  
Route::post('get-all-staff-list', [InventoryController::class, 'getAllStaffList']);  
Route::post('get-general-stores', [InventoryController::class, 'getGeneralStores']);  
Route::post('get-general-store-categories', [InventoryController::class, 'getGeneralStoreCategories']);  
Route::post('get-store-items', [InventoryController::class, 'getStoreItems']);  

// ------------- others controller routes
Route::post('get-customers-list', [OthersController::class, 'getCustomersList']);  
Route::post('get-customer-contracts-list', [OthersController::class, 'getCustomersContractList']);  
Route::post('get-contract-sites-list', [OthersController::class, 'getContractSitesList']);  

Route::post('get-request-contracts-list', [OthersController::class, 'getReqeustCustomersContractList']);  

Route::post('get-customer-baseunits', [OthersController::class, 'getClientBaseUnits']);  
Route::post('get-request-baseunits', [OthersController::class, 'getRequestBaseUnits']);  

Route::post('get-assets-list', [OthersController::class, 'getAssetsByContractId']);  
Route::post('get-oem-list', [OthersController::class, 'getOEMList']);  
Route::post('get-makes-list', [OthersController::class, 'getMakes']);  
Route::post('get-models-list', [OthersController::class, 'getModels']);  
Route::post('get-asset-master-categories', [OthersController::class, 'getAssetMasterCategories']);  
Route::post('get-asset-categories', [OthersController::class, 'getAssetCategories']);  
Route::post('get-asset-sub-categories', [OthersController::class, 'getAssetSubCategories']);  
Route::post('get-functional-status-list', [OthersController::class, 'getFunctionalStatusList']);  
Route::post('get-asset-conditions-list', [OthersController::class, 'getAssetConditionList']);  
Route::post('create-new-asset', [OthersController::class, 'createAsset']);  
Route::post('create-new-asset-test-abc', [OthersController::class, 'createAsset']);  

Route::post('update-asset', [OthersController::class, 'updateAsset']);  
Route::post('get-cost-booking-history', [OthersController::class, 'getCostBookingHistory']);  
Route::post('get-enfm-staff', [OthersController::class, 'getENFMStaff']);  
Route::post('get-asset-history', [OthersController::class, 'getAssetHistory']);   
Route::post('get-service-groups', [OthersController::class, 'getServiceGroups']); 
Route::post('get-service-group-details', [OthersController::class, 'getServiceGroupDetails']); 
Route::post('get-priority-list', [OthersController::class, 'getPriority']); 
Route::post('save-wo-cost-booking', [OthersController::class, 'saveWOCostBooking']); 
Route::post('save-service-request', [OthersController::class, 'saveServiceRequest']); 
Route::post('save-service-request-image', [OthersController::class, 'saveRequestImage']); 
Route::post('save-material-booking', [OthersController::class, 'saveMaterialBooking']); 

// ------------- user controller routes


Route::post('get-user-profile', [UserController::class, 'getUserProfile']); 
Route::post('update-user-profile', [UserController::class, 'updateProfile']); 

});



