<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit(){
        $user = auth()->user();
        return view('profile', compact('user')); //la variable está disponible directamente como $user
    }

    public function update(Request $request){
        //validate
        $user = auth()->user();
        $user->name = $request->input('name'); //$request->name
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        $notification = "Los datos han sido actualizados satisfactoriamente.";
        return back()->with(compact('notification')); //al ser redireccionado la variable está disponible como session('notification')
    }
}
