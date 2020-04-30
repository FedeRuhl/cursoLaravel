<?php namespace App\Services;

use App\Interfaces\ScheduleServiceInterface;
use Carbon\Carbon;
use App\WorkDay;
use App\Appointment;

class ScheduleService implements ScheduleServiceInterface{

    private function getDayFromDate($date){
        $dateCarbon = new Carbon($date);
        $dayCarbon = $dateCarbon->dayOfWeek;
        $day = ($dayCarbon == 0 ? 6 : $dayCarbon-1);
        return $day;
    }

    public function getAvailableIntervals($date, $doctorId){
        $workDay = WorkDay::where('active', true)
                ->where('day', $this->getDayFromDate($date))
                ->where('doctor_id', $doctorId)->first([ //en vez de get se usa first porque solo necesitamos 1
                    'morningStart', 'morningEnd', 'afternoonStart', 'afternoonEnd'
                ]);

        if(!$workDay){
            return [];
        }

        $morningIntervals = [];
        $afternoonIntervals = [];

        if($workDay->morningStart != null && $workDay->morningEnd != null)
            $morningIntervals = $this->getIntervals($workDay->morningStart, $workDay->morningEnd, $doctorId, $date);
        if($workDay->afternoonStart != null && $workDay->afternoonEnd != null)
            $afternoonIntervals = $this->getIntervals($workDay->afternoonStart, $workDay->afternoonEnd, $doctorId, $date);

        $data = [];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;

        return $data;
    }

    private function getIntervals($start, $end, $doctorId, $scheduledDate){
        $startCarbon = new Carbon($start);
        $endCarbon = new Carbon($end);

        $intervals = [];
        while ($startCarbon < $endCarbon){
            $interval = [];
            $interval['start'] = $startCarbon->format('H:i');

            $exists = Appointment::where('doctor_id', $doctorId)
                    ->where('scheduled_date', $scheduledDate)
                    ->where('scheduled_time', $startCarbon->format('H:i:s'))->exists();

            $startCarbon->addMinutes(30);
            $interval['end'] = $startCarbon->format('H:i');
            
            if(!$exists) //dias y horarios ya reservados ocupados
            $intervals []= $interval;
        }
        return $intervals;
    }
}