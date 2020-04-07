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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Specialty
Route::get('/specialties', 'SpecialtyController@index')->name('specialties');
Route::get('/specialties/create', 'SpecialtyController@create')->name('specialties.create'); //form registro
Route::get('/specialties/{specialty?}/edit', 'SpecialtyController@edit')->name('specialties.edit');
Route::post('/specialties', 'SpecialtyController@store'); //envío del form a la db