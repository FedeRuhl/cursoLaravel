<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests\StoreAppointment;
use App\Appointment;

class AppointmentController extends Controller
{
    public function index(){
        $user = Auth::guard('api')->user();
        $appointments =  $user->asPatientAppointments()
        ->with([
            'specialty' => function($query){
                $query->select('id', 'name');
            },
             'doctor' => function($query){
                $query->select('id', 'name');
             }
            ])
        ->get([
            'id',
            'description',
            'specialty_id',
            'doctor_id',
            'scheduled_date',
            'scheduled_time',
            'type',
            'created_at',
            'status'
        ]);

        return $appointments;
    }

    public function store(StoreAppointment $request){
        $patientId = Auth::guard('api')->id();
        $appointment = Appointment::createForPatient($request, $patientId);
        $appointment ? $success = true : $success = false;
        return compact('success');
    }
}
