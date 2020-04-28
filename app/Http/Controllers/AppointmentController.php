<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;

use App\Appointment;

class AppointmentController extends Controller
{
    public function create(){
        $specialties = Specialty::all();
        return view('appointments.create', compact('specialties'));
    }

    public function store(Request $request){
        $data = $request->only([
            'description',
            'specialty_id',
            'doctor_id',
            'patient_id',
            'scheduled_date',
            'scheduled_time',
            'type'
        ]);
        Appointment::create($data);
        
        $notification = "El turno se ha reservado correctamente.";
        return back()->with(compact('notification'));
    }
}
