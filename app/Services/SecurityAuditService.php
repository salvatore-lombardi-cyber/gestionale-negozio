<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Service per audit di sicurezza e monitoraggio
 * Implementa logging avanzato secondo le best practice OWASP
 */
class SecurityAuditService
{
    private const AUDIT_CHANNEL = 'security_audit';
    
    /**
     * Log accesso alle configurazioni con dettagli completi
     */
    public function logConfigurationAccess(string $action, array $context = []): void
    {
        $baseContext = $this->getBaseSecurityContext();
        
        $auditData = array_merge($baseContext, [
            'event_type' => 'configuration_access',
            'action' => $action,
            'severity' => $this->getSeverityLevel($action),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::channel(self::AUDIT_CHANNEL)->info("Configuration access: {$action}", $auditData);
        
        // Salva in database per audit trail permanente
        $this->storeSecurityEvent($auditData);
    }

    /**
     * Log tentativi di accesso non autorizzato
     */
    public function logUnauthorizedAccess(string $resource, array $context = []): void
    {
        $baseContext = $this->getBaseSecurityContext();
        
        $auditData = array_merge($baseContext, [
            'event_type' => 'unauthorized_access',
            'resource' => $resource,
            'severity' => 'HIGH',
            'timestamp' => now()->toISOString(),
            'threat_level' => $this->calculateThreatLevel($baseContext['ip_address']),
        ], $context);

        Log::channel(self::AUDIT_CHANNEL)->warning("Unauthorized access attempt: {$resource}", $auditData);
        
        $this->storeSecurityEvent($auditData);
        $this->checkForSuspiciousActivity($baseContext['ip_address'], $baseContext['user_id']);
    }

    /**
     * Log modifiche ai dati sensibili
     */
    public function logSensitiveDataChange(string $dataType, string $operation, array $context = []): void
    {
        $baseContext = $this->getBaseSecurityContext();
        
        $auditData = array_merge($baseContext, [
            'event_type' => 'sensitive_data_change',
            'data_type' => $dataType,
            'operation' => $operation,
            'severity' => 'MEDIUM',
            'timestamp' => now()->toISOString(),
            'data_classification' => $this->classifyDataSensitivity($dataType),
        ], $context);

        Log::channel(self::AUDIT_CHANNEL)->notice("Sensitive data change: {$dataType} - {$operation}", $auditData);
        
        $this->storeSecurityEvent($auditData);
    }

    /**
     * Log errori di validazione sospetti
     */
    public function logValidationFailure(string $field, string $error, array $context = []): void
    {
        $baseContext = $this->getBaseSecurityContext();
        
        $auditData = array_merge($baseContext, [
            'event_type' => 'validation_failure',
            'field' => $field,
            'error' => $error,
            'severity' => $this->isSecurityValidationError($error) ? 'HIGH' : 'LOW',
            'timestamp' => now()->toISOString(),
            'potential_attack' => $this->detectPotentialAttack($error),
        ], $context);

        $logLevel = $this->isSecurityValidationError($error) ? 'warning' : 'info';
        Log::channel(self::AUDIT_CHANNEL)->$logLevel("Validation failure: {$field}", $auditData);
        
        $this->storeSecurityEvent($auditData);
    }

    /**
     * Log performance anomalie
     */
    public function logPerformanceAnomaly(string $operation, float $duration, array $context = []): void
    {
        if ($duration > 5.0) { // Operazioni che richiedono più di 5 secondi
            $baseContext = $this->getBaseSecurityContext();
            
            $auditData = array_merge($baseContext, [
                'event_type' => 'performance_anomaly',
                'operation' => $operation,
                'duration_seconds' => $duration,
                'severity' => $duration > 10.0 ? 'HIGH' : 'MEDIUM',
                'timestamp' => now()->toISOString(),
            ], $context);

            Log::channel(self::AUDIT_CHANNEL)->warning("Performance anomaly: {$operation}", $auditData);
        }
    }

    /**
     * Genera report di sicurezza giornaliero
     */
    public function generateDailySecurityReport(): array
    {
        $yesterday = Carbon::yesterday();
        
        $report = [
            'date' => $yesterday->toDateString(),
            'total_events' => $this->getSecurityEventsCount($yesterday),
            'event_types' => $this->getEventTypeBreakdown($yesterday),
            'threat_summary' => $this->getThreatSummary($yesterday),
            'top_users' => $this->getTopActiveUsers($yesterday),
            'suspicious_ips' => $this->getSuspiciousIPs($yesterday),
            'performance_metrics' => $this->getPerformanceMetrics($yesterday),
            'recommendations' => $this->generateSecurityRecommendations($yesterday),
        ];

        Log::channel(self::AUDIT_CHANNEL)->info('Daily security report generated', $report);
        
        return $report;
    }

    /**
     * Ottieni contesto base per sicurezza
     */
    private function getBaseSecurityContext(): array
    {
        $request = request();
        
        return [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'referrer' => $request->headers->get('referer'),
            'geo_location' => $this->getGeoLocation($request->ip()),
        ];
    }

    /**
     * Determina livello di severità basato sull'azione
     */
    private function getSeverityLevel(string $action): string
    {
        $highSeverityActions = [
            'delete', 'remove', 'destroy', 'update_sensitive', 'encrypt', 'decrypt'
        ];
        
        $mediumSeverityActions = [
            'update', 'create', 'store', 'modify'
        ];
        
        foreach ($highSeverityActions as $highAction) {
            if (stripos($action, $highAction) !== false) {
                return 'HIGH';
            }
        }
        
        foreach ($mediumSeverityActions as $mediumAction) {
            if (stripos($action, $mediumAction) !== false) {
                return 'MEDIUM';
            }
        }
        
        return 'LOW';
    }

    /**
     * Calcola livello di minaccia per IP
     */
    private function calculateThreatLevel(string $ip): string
    {
        // Verifica tentativi recenti dallo stesso IP
        $recentAttempts = $this->getRecentSecurityEvents($ip, 24);
        
        if ($recentAttempts >= 10) return 'CRITICAL';
        if ($recentAttempts >= 5) return 'HIGH';
        if ($recentAttempts >= 2) return 'MEDIUM';
        
        return 'LOW';
    }

    /**
     * Classifica la sensibilità dei dati
     */
    private function classifyDataSensitivity(string $dataType): string
    {
        $highSensitivityData = [
            'bank_account', 'iban', 'swift', 'password', 'tax_id', 'personal_id'
        ];
        
        $mediumSensitivityData = [
            'company_profile', 'email', 'phone', 'address'
        ];
        
        foreach ($highSensitivityData as $sensitiveData) {
            if (stripos($dataType, $sensitiveData) !== false) {
                return 'HIGH';
            }
        }
        
        foreach ($mediumSensitivityData as $mediumData) {
            if (stripos($dataType, $mediumData) !== false) {
                return 'MEDIUM';
            }
        }
        
        return 'LOW';
    }

    /**
     * Verifica se l'errore di validazione indica un tentativo di attacco
     */
    private function isSecurityValidationError(string $error): bool
    {
        $securityPatterns = [
            'script', 'javascript', 'vbscript', 'onload', 'onerror',
            'select.*from', 'union.*select', 'drop.*table', 'insert.*into',
            '../', '..\\', 'etc/passwd', 'cmd.exe', 'powershell'
        ];
        
        foreach ($securityPatterns as $pattern) {
            if (stripos($error, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Rileva potenziali tipologie di attacco
     */
    private function detectPotentialAttack(string $input): ?string
    {
        if (preg_match('/script|javascript|vbscript/i', $input)) {
            return 'XSS_ATTEMPT';
        }
        
        if (preg_match('/select.*from|union.*select|drop.*table/i', $input)) {
            return 'SQL_INJECTION_ATTEMPT';
        }
        
        if (preg_match('/\.\.[\/\\\\]|etc\/passwd|cmd\.exe/i', $input)) {
            return 'PATH_TRAVERSAL_ATTEMPT';
        }
        
        if (strlen($input) > 10000) {
            return 'BUFFER_OVERFLOW_ATTEMPT';
        }
        
        return null;
    }

    /**
     * Ottieni geolocalizzazione IP (implementazione semplificata)
     */
    private function getGeoLocation(string $ip): ?string
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'localhost';
        }
        
        // Qui potresti integrare un servizio di geolocalizzazione
        // Per ora returno null per evitare chiamate esterne
        return null;
    }

    /**
     * Salva evento di sicurezza nel database
     */
    private function storeSecurityEvent(array $data): void
    {
        try {
            DB::table('security_audit_log')->insert([
                'event_type' => $data['event_type'],
                'user_id' => $data['user_id'] ?? null,
                'ip_address' => $data['ip_address'],
                'severity' => $data['severity'],
                'data' => json_encode($data),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fallback: almeno logga l'errore
            Log::error('Failed to store security event', ['error' => $e->getMessage(), 'data' => $data]);
        }
    }

    /**
     * Verifica attività sospette
     */
    private function checkForSuspiciousActivity(string $ip, ?int $userId): void
    {
        $recentEvents = $this->getRecentSecurityEvents($ip, 1, ['unauthorized_access', 'validation_failure']);
        
        if ($recentEvents >= 5) {
            // IP sospetto - potrebbe essere necessario bloccare temporaneamente
            Log::channel(self::AUDIT_CHANNEL)->critical("Suspicious activity detected", [
                'ip_address' => $ip,
                'user_id' => $userId,
                'event_count' => $recentEvents,
                'recommended_action' => 'TEMPORARY_IP_BLOCK'
            ]);
        }
    }

    /**
     * Conta eventi di sicurezza recenti per IP
     */
    private function getRecentSecurityEvents(string $ip, int $hours, array $eventTypes = []): int
    {
        try {
            $query = DB::table('security_audit_log')
                ->where('ip_address', $ip)
                ->where('created_at', '>=', Carbon::now()->subHours($hours));
                
            if (!empty($eventTypes)) {
                $query->whereIn('event_type', $eventTypes);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Statistiche eventi per data
     */
    private function getSecurityEventsCount(Carbon $date): int
    {
        try {
            return DB::table('security_audit_log')
                ->whereDate('created_at', $date)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Breakdown eventi per tipo
     */
    private function getEventTypeBreakdown(Carbon $date): array
    {
        try {
            return DB::table('security_audit_log')
                ->selectRaw('event_type, count(*) as count')
                ->whereDate('created_at', $date)
                ->groupBy('event_type')
                ->pluck('count', 'event_type')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Summary minacce
     */
    private function getThreatSummary(Carbon $date): array
    {
        try {
            return DB::table('security_audit_log')
                ->selectRaw('severity, count(*) as count')
                ->whereDate('created_at', $date)
                ->groupBy('severity')
                ->pluck('count', 'severity')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Top utenti attivi
     */
    private function getTopActiveUsers(Carbon $date): array
    {
        try {
            return DB::table('security_audit_log')
                ->selectRaw('user_id, count(*) as event_count')
                ->whereDate('created_at', $date)
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('event_count')
                ->limit(10)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * IP sospetti
     */
    private function getSuspiciousIPs(Carbon $date): array
    {
        try {
            return DB::table('security_audit_log')
                ->selectRaw('ip_address, count(*) as event_count')
                ->whereDate('created_at', $date)
                ->whereIn('event_type', ['unauthorized_access', 'validation_failure'])
                ->groupBy('ip_address')
                ->havingRaw('count(*) > 3')
                ->orderByDesc('event_count')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Metriche performance
     */
    private function getPerformanceMetrics(Carbon $date): array
    {
        // Implementazione semplificata
        return [
            'slow_operations' => 0,
            'average_response_time' => 0.5,
            'peak_usage_hour' => '14:00'
        ];
    }

    /**
     * Genera raccomandazioni di sicurezza
     */
    private function generateSecurityRecommendations(Carbon $date): array
    {
        $recommendations = [];
        
        $suspiciousIPs = $this->getSuspiciousIPs($date);
        if (count($suspiciousIPs) > 0) {
            $recommendations[] = 'Considera il blocco temporaneo degli IP con attività sospetta';
        }
        
        $threatSummary = $this->getThreatSummary($date);
        if (($threatSummary['HIGH'] ?? 0) > 10) {
            $recommendations[] = 'Rivedi le policy di sicurezza - rilevata alta attività di minacce';
        }
        
        return $recommendations;
    }
}