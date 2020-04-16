<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\WorkDay;
class ScheduleController extends Controller
{
    private $days = [
        'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
    ];

    public function edit(){
        $workDays = WorkDay::doctor()->get();
        //dd($workDays->toArray()); lo muestra en forma de arreglo
        $workDays->map(function ($workDay){
            if ($workDay->morningStart != null) $workDay->morningStart = (new Carbon($workDay->morningStart))->format('H:i');
            if ($workDay->morningEnd != null)$workDay->morningEnd = (new Carbon($workDay->morningEnd))->format('H:i');
            if ($workDay->afternoonStart != null)$workDay->afternoonStart = (new Carbon($workDay->afternoonStart))->format('H:i');
            if ($workDay->afternoonEnd != null)$workDay->afternoonEnd = (new Carbon($workDay->afternoonEnd))->format('H:i');
            return $workDay;
        });
        $days = $this->days;

        $morningHours = [];
        for($i=5; $i<=12; $i++){
            $morningHours[] = ($i<10 ? '0' : '') . $i . ':00';
            $morningHours[] = ($i<10 ? '0' : '') . $i . ':30';
        }

        $afternoonHours = [];
        for($i=13; $i<=23; $i++){
            $afternoonHours[] = $i . ':00';
            $afternoonHours[] = $i . ':30';
        }
        return view('schedule', compact('workDays', 'days', 'morningHours', 'afternoonHours'));
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

        $errors = [];
        for($i=0; $i<sizeof($active); $i++){
            $dia = $active[$i];

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($morningStart[$dia])))
                $morningStart[$dia] = null;
                
            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($morningEnd[$dia])))
                $morningEnd[$dia] = null;

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($afternoonStart[$dia])))
                $afternoonStart[$dia] = null;

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", strval($afternoonEnd[$dia])))
                $afternoonEnd[$dia] = null;

            if ($morningStart[$dia] != null && $morningEnd[$dia] != null
                && $morningStart[$dia]>$morningEnd[$dia])
                $errors[] = "Las horas del turno mañana son inconsistenes para el día " . $this->days[$dia];

            if ($afternoonStart[$dia] != null && $afternoonEnd[$dia] != null
                && $afternoonStart[$dia]>$afternoonEnd[$dia])
                $errors[] = "Las horas del turno tarde son inconsistenes para el día " . $this->days[$dia];

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
        if (count($errors) > 0)
            return back()->with(compact('errors'));
        else
            $notification = 'Los cambios se han guardado correctamente.';
            return back()->with(compact('notification'));
    }
}
