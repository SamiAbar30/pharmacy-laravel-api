<?php

use App\Http\Controllers\paysController;
use App\Http\Controllers\pharmacieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\regionController;
use App\Http\Controllers\villeController;
use App\Http\Controllers\zoneController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FirebaseController;
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


Route::resource('/Authentication',AuthenticationController::class);
Route::resource('/pays',paysController::class);
Route::resource('/region',regionController::class);
Route::resource('/ville',villeController::class);
Route::resource('/zone',zoneController::class);
Route::resource('/pharmacie',pharmacieController::class);
Route::get('/pharmacie/detail/{id}',[pharmacieController::class, 'detail']);


Route::put('/signUpadmin',[FirebaseController::class, 'signUpadmin']);
Route::put('/signUp',[FirebaseController::class, 'signUp']);
Route::put('/signIn',[FirebaseController::class, 'signIn']);
Route::get('/signOut/{id}',[FirebaseController::class, 'signOut']);
Route::get('/Check/{id}',[FirebaseController::class, 'Check']);
Route::get('/users',[FirebaseController::class, 'users']);
Route::get('/users/delete',[FirebaseController::class, 'delete']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
