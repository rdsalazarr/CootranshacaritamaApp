<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth, DB;

class PreloadSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {        
        $ruta     = $request->path(); 
        $consulta = DB::table('funcionalidad')
                        ->select('funcid')
                        ->join('rolfuncionalidad','rolfunfuncid','=','funcid')
                        ->join('usuariorol','usurolrolid','=','rolfunrolid')
                        ->where('usurolusuaid', Auth::id());

            if(Auth::id() != 2)
                $consulta = $consulta->where('funcactiva', 1);

            $funcionalidad =  $consulta->where('funcruta', $ruta)->get();

        if (count($funcionalidad) < 1) {
            return response()->view('errors.401', ['title' => 'Acceso no autorizado'], 401);
        }

        return $next($request);
    }
}
