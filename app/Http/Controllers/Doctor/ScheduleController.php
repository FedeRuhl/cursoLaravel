<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WorkDay;
class ScheduleController extends Controller
{
    public function edit(){
        $days = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ];
        return view('schedule', compact('days'));
    }

    public function store(Request $request){
         //dd($request->all());// imprimir todos los datos que vengan del formulario
        $active = $request->input('active') ?: []; //Si no existe lo reemplazamos con un arreglo vacío
        $morningStart = $request->input('morningStart');
        $morningEnd = $request->input('morningEnd');
        $afternoonStart = $request->input('afternoonStart');
        $afternoonEnd = $request->input('afternoonEnd');

        for($i=0; $i<sizeof($active); $i++){
            $dia = $active[$i];
            echo "Dia: ".$dia;

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($morningStart[$dia])))
                $morningStart[$dia] = null;
                
            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($morningEnd[$dia])))
                $morningEnd[$dia] = null;

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($afternoonStart[$dia])))
                $afternoonStart[$dia] = null;

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($afternoonEnd[$dia])))
                $afternoonEnd[$dia] = null;

            WorkDay::updateOrCreate(
                [
                    'day' => $dia,
                    'doctorId' => auth()->user()->id
                ],
                [
                    'active' => true,
                    'morningStart' => $morningStart[$dia],
                    'morningEnd' => $morningEnd[$dia],
                    'afternoonStart' => $afternoonStart[$dia],
                    'afternoonEnd' => $afternoonEnd[$dia]
                ]);
        }
        return back();
    }
}
