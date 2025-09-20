<?php

namespace App\Services\GestioneTabelle\Sicurezza;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Service enterprise per gestione sicurezza tabelle di configurazione
 * Implementa controlli OWASP 2025 e security best practices
 */
class SicurezzaTabelle
{
    private const MAX_TENTATIVI_OPERAZIONE = 60; // per ora
    private const MAX_TENTATIVI_UTENTE = 1000; // per ora
    private const TIMEOUT_BLOCCO = 3600; // 1 ora in secondi

    private array $permessiCache = [];
    private array $configurazioneTabellePermessi = [];

    public function __construct()
    {
        $this->inizializzaConfigurazionePermessi();
    }

    /**
     * Verifica permessi per operazione su tabella
     */
    public function verificaPermessi(string $nomeTabella, string $operazione): bool
    {
        try {
            $utente = Auth::user();
            
            if (!$utente) {
                $this->logTentativoAccesso($nomeTabella, $operazione, 'non_autenticato');
                throw new \Illuminate\Auth\AuthenticationException('Utente non autenticato');
            }

            // Rate limiting per utente
            $this->applicaRateLimiting($utente->id, $nomeTabella, $operazione);

            // Verifica permesso specifico
            $permesso = $this->ottieniPermessoRichiesto($nomeTabella, $operazione);
            
            if (!$this->haPermesso($utente, $permesso)) {
                $this->logTentativoAccesso($nomeTabella, $operazione, 'permesso_negato', $utente->id);
                throw new \Illuminate\Auth\Access\AuthorizationException("Permesso negato per {$operazione} su {$nomeTabella}");
            }

            // Verifica restrizioni temporali
            $this->verificaRestrizioniTemporali($utente, $nomeTabella, $operazione);

            // Verifica IP whitelist se configurata
            $this->verificaRestrizioniIP($utente, request()->ip());

            $this->logOperazioneAutorizzata($nomeTabella, $operazione, $utente->id);
            
            return true;

        } catch (\Exception $e) {
            Log::error("Errore verifica permessi {$nomeTabella}.{$operazione}", [
                'utente_id' => Auth::id(),
                'errore' => $e->getMessage(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            throw $e;
        }
    }

    /**
     * Verifica se utente ha permesso specifico
     */
    public function haPermesso(?\Illuminate\Contracts\Auth\Authenticatable $utente, string $permesso): bool
    {
        if (!$utente) {
            return false;
        }

        // Cache permessi per performance
        $cacheKey = "permessi_{$utente->id}_{$permesso}";
        
        if (isset($this->permessiCache[$cacheKey])) {
            return $this->permessiCache[$cacheKey];
        }

        // Verifica tramite Gate o ruoli
        $haPermesso = Gate::forUser($utente)->allows($permesso) || 
                     (method_exists($utente, 'hasPermissionTo') && $utente->hasPermissionTo($permesso)) ||
                     (method_exists($utente, 'can') && $utente->can($permesso));

        $this->permessiCache[$cacheKey] = $haPermesso;
        
        return $haPermesso;
    }

    /**
     * Applica rate limiting per prevenire abuse
     */
    public function applicaRateLimiting(int $utenteId, string $nomeTabella, string $operazione): void
    {
        $chiaveOperazione = "gestione_tabelle:{$utenteId}:{$nomeTabella}:{$operazione}";
        $chiaveUtente = "gestione_tabelle_utente:{$utenteId}";

        // Rate limiting per operazione specifica
        if (RateLimiter::tooManyAttempts($chiaveOperazione, self::MAX_TENTATIVI_OPERAZIONE)) {
            $this->logRateLimitExceeded($utenteId, $nomeTabella, $operazione);
            throw new \Illuminate\Http\Exceptions\ThrottleRequestsException(
                'Troppi tentativi per questa operazione. Riprova più tardi.'
            );
        }

        // Rate limiting globale per utente
        if (RateLimiter::tooManyAttempts($chiaveUtente, self::MAX_TENTATIVI_UTENTE)) {
            $this->logRateLimitExceeded($utenteId, 'globale', 'tutte');
            throw new \Illuminate\Http\Exceptions\ThrottleRequestsException(
                'Limite operazioni raggiunto. Account temporaneamente sospeso.'
            );
        }

        // Incrementa contatori
        RateLimiter::hit($chiaveOperazione, self::TIMEOUT_BLOCCO);
        RateLimiter::hit($chiaveUtente, self::TIMEOUT_BLOCCO);
    }

    /**
     * Log operazione autorizzata per audit
     */
    public function logOperazione(string $operazione, string $nomeTabella, ?int $elementoId = null): void
    {
        $utente = Auth::user();
        
        $datiLog = [
            'utente_id' => $utente?->id,
            'utente_email' => $utente?->email,
            'operazione' => $operazione,
            'tabella' => $nomeTabella,
            'elemento_id' => $elementoId,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
            'session_id' => session()->getId()
        ];

        // Log principale
        Log::info("Operazione gestione tabelle: {$operazione}", $datiLog);

        // Log audit separato per compliance
        $this->scriviLogAudit($datiLog);

        // Notifica operazioni critiche
        if (in_array($operazione, ['eliminazione', 'modifica_bulk', 'export_completo'])) {
            $this->notificaOperazioneCritica($datiLog);
        }
    }

    /**
     * Verifica integrità dati prima di operazioni critiche
     */
    public function verificaIntegritaDati(array $dati, string $nomeTabella): array
    {
        $datiPuliti = [];

        foreach ($dati as $campo => $valore) {
            // Sanitizzazione XSS
            if (is_string($valore)) {
                $valore = $this->sanitizzaXSS($valore);
            }

            // Verifica SQL Injection
            if (is_string($valore) && $this->rilevaSQLInjection($valore)) {
                $this->logTentativoAttacco('sql_injection', $campo, $valore);
                throw new \InvalidArgumentException("Contenuto non sicuro rilevato nel campo {$campo}");
            }

            // Verifica lunghezza massima
            if (is_string($valore) && strlen($valore) > 10000) {
                throw new \InvalidArgumentException("Contenuto troppo lungo nel campo {$campo}");
            }

            $datiPuliti[$campo] = $valore;
        }

        return $datiPuliti;
    }

    /**
     * Genera token CSRF per operazioni critiche
     */
    public function generaTokenOperazione(string $nomeTabella, string $operazione): string
    {
        $payload = [
            'utente_id' => Auth::id(),
            'tabella' => $nomeTabella,
            'operazione' => $operazione,
            'timestamp' => time(),
            'nonce' => bin2hex(random_bytes(16))
        ];

        return base64_encode(json_encode($payload));
    }

    /**
     * Verifica token operazione
     */
    public function verificaTokenOperazione(string $token, string $nomeTabella, string $operazione): bool
    {
        try {
            $payload = json_decode(base64_decode($token), true);
            
            if (!$payload || !isset($payload['utente_id'], $payload['timestamp'])) {
                return false;
            }

            // Verifica utente
            if ($payload['utente_id'] !== Auth::id()) {
                return false;
            }

            // Verifica operazione
            if ($payload['tabella'] !== $nomeTabella || $payload['operazione'] !== $operazione) {
                return false;
            }

            // Verifica scadenza (5 minuti)
            if (time() - $payload['timestamp'] > 300) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::warning('Token operazione invalido', [
                'token' => substr($token, 0, 20) . '...',
                'errore' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Controllo accesso basato su IP
     */
    public function verificaRestrizioniIP(?\Illuminate\Contracts\Auth\Authenticatable $utente, string $ip): void
    {
        if (!$utente) {
            return;
        }

        // Lista IP bloccati
        $ipBloccati = config('gestione_tabelle.ip_bloccati', []);
        if (in_array($ip, $ipBloccati)) {
            $this->logTentativoAccesso('*', '*', 'ip_bloccato', $utente->id, $ip);
            throw new \Illuminate\Auth\Access\AuthorizationException('Accesso negato da questo IP');
        }

        // Whitelist IP per operazioni critiche (se configurata)
        $whitelistIP = config('gestione_tabelle.ip_whitelist', []);
        if (!empty($whitelistIP) && !in_array($ip, $whitelistIP)) {
            $this->logTentativoAccesso('*', '*', 'ip_non_autorizzato', $utente->id, $ip);
            throw new \Illuminate\Auth\Access\AuthorizationException('IP non autorizzato per operazioni critiche');
        }
    }

    /**
     * Verifica restrizioni temporali
     */
    public function verificaRestrizioniTemporali(?\Illuminate\Contracts\Auth\Authenticatable $utente, string $nomeTabella, string $operazione): void
    {
        if (!$utente) {
            return;
        }

        $ora = now()->hour;
        $giornoSettimana = now()->dayOfWeek;

        // Restrizioni orarie per operazioni critiche
        if (in_array($operazione, ['eliminazione', 'modifica_bulk']) && ($ora < 8 || $ora > 20)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Operazioni critiche consentite solo dalle 8:00 alle 20:00');
        }

        // Restrizioni weekend
        if (in_array($giornoSettimana, [0, 6]) && $operazione === 'eliminazione') {
            throw new \Illuminate\Auth\Access\AuthorizationException('Eliminazioni non consentite nei weekend');
        }
    }

    /**
     * Sanitizzazione XSS
     */
    private function sanitizzaXSS(string $input): string
    {
        // Rimuovi tag script
        $input = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $input);
        
        // Rimuovi javascript: URLs
        $input = preg_replace('/javascript:/i', '', $input);
        
        // Rimuovi on* event handlers
        $input = preg_replace('/\bon\w+\s*=/i', '', $input);
        
        // Escapng caratteri HTML
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Rileva tentativi SQL Injection
     */
    private function rilevaSQLInjection(string $input): bool
    {
        $patternsSQL = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bDELETE\b|\bDROP\b|\bCREATE\b|\bALTER\b)/i',
            '/(\bOR\b|\bAND\b)\s+\d+\s*=\s*\d+/i',
            '/\';\s*(DROP|DELETE|INSERT|UPDATE)/i',
            '/\/\*.*\*\//s',
            '/--\s/i'
        ];

        foreach ($patternsSQL as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ottiene permesso richiesto per operazione
     */
    private function ottieniPermessoRichiesto(string $nomeTabella, string $operazione): string
    {
        $mappaPermessi = $this->configurazioneTabellePermessi[$nomeTabella] ?? [
            'lettura' => "view_{$nomeTabella}",
            'creazione' => "create_{$nomeTabella}",
            'modifica' => "edit_{$nomeTabella}",
            'eliminazione' => "delete_{$nomeTabella}",
            'esportazione' => "export_{$nomeTabella}"
        ];

        return $mappaPermessi[$operazione] ?? "manage_{$nomeTabella}";
    }

    /**
     * Log tentativi di accesso
     */
    private function logTentativoAccesso(string $nomeTabella, string $operazione, string $motivo, ?int $utenteId = null, ?string $ip = null): void
    {
        Log::warning('Tentativo accesso negato gestione tabelle', [
            'utente_id' => $utenteId ?: Auth::id(),
            'tabella' => $nomeTabella,
            'operazione' => $operazione,
            'motivo' => $motivo,
            'ip' => $ip ?: request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Log operazioni autorizzate
     */
    private function logOperazioneAutorizzata(string $nomeTabella, string $operazione, int $utenteId): void
    {
        Log::info('Operazione autorizzata gestione tabelle', [
            'utente_id' => $utenteId,
            'tabella' => $nomeTabella,
            'operazione' => $operazione,
            'ip' => request()->ip(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Log rate limit exceeded
     */
    private function logRateLimitExceeded(int $utenteId, string $nomeTabella, string $operazione): void
    {
        Log::warning('Rate limit superato gestione tabelle', [
            'utente_id' => $utenteId,
            'tabella' => $nomeTabella,
            'operazione' => $operazione,
            'ip' => request()->ip(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Log tentativi di attacco
     */
    private function logTentativoAttacco(string $tipoAttacco, string $campo, string $valore): void
    {
        Log::alert('Tentativo attacco rilevato gestione tabelle', [
            'tipo_attacco' => $tipoAttacco,
            'campo' => $campo,
            'valore' => substr($valore, 0, 100),
            'utente_id' => Auth::id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Scrive log audit per compliance
     */
    private function scriviLogAudit(array $datiLog): void
    {
        // Implementa scrittura su sistema audit dedicato se richiesto
        $logAudit = storage_path('logs/audit_gestione_tabelle.log');
        
        $lineaAudit = json_encode($datiLog) . PHP_EOL;
        file_put_contents($logAudit, $lineaAudit, FILE_APPEND | LOCK_EX);
    }

    /**
     * Notifica operazioni critiche
     */
    private function notificaOperazioneCritica(array $datiLog): void
    {
        // Implementa notifiche per amministratori su operazioni critiche
        Log::channel('slack')->critical('Operazione critica gestione tabelle', $datiLog);
    }

    /**
     * Inizializza configurazione permessi
     */
    private function inizializzaConfigurazionePermessi(): void
    {
        $this->configurazioneTabellePermessi = [
            'banche' => [
                'lettura' => 'view_banks',
                'creazione' => 'create_banks',
                'modifica' => 'edit_banks',
                'eliminazione' => 'delete_banks',
                'esportazione' => 'export_banks'
            ]
            // Aggiungere altre tabelle...
        ];
    }
}