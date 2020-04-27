<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WorkDay;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function hours(Request $request){

        $rules = [
            'date' => 'required',
            'doctor_id' => 'required|exists:users,id' //que existe en la tabla users
        ];
        $this->validate($request, $rules); //validar no es absolutamente necesario debido a que vamos a ser nosotros quien consumamos esta información

        $dateCarbon = new Carbon($request->input('date'));
        $dayCarbon = $dateCarbon->dayOfWeek;
        $day = ($dayCarbon == 0 ? 6 : $dayCarbon-1);
        $doctorId = $request->input('doctor_id');

        $workDay = WorkDay::where('active', true)
                ->where('day', $day)
                ->where('doctor_id', $doctorId)->first([ //en vez de get se usa first porque solo necesitamos 1
                    'morningStart', 'morningEnd', 'afternoonStart', 'afternoonEnd'
                ]);

        if(!$workDay){
            return [];
        }

        $morningIntervals = [];
        $afternoonIntervals = [];

        if($workDay->morningStart != null && $workDay->morningEnd != null)
            $morningIntervals = $this->getIntervals($workDay->morningStart, $workDay->morningEnd);
        if($workDay->afternoonStart != null && $workDay->afternoonEnd != null)
            $afternoonIntervals = $this->getIntervals($workDay->afternoonStart, $workDay->afternoonEnd);

        $data = [];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;
        return $data;
    }

    private function getIntervals($start, $end){
        $startCarbon = new Carbon($start);
        $endCarbon = new Carbon($end);

        $intervals = [];
        while ($startCarbon < $endCarbon){
            $interval = [];
            $interval['start'] = $startCarbon->format('H:i');
            $startCarbon->addMinutes(30);
            $interval['end'] = $startCarbon->format('H:i');
            $intervals []= $interval;
        }
        return $intervals;
    }
}