<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'description',
        'specialty_id',
        'doctor_id',
        'patient_id',
        'scheduled_date',
        'scheduled_time',
        'type'
    ];

    protected $hidden = [
        'specialty_id',
        'doctor_id',
        'scheduled_time'
    ];

    protected $appends = [
        'scheduled_time_24'
    ];

    //$appointment->specialty
    public function specialty(){
        //relacion n a 1
        return $this->belongsTo(Specialty::class);
        //laravel gracias a doctor_id identifica a los médicos
    }

    //$appointment->doctor
    public function doctor(){
        //relacion n a 1
        return $this->belongsTo(User::class);
        //laravel gracias a patient_id identifica a los pacientes
    }

    //$appointment->cancellation
    public function cancellation(){
        // has one 1 a 1/0 belongsTo
        return $this->hasOne(CancelledAppointment::class);
    }

    //accessor: campo calculado
    //$appointment->scheduled_time_24
    public function getScheduledTime24Attribute(){
        return (new Carbon($this->scheduled_time))->format('H:i');
    }

    public function patient(){
        return $this->belongsTo(User::class);
    }
}
