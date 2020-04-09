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
Route::get('/specialties/{specialty}/edit', 'SpecialtyController@edit')->name('specialties.edit');

Route::post('/specialties', 'SpecialtyController@store')->name('specialties.store'); //envío del form a la db
Route::put('/specialties/{specialty}', 'SpecialtyController@update')->name('specialties.update'); //edicion de especialidad
Route::delete('/specialties/{specialty}', 'SpecialtyController@destroy')->name('specialties.destroy');

// Doctors
//hace las rutas de arriba automáticamente
Route::resource('doctors', 'DoctorController')->names([
    'index' => 'doctors',
    'create' => 'doctors.create',
    'store' => 'doctors.store',
    'edit' => 'doctors.edit',
    'update' => 'doctors.update',
    'destroy' => 'doctors.destroy'

]);
//en consola escribimos php artisan make:controller NameController --resource

// Patients
Route::resource('patients', 'PatientController')->names([
    'index' => 'patients',
    'create' => 'patients.create',
    'store' => 'patients.store',
    'edit' => 'patients.edit',
    'update' => 'patients.update',
    'destroy' => 'patients.destroy'

]);