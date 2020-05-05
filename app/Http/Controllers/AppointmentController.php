<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;
use App\Interfaces\ScheduleServiceInterface;
use App\Appointment;
use Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function patientList(){
        $confirmedAppointments = Appointment::where('status', 'Confirmado')
            ->where('patient_id', auth()->id())
            ->paginate(10);
        $pendingAppointments = Appointment::where('status', 'Reservado')
            ->where('patient_id', auth()->id())
            ->paginate(10);
        $oldAppointments = Appointment::whereIn('status', ['Atendido', 'Cancelado'])
            ->where('patient_id', auth()->id())
            ->paginate(10);
        return view('appointments.patient', compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments'));
    }

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

    public function store(Request $request, ScheduleServiceInterface $scheduleService){

        $rules = [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'required|exists:users,id',
            'scheduled_time' => 'required',
            'scheduled_date' => 'required'
        ];

        $messages = [
            'scheduled_time.required' => 'Por favor, seleccione una hora válida para reservar su turno.',
            'scheduled_date.required' => 'Por favor, seleccione una fecha disponible.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages); //ya que con validate no es suficiente

        $validator->after(function ($validator) use ($request, $scheduleService) { //la funcion anonima no reconoce la variable si no se pone 'use'
            $date = $request->input('scheduled_date');
            $doctorId = $request->input('doctor_id');
            $scheduledTime = $request->input('scheduled_time');
            $start = new Carbon($scheduledTime);

            if($scheduleService->isAvailableInterval($date, $doctorId, $start) == false){
                $validator->errors()
                          ->add('available_time', 'La hora seleccionada se acaba de reservar por otro paciente.');
            }
        });

        if($validator->fails()){
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

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
