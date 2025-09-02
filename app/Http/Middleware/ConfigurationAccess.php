<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware per controllare l'accesso alle configurazioni di sistema
 * Implementa controlli di sicurezza OWASP per prevenire accessi non autorizzati
 */
class ConfigurationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica autenticazione
        if (!Auth::check()) {
            Log::warning('Tentativo di accesso non autenticato alle configurazioni', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Accesso non autorizzato. Effettua il login.');
        }

        $user = Auth::user();

        // Verifica ruolo amministratore o configuratore
        if (!$this->hasConfigurationAccess($user)) {
            Log::warning('Tentativo di accesso non autorizzato alle configurazioni', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);

            abort(403, 'Non hai i permessi necessari per accedere a questa sezione.');
        }

        // Log accesso autorizzato
        Log::info('Accesso autorizzato alle configurazioni', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);

        return $next($request);
    }

    /**
     * Verifica se l'utente ha accesso alle configurazioni
     */
    private function hasConfigurationAccess($user): bool
    {
        // Utilizza il sistema di ruoli implementato nel modello User
        if (method_exists($user, 'canConfigure')) {
            return $user->canConfigure();
        }
        
        // Fallback: controlla se esiste un campo role direttamente
        if (isset($user->role)) {
            return in_array($user->role, ['admin', 'configuratore', 'super_admin']);
        }

        // Fallback sicuro: nega l'accesso se non ci sono ruoli definiti
        Log::warning('Utente senza ruolo definito - accesso negato', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        return false;
    }
}