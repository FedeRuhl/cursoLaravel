<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function show(){
        //return Auth::user();
        return Auth::guard('api')->user();
    }
}
