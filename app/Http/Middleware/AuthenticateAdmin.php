<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class AuthenticateAdmin
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
        if (! Auth::guard('admin')->check()) {
           return redirect(env('admin').'/login')->with('error','Porfavor Inicia sesion para continuar.');
       }
		
		return $next($request);
    }
}
