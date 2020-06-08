<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'AuthController@login');

//JSON... public resources
//estas 3 rutas no pertenecen ni a las rutas web ni a las rutas api, son públicas y pueden ser accedidas por cualquiera
Route::get('/specialties', 'SpecialtyController@index')->name('specialties.index'); 
Route::get('/specialties/{specialty}/doctors', 'SpecialtyController@doctors')->name('specialties.doctors'); //form registro
Route::get('/schedule/hours', 'ScheduleController@hours')->name('schedule.hours'); //form registro

Route::middleware('auth:api')->group(function (){
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');
});

