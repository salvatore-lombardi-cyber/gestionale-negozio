<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Recupera la lingua dalla sessione, o usa quella predefinita
        $locale = Session::get('locale', config('app.locale'));
        
        // Verifica che la lingua sia supportata
        if (!in_array($locale, array_keys(config('app.available_locales')))) {
            $locale = config('app.locale');
        }
        
        // Imposta la lingua per l'applicazione
        App::setLocale($locale);
        
        return $next($request);
    }
}