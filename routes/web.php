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
    return redirect('/login');
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

    // Charts
    Route::get('/charts/appointments/line', 'ChartController@appointments')->name('charts.appointments.line');
    Route::get('/charts/doctors/column', 'ChartController@doctors')->name('charts.doctors.column');
    Route::get('/charts/doctors/column/data', 'ChartController@doctorJson')->name('charts.doctors.column.data');
    //php artisan make:controller Admin\ChartController

    //FCM
    Route::post('/fcm/send', 'FirebaseController@sendAll');
});

Route::middleware(['auth', 'doctor'])->namespace('Doctor')->group(function(){
    Route::get('/schedule', 'ScheduleController@edit')->name('schedule.edit');
    Route::post('/schedule', 'ScheduleController@store')->name('schedule.store');
    //en consola escribimos php artisan make:controller Doctor\ScheduleController
});

Route::middleware('auth')->group(function() { //no protejemos con un middleware especial para que un admin/medico puede reservar citas

    Route::get('/profile', 'UserController@edit')->name('profile.edit');
    Route::post('/profile', 'UserController@update')->name('profile.update');

    Route::middleware('phone')->group(function() {
        Route::get('/appointments/create', 'AppointmentController@create')->name('appointment.create');
        Route::post('/appointments', 'AppointmentController@store')->name('appointment.store');
    });

    Route::get('/patient/appointments', 'AppointmentController@patientList')->name('appointment.patient');
    Route::get('/doctor/appointments', 'AppointmentController@doctorList')->name('appointment.doctor');
    Route::get('/admin/appointments', 'AppointmentController@adminList')->name('appointment.admin');

    Route::get('/appointments/{appointment}/cancel', 'AppointmentController@showCancelForm')->name('appointment.showCancelForm');
    Route::post('/appointments/{appointment}/cancel', 'AppointmentController@cancel')->name('appointment.cancel');
    Route::post('/appointments/{appointment}/confirm', 'AppointmentController@confirm')->name('appointment.confirm');
    
    //para crear el modelo, la migracion de la db y además el controlador ejecutamos en consola: php artisan make:model Appointment -mc
}); 






