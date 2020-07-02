<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;
use App\Interfaces\ScheduleServiceInterface;
use App\Appointment;
use App\CancelledAppointment;
use App\Http\Requests\StoreAppointment;
use Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function patientList(){
        $role = auth()->user()->role;
        $confirmedAppointments = Appointment::where('status', 'Confirmado')
            ->where('patient_id', auth()->id())
            ->paginate(10);
        $pendingAppointments = Appointment::where('status', 'Reservado')
            ->where('patient_id', auth()->id())
            ->paginate(10);
        $oldAppointments = Appointment::whereIn('status', ['Atendido', 'Cancelado'])
            ->where('patient_id', auth()->id())
            ->paginate(10);
        return view('appointments.patient', compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments', 'role'));
    }

    public function doctorList(){
        $role = auth()->user()->role;
        $confirmedAppointments = Appointment::where('status', 'Confirmado')
            ->where('doctor_id', auth()->id())
            ->paginate(10);
        $pendingAppointments = Appointment::where('status', 'Reservado')
            ->where('doctor_id', auth()->id())
            ->paginate(10);
        $oldAppointments = Appointment::whereIn('status', ['Atendido', 'Cancelado'])
            ->where('doctor_id', auth()->id())
            ->paginate(10);
        return view('appointments.doctor', compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments', 'role'));
    }

    public function adminList(){
        $role = auth()->user()->role;
        $confirmedAppointments = Appointment::where('status', 'Confirmado')
            ->paginate(10);
        $pendingAppointments = Appointment::where('status', 'Reservado')
            ->paginate(10);
        $oldAppointments = Appointment::whereIn('status', ['Atendido', 'Cancelado'])
            ->paginate(10);
        return view('appointments.admin', compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments', 'role'));
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

    public function store(StoreAppointment $request){ //storeAppointment lo creamos nosotros como form request
        $created = Appointment::createForPatient($request, auth()->id());
        if ($created)
            $notification = "El turno se ha reservado correctamente.";
        else
            $notification = "Ocurrió un problema al registrar el turno.";
    
        return back()->with(compact('notification'));
    }

    public function showCancelForm(Appointment $appointment){
        $role = auth()->user()->role;

        if($appointment->status == 'Confirmado' or $appointment->status == 'Reservado')
            return view('appointments.cancel', compact('appointment', 'role'));
        else return redirect(route('appointment.'.$role));
    }

    public function cancel(Appointment $appointment, Request $request){
        if ($request->has('justification')){
            $rules = [
                'justification' => ['required', 'min:10', 'max:255', 'alpha_num'],
            ];
    
            $messages = [
                'justification.required' => 'La justificación es un campo obligatorio.',
                'justification.min' => 'La justificación debe contener como mínimo 10 caracteres.',
                'justification.max' => 'La justificación no debe exceder los 255 caracteres.',
                'justification.alpha_num' => 'Ha ingresado caracteres no permitidos.'
            ];
    
            $this->validate($request, $rules, $messages);
            $cancellation = new CancelledAppointment();
            $cancellation->justification = $request->input('justification');
            $cancellation->cancelled_by_id = auth()->id();
            $appointment->cancellation()->save($cancellation);
        }
        $appointment->status = 'Cancelado';
        $saved = $appointment->save(); //update

        if ($saved)
            $appointment->patient->sendFCM('Su turno se ha cancelado.');

        $notification = "La cita se ha cancelado correctamente";
        
        if (auth()->user()->role == 'patient'){
            return redirect(route('appointment.patient'))->with(compact('notification'));
        }
        else if (auth()->user()->role == 'doctor'){
            return redirect(route('appointment.doctor'))->with(compact('notification'));
        }
        else{
            return redirect(route('appointment.admin'))->with(compact('notification'));
        }
    }

    public function confirm(Appointment $appointment){
        $appointment->status = 'Confirmado';
        $saved = $appointment->save(); //update

        if ($saved)
            $appointment->patient->sendFCM('Su turno se ha confimado!');

        $notification = "La cita se ha cancelado correctamente";
        if (auth()->user()->role == 'patient'){
            return redirect(route('appointment.patient'))->with(compact('notification'));
        }
        else if (auth()->user()->role == 'doctor'){
            return redirect(route('appointment.doctor'))->with(compact('notification'));
        }
        else{
            return redirect(route('appointment.admin'))->with(compact('notification'));
        }
    }
}