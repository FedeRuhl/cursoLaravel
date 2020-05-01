<?php 
namespace App\Interfaces;
use Carbon\Carbon;

interface ScheduleServiceInterface{
    public function getAvailableIntervals($date, $doctorId);
    public function isAvailableInterval($scheduledDate, $doctorId, Carbon $time);
}

?>