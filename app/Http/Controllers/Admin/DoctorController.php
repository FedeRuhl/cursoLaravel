<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use App\Specialty;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::doctors()->get(); //scope  generado en el modelo user
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialties = Specialty::all();
        return view('doctors.create', compact('specialties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:4',
            'email' => 'required|unique:users|email',
            'dni' => 'nullable|digits:8',
            'address' => 'nullable|min:5',
            'phone' => 'nullable|min:6'
        ];
        $messages= [
            'name.required' => 'Es necesario ingresar un nombre.',
            'name.min' => 'El nombre debe contener como mínimo 4 caracteres.',
            'email.required' => 'Es necesario ingresar un email.',
            'email.email' => 'Debe usar un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya ha sido utilizado',
            'dni.digits' => 'El D.N.I. debe estar compuesto por 8 caracteres numéricos.',
            'address.min' => 'La dirección debe contener como mínimo 5 caracteres',
            'phone.min' => 'El teléfono debe contener como mínimo 6 caracteres' 
        ];
        $this->validate($request, $rules, $messages);

        #mass assigment, en el modelo user se define los campos fillables
        $user = User::create($request->only('name', 'email', 'dni', 'address', 'phone') //es lo mismo que crear un objeto y llamar al método save
        + [ //concatenamos arreglos
            'role' => 'doctor',
            'password' => bcrypt($request->input('password'))
        ]
        );
        //se usa only en vez de all porque de esta forma evitamos que desde el navegador creen un usuario admin

        $user->specialties()->attach($request->input('specialties'));//este metodo crea relaciones n a n

        $notification = "El médico se ha registrado correctamente.";
        return redirect('/doctors')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $specialties = Specialty::all();
        $doctor = User::doctors()->findOrFail($id);
        $specialty_ids = $doctor->specialties()->pluck('specialties.id'); //pluck obtiene de un arreglo lo que necesitemos, en este caso de las especialidades solo necesitamos los ID
        return view('doctors.edit', compact('doctor', 'specialties', 'specialty_ids'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'dni' => 'nullable|digits:8',
            'address' => 'nullable|min:5',
            'phone' => 'nullable|min:6'
        ];
        $messages= [
            'name.required' => 'Es necesario ingresar un nombre.',
            'name.min' => 'El nombre debe contener como mínimo 4 caracteres.',
            'email.required' => 'Es necesario ingresar un email.',
            'email.email' => 'Debe usar un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya ha sido utilizado',
            'dni.digits' => 'El D.N.I. debe estar compuesto por 8 caracteres numéricos.',
            'address.min' => 'La dirección debe contener como mínimo 5 caracteres',
            'phone.min' => 'El teléfono debe contener como mínimo 6 caracteres' 
        ];
        $this->validate($request, $rules, $messages);

        $user = User::doctors()->findOrFail($id);
        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
         if($password)
        $data['password'] = bcrypt($password);
        $user->fill($data); 
        $user->save();
        $user->specialties()->sync($request->input('specialties')); //laravel se encarga de sincronizar las especialidades que vienen del form, hayan estado o no seleccionadas
        $notification = "El médico se ha modificado correctamente.";
        return redirect('/doctors')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = User::doctors()->findOrFail($id);
        $deletedName = $doctor->name;
        $doctor->delete();
        $notification = "El médico ".$deletedName." se ha eliminado correctamente.";
        return redirect('/doctors')->with(compact('notification'));
    }
}
