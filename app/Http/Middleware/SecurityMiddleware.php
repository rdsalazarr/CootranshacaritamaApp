<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth, DB;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
    public function handle($request, Closure $next, $role): Response 
    { 
        $consulta = DB::table('funcionalidad')
                        ->select('funcid')
                        ->join('rolfuncionalidad','rolfunfuncid','=','funcid')
                        ->join('usuariorol','usurolrolid','=','rolfunrolid')
                        ->where('usurolusuaid', Auth::id());

            if(Auth::id() != 1)
                $consulta = $consulta->where('funcactiva', 1);

            $funcionalidad =  $consulta->where('funcruta', $role)->get();

        if (count($funcionalidad) < 1) {
            return response()->view('errors.401', ['title' => 'Acceso no autorizado'], 401);
        }

        return $next($request);
    }
}