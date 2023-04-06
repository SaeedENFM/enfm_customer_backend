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

Route::get('/test-sql', function () {

    // dd(bcrypt('123456') );
    $users = DB::table('users')->where(['UserName' => 'RashidAC', 
    // "Password" => "123456"
    ]) 
    ->get(); 
     
    return response()->json(['users'=>$users]);

    // return $users;
    // return "test sql server"; 
});
