<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dni', 'address', 'phone', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot', 'email_verified_at', 'created_at', 'updated_at'
    ];

    public static $rules = [ //poniendo static no es necesario crear una instancia de user para usar las reglas
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed']
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function specialties(){
        return $this->belongsToMany(Specialty::class)->withTimestamps(); //esto significa que una usuario se asocia con multiples especialidades
        // para establecer la relacion entre ambos modelos, se creo php artisan make:migration create_specialty_user_table
    }

    public function scopePatients($query){
        return $query->where('role', 'patient');
    }

    public function scopeDoctors($query){
        return $query->where('role', 'doctor');
    }

    public function AsDoctorAppointments(){
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function attendedAppointments(){
        return $this->AsDoctorAppointments()->where('status', 'Atendido');
    }

    public function cancelledAppointments(){
        return $this->AsDoctorAppointments()->where('status', 'Cancelado');
    }

    public function AsPatientAppointments(){
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public static function createPatient(array $data){
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'patient'
        ]);
    }

    public function sendFCM($message){

        if (!$this->device_token)
            return;
            
        return fcm()
        ->to([
            $this->device_token
        ]) // $recipients must an array
        ->priority('high')
        ->timeToLive(0)
        ->notification([
            'title' => config('app.name'),
            'body' => $message
        ])
        ->send();
    }
}
