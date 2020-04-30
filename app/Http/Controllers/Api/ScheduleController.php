<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WorkDay;
use Carbon\Carbon;
use App\Interfaces\ScheduleServiceInterface;

class ScheduleController extends Controller
{
    public function hours(Request $request, ScheduleServiceInterface $scheduleService){ //inyeccion de dependencias

        $rules = [
            'date' => 'required',
            'doctor_id' => 'required|exists:users,id' //que existe en la tabla users
        ];
        $this->validate($request, $rules); //validar no es absolutamente necesario debido a que vamos a ser nosotros quien consumamos esta información

        $date = $request->input('date');
        $doctorId = $request->input('doctor_id');

        return $scheduleService->getAvailableIntervals($date, $doctorId);
    }

    
}