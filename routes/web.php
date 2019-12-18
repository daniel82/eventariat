<?php

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



Route::middleware(["locale"])->group(function ()
{
  Route::get('/appointments', "AppointmentController@index" );
});


Route::prefix('admin')->group(function () {
  Route::resource('users', "UserController");
  Route::resource('locations', "LocationController");
});


