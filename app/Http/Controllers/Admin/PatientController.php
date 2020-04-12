<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = User::patients()->paginate(10); //Para aclarar que se paginan los clientes, en el index debemos indicar sobre el final
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.create');
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

        User::create($request->only('name', 'email', 'dni', 'address', 'phone')
        + [ //concatenamos arreglos
            'role' => 'patient',
            'password' => bcrypt($request->input('password'))
        ]
        ); 
        $notification = "El paciente se ha registrado correctamente.";
        return redirect('/patients')->with(compact('notification'));
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
        $patient = User::patients()->findOrFail($id);
        return view('patients.edit', compact('patient'));
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

        $user = User::patients()->findOrFail($id);
        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
         if($password)
        $data['password'] = bcrypt($password);
        $user->fill($data); 
        $user->save();
        $notification = "El paciente se ha modificado correctamente.";
        return redirect('/patients')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = User::patients()->findOrFail($id);
        $deletedName = $patient->name;
        $patient->delete();
        $notification = "El paciente ".$deletedName." se ha eliminado correctamente.";
        return redirect('/patients')->with(compact('notification'));
    }
}
