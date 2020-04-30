<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;
use App\Interfaces\ScheduleServiceInterface;
use App\Appointment;

class AppointmentController extends Controller
{
    public function create(ScheduleServiceInterface $scheduleService){
        $specialties = Specialty::all();

        $specialtyId = old('specialty_id');
        if ($specialtyId){
            $specialty = Specialty::find($specialtyId);
            $doctors = $specialty->users;
        }
        else $doctors = collect(); //coleccion vacia

        
        $date = old('scheduled_date');
        $doctorId = old('doctor_id');
        if ($date && $doctorId){
            $intervals = $scheduleService->getAvailableIntervals($date, $doctorId);
        }
        else $intervals = null;

        return view('appointments.create', compact('specialties', 'doctors', 'intervals'));
    }

    public function store(Request $request){

        $rules = [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'exists:users,id',
            'scheduled_time' => 'required',
            'scheduled_date' => 'required'
        ];

        $messages = [
            'scheduled_time.required' => 'Por favor, seleccione una hora válida para reservar su turno.',
            'scheduled_date.required' => 'Por favor, seleccione una fecha disponible.'
        ];

        $this->validate($request, $rules, $messages);

        $data = $request->only([
            'description',
            'specialty_id',
            'doctor_id',
            'patient_id',
            'scheduled_date',
            'scheduled_time',
            'type'
        ]);
        $data['patient_id'] = auth()->id();
        Appointment::create($data);
        
        $notification = "El turno se ha reservado correctamente.";
        return back()->with(compact('notification'));
    }
}
