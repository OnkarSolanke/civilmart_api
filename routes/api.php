<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AccessTokenController::class,'issueToken'])->middleware(['api-login','throttle']);
// Route::post('login',[LoginController::class,'login'])->middleware(['api-login','throttle']);

Route::post('logout',[]);
Route::resource('vendor','VendorController');
Route::resource('worker','WorkerController');
Route::post('worker/store','WorkerController@store');

Route::resource('product','ProductController');
Route::get('material/search','ProductController@materailSearch');

Route::post('vendor/store','VendorController@store');