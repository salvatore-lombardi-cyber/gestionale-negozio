<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action = 'read'): Response
    {
        // Se l'utente non è autenticato, reindirizza al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Gli amministratori hanno sempre accesso completo
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Verifica se l'utente ha i permessi per questo modulo e azione
        $currentPermission = $user->activePermissions()->first();

        if (!$currentPermission) {
            // Nessun permesso configurato
            abort(403, 'Non hai permessi per accedere a questa sezione.');
        }

        $modules = $currentPermission->modules ?? [];
        
        // Verifica se ha il permesso per il modulo richiesto
        if (!isset($modules[$module]) || !isset($modules[$module][$action]) || !$modules[$module][$action]) {
            abort(403, "Non hai i permessi per {$this->getActionDescription($action)} nella sezione {$this->getModuleDescription($module)}.");
        }

        return $next($request);
    }

    /**
     * Ottieni descrizione dell'azione
     */
    private function getActionDescription(string $action): string
    {
        return match($action) {
            'read' => 'visualizzare',
            'write' => 'modificare',
            'delete' => 'eliminare',
            default => $action
        };
    }

    /**
     * Ottieni descrizione del modulo
     */
    private function getModuleDescription(string $module): string
    {
        return match($module) {
            'magazzino' => 'Magazzino',
            'vendite' => 'Vendite',
            'fatturazione' => 'Fatturazione',
            'anagrafiche' => 'Anagrafiche',
            'configurazioni' => 'Configurazioni',
            default => ucfirst($module)
        };
    }
}