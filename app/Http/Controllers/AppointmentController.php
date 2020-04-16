<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;

class AppointmentController extends Controller
{
    public function create(){
        $specialties = Specialty::all();
        return view('appointments.create', compact('specialties'));
    }
}
