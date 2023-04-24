<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/emiratesNFM-mobile-app', function () {
    return view('download_app');
});

Route::get('/test-sql', function () {

    // dd(bcrypt('123456') );
    $data = DB::table('users')->where(['UserName' => 'RashidAC'  
    ]) 
    ->get(); 
     
    return response()->json(['data'=>$data]);

    // return $users;
    // return "test sql server"; 
});
