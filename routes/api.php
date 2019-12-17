<?php

use Illuminate\Http\Request;

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

Route::get("appointments", "AppointmentApiController@index");
Route::post("appointments", "AppointmentApiController@store");
Route::patch("appointments/{id}", "AppointmentApiController@update");
Route::delete("appointments/{id}", "AppointmentApiController@destroy");