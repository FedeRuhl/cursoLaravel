<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Appointment;
use App\User;
use DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function appointments(){
        $monthlyCounts = Appointment::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('COUNT(*) as count')
        )->groupBy('month')
        ->get()->toArray();

        $counts = array_fill(0, 12, 0); //index, cant, value

        foreach($monthlyCounts as $monthlyCount){
            $index = $monthlyCount['month']-1;
            $counts[$index] = $monthlyCount['count'];
        }

        return view('charts.appointments', compact('counts'));
    }

    public function doctors(){
        $now = Carbon::now();
        $end = $now->format('Y-m-d');
        $start = $now->subYear()->format('Y-m-d');

        return view('charts.doctors', compact('start', 'end'));
    }

    public function doctorJson(Request $request){
        $start = $request->input('start');
        $end = $request->input('end');

        $doctors = User::doctors() //en ocasiones que las peticiones son muy altas, tener todas estas clausulas dentro del select no es eficiente, y desde la misma db se debería crear views o tables adecuadas para obtener solamente con select
        ->select('id', 'name')
        ->withCount([
            'attendedAppointments' => function ($query) use ($start, $end){
                $query->whereBetween('scheduled_date', [$start, $end]);
            },
            'cancelledAppointments' => function ($query) use ($start, $end){
                $query->whereBetween('scheduled_date', [$start, $end]);
            }
            ])
        ->orderBy('attended_appointments_count', 'desc')
        ->orderBy('cancelled_appointments_count', 'desc')
        ->take(3)
        ->get();

        $data = [];
        $data['categories'] = $doctors->pluck('name'); //usamos pluck porque es una sola columna y para obtenerlo en formato arreglo
        
        $series = [];
        $seriesAttendedAppointments['name'] = 'Turnos atendidos';
        $seriesAttendedAppointments['data'] = $doctors->pluck('attended_appointments_count');
        $seriesCancelledAppintments['name'] = 'Turnos cancelados';
        $seriesCancelledAppintments['data'] = $doctors->pluck('cancelled_appointments_count');
        $series[] = $seriesAttendedAppointments;
        $series[] = $seriesCancelledAppintments;
        $data['series'] = $series;

        return $data; //objeto json {categories: [], series[]}
    }
}
