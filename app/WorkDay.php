<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    //El modelo se crea utilizando el comando php artisan make:model WorkDay -m, el cual también crea las migraciones correspondientes
    //Los datos de la tabla se realizan en la migración correspondiente
    protected $fillable = [
        'day', 
        'active',
        'morningStart',
        'morningEnd',
        'afternoonStart',
        'afternoonEnd',
        'doctor_id'
    ];

    public function scopeDoctor($query){
        return $query->where('doctor_id', auth()->user()->id);
    }
}
