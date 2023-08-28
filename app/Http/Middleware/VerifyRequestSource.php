<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRequestSource
{
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->headers->get('referer');
 
        if ($referer && strpos($referer, config('app.url'))  !== false) {
            return $next($request);
        }

        return response()->view('errors.403', ['title' => 'Acceso no autorizado'], 403);
    }
}
