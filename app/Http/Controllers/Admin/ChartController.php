<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Appointment;
use App\User;
use DB;

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
        return view('charts.doctors');
    }

    public function doctorJson(){
        $doctors = User::doctors()
        ->select('id', 'name')
        ->withCount('asDoctorAppointments')
        ->orderBy('as_doctor_appointments_count', 'desc')
        ->take(3)
        ->get()->toArray();
        dd($doctors);


        $data = [];
        $data['categories'] = User::doctors()->pluck('name'); //usamos pluck porque es una sola columna y para obtenerlo en formato arreglo
        
        $series = [];
        $seriesCitasAtendidas = 1;
        $seriesCitasCanceladas = 2;
        $series[] = $seriesCitasAtendidas;
        $series[] = $seriesCitasCanceladas;
        $data['series'] = $series;

        return $data; //objeto json {categories: [], series[]}
    }
}
