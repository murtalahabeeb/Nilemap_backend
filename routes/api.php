<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ActivityController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's

    });
    
//Route::apiResource("location", LocationController::class);
Route::post("login",[UserController::class,'index']);
Route::get("getuser",[UserController::class,'show']);
//Route::get("create",[UserController::class,'create']);
Route::post("adduser",[UserController::class,'store']);
//Route::get("edit/{id}",[UserController::class,'edit']);
Route::post("updateuser/{id}",[UserController::class,'update']);
Route::delete("deleteuser/{id}",[UserController::class,'destroy']);

Route::get("home",[LocationController::class,'index']);
//Route::get("create",[LocationController::class,'create']);
Route::post("add",[LocationController::class,'store']);
Route::get("edit/{id}",[LocationController::class,'edit']);
Route::post("update/{id}",[LocationController::class,'update']);
Route::post("delete/{id}",[LocationController::class,'destroy']);


//Route::apiResource("location", LocationController::class);
Route::get("rooms",[RoomController::class,'index']);
Route::get("rooms/{id}",[RoomController::class,'show']);
Route::get("roomlist/{id}",[RoomController::class,'roomList']);
//Route::post("addroom",[RoomController::class,'store']);
//Route::get("edit/{id}",[UserController::class,'edit']);
Route::post("updateroom/{id}",[RoomController::class,'update']);
Route::post("deleteroom/{id}",[RoomController::class,'destroy']);

Route::get("category",[CategoryController::class,'index']);
//Route::get("create",[CategoryController::class,'create']);
Route::post("addcategory",[CategoryController::class,'store']);
//Route::get("edit/{id}",[CategoryController::class,'edit']);
Route::post("updatecategory/{id}",[CategoryController::class,'update']);
Route::post("deletecategory/{id}",[CategoryController::class,'destroy']);

Route::post("activities",[ActivityController::class,'index']);
Route::get("activities/{id}",[ActivityController::class,'show']);
//Route::get("create",[ActivityController::class,'create']);
//Route::post("addactivity",[ActivityController::class,'store']);
//Route::get("edit/{id}",[ActivityController::class,'edit']);
//Route::post("updateroom/{id}",[ActivityController::class,'update']);
//Route::delete("deleteroom/{id}",[ActivityController::class,'destroy']);