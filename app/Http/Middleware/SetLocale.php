<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada session bernama 'locale'
        if (session()->has('locale')) {
            // Jika ada, paksa Laravel menggunakan bahasa tersebut
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
}
