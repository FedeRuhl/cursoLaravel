<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Appointment;
use DB;
use Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $minutes = $this->daysToMinutes(7);

        $appointmentsByDay = Cache::remember('appointments_by_day', $minutes, function () {
            $results = Appointment::select([
                DB::raw('DAYOFWEEK(scheduled_date) as day'),
                DB::raw('COUNT(*) as count')
                ])
                ->groupBy('day')
                ->where('status', 'Confirmado')
                ->get(['day', 'count'])
                ->mapWithKeys(function ($item){ //lo mapeamos para generar consistencia, incluya los dias tengan o no turnos registrados
                    return [$item['day'] => $item['count']];
                })->toArray();

            $count = [];
            for ($i=1; $i<=7; $i++){
                if (array_key_exists($i, $results))
                    $count[] = $results[$i];
                else
                    $count[] = 0;
            }

            return $count;
        });

        return view('home', compact('appointmentsByDay'));
    }

    private function daysToMinutes($days){
        return $days * 24 * 60;
    }
}
