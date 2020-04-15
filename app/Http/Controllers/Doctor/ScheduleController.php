<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\WorkDay;
class ScheduleController extends Controller
{
    public function edit(){
        $days = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ];
        $workDays = WorkDay::doctor()->get();
        //dd($workDays->toArray()); lo muestra en forma de arreglo

        $workDays->map(function ($workDay){
            $workDay->morningStart = (new Carbon($workDay->morningStart))->format('G:i');
            $workDay->morningEnd = (new Carbon($workDay->morningEnd))->format('G:i');
            $workDay->afternoonStart = (new Carbon($workDay->afternoonStart))->format('G:i');
            $workDay->afternoonEnd = (new Carbon($workDay->afternoonEnd))->format('G:i');
            return $workDay;
        });
        return view('schedule', compact('workDays', 'days'));
    }

    public function store(Request $request){
        //dd($request->all());// imprimir todos los datos que vengan del formulario
        $active = $request->input('active') ?: []; //Si no existe lo reemplazamos con un arreglo vacío
        $morningStart = $request->input('morningStart');
        $morningEnd = $request->input('morningEnd');
        $afternoonStart = $request->input('afternoonStart');
        $afternoonEnd = $request->input('afternoonEnd');

        for($i=0; $i<sizeof($active); $i++){
        workDay::doctor()->where('day', '!=', $active[$i])->delete(); //eliminar dias que el medico ya no usa
        }

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
