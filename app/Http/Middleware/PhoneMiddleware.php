<?php

namespace App\Http\Middleware;

use Closure;

class PhoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->phone)
            return $next($request);

        $notification = "Es necesario asociar un número de teléfono para registrar turnos.";
        return redirect(route('profile.edit'))->with(compact('notification'));
    }
}
