<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/test', function (Request $request) {
    return dd(Auth::user());
});

Route::post('login',[LoginController::class,'login']);
// Route::post('login',[LoginController::class,'login'])->middleware(['api-login','throttle']);

Route::post('logout',[]);
Route::resource('vendor','VendorController')->middleware('auth:api');
Route::resource('worker','WorkerController')->middleware('auth:api');
Route::post('worker/store','WorkerController@store');

Route::resource('product','ProductController');
Route::resource('order','OrderController')->middleware('auth:api');
Route::post('order-change-status/{order_id}/{status}','OrderController@orderChangeStatus')->middleware('auth:api');


Route::resource('unit','UnitController')->middleware('auth:api');;

Route::get('material/search','ProductController@materailSearch');

Route::post('vendor/store','VendorController@store');

Route::post('genrate-otp',[CustomerController::class,'genrateOtp']);
Route::post('verify-otp',[CustomerController::class,'verifyOtp']);