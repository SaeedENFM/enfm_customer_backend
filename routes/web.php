<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-url', function () {
    return view('welcome');
});

Route::post('upload-image', [UserController::class, 'uploadImage']);  


Route::get('/emiratesNFM-mobile-app', function () {
    return view('download_app');
});

Route::get('/test-sql', function () {

    // dd(bcrypt('123456') );
    $data = DB::table('Documents')->where('Base64Image' != null  
    )->count(); 
     
    return response()->json(['data'=>$data]);

    // return $users;
    // return "test sql server"; 
});
