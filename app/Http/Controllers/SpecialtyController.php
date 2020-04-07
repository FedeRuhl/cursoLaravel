<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;

class SpecialtyController extends Controller
{
    public function __construct(){
        $this->middleware('auth'); //verificar que el usuario ha iniciado sesión
    }

    public function index(){
        $specialties = Specialty::all(); // obtenemos todas las especialidades usando el modelo Specialty y el método all
        return view('specialties.index', compact('specialties')); //para pasarle esta informacion a la vista, usamos el parametro specialties
    }

    public function create(){
        return view('specialties.create');
    }

    public function store(Request $request){
        //dd($request->all()); esto muestra todo lo que acabamos de recibir del form
        $rules = [
            'name' => 'required|min:4|regex:/^[\pL\s\-]+$/u'
        ];
        $messages= [
            'name.required' => 'Es necesario ingresar un nombre.',
            'name.min' => 'Debe ingresar como mínimo 4 caracteres.',
            'name.regex' => 'Solo está permitido ingresar letras y espacios.'
        ];
        $this->validate($request, $rules, $messages);
        $specialty = new Specialty();
        $specialty->name = $request->input('name');
        $specialty->description = $request->input('description');
        $specialty->save(); //insert

        return redirect('/specialties');
    }
}
