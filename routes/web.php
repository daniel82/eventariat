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
  return redirect( "/dienstplan" );

});


Route::middleware([ "auth", "locale"])->group(function ()
{
  Route::get('/appointments', "AppointmentController@index" );
  Route::get('/calendar', "AppointmentController@index" );
  Route::get('/kalender', "AppointmentController@index" );
  Route::get('/dienstplan', "AppointmentController@index" );
  Route::get('/account', "UserFrontendController@edit");
  Route::post('/account', "UserFrontendController@update");
  Route::patch('/account', "UserFrontendController@update");

  Route::get('/shift-requests/create', "ShiftRequestFrontendController@create" );
  Route::post('/shift-requests', "ShiftRequestFrontendController@store" );
  Route::patch('/shift-requests/{id}', "ShiftRequestFrontendController@update" );
  Route::get('/shift-requests/{id}', "ShiftRequestFrontendController@edit" );
  Route::delete('/shift-requests/{id}', "ShiftRequestFrontendController@destroy" );
});



Route::middleware([ "admin"])->group(function ()
{
  Route::prefix('admin')->group(function ()
  {
    Route::resource('users', "UserController");
    Route::resource('locations', "LocationController");
    Route::resource('shift-requests', "ShiftRequestController");
    Route::get('login-as/', "UserController@loginAs");
  });
});



Auth::routes(["register"=>false]);

Route::get('/home', 'HomeController@index')->name('home');



Route::middleware(["auth", "locale"])->group(function ()
{
  Route::prefix('api')->group(function ()
  {
    Route::get("appointments", "AppointmentApiController@index");
    Route::post("appointments", "AppointmentApiController@store");
    Route::patch("appointments/{id}", "AppointmentApiController@update");
    Route::delete("appointments/{id}", "AppointmentApiController@destroy");

    Route::get("users/{id}", "UserApiController@show");
  });
});