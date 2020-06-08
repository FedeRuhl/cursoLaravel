<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Specialty;

class SpecialtyController extends Controller
{

    public function index(){
        return Specialty::all(['id', 'name']);        
    }

    public function doctors(Specialty $specialty){
        return $specialty->users()->get([ //cuando laravel detecta que se retorna una coleccion, en este caso de usuarios, la devuelve en formato json
            'users.id', 'users.name'
            //esto además lleva el pivot que indica la relación entre ambos, para ocultarlo, en el modelo user en hidden agregamos pivot
            ]); 
    }
}
