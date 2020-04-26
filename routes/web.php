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

Route::middleware(['auth', 'admin'])->namespace('Admin')->group(function(){ //en http, kernel establecemos a qué se refiere admin
    //El namespace es para que busque estos controladores dentro de la carpeta admin, en controllers
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
});

Route::middleware(['auth', 'doctor'])->namespace('Doctor')->group(function(){
    Route::get('/schedule', 'ScheduleController@edit')->name('schedule.edit');
    //en consola escribimos php artisan make:controller Doctor\ScheduleController
    Route::post('/schedule', 'ScheduleController@store')->name('schedule.store');
});

Route::middleware('auth')->group(function() { //no protejemos con un middleware especial para que un admin/medico puede reservar citas
    Route::get('/appointments/create', 'AppointmentController@create')->name('appointment.create');
    Route::post('/appointments', 'AppointmentController@store')->name('appointment.store');
    //para crear el modelo, la migracion de la db y además el controlador ejecutamos en consola: php artisan make:model Appointment -mc


    //JSON
    Route::get('/specialties/{specialty}/doctors', 'Api\SpecialtyController@doctors')->name('specialties.doctors'); //form registro
    //php artisan make:controller Api\SpecialtyController
    Route::get('/schedule/hours', 'Api\ScheduleController@hours')->name('schedule.hours'); //form registro
}); 






