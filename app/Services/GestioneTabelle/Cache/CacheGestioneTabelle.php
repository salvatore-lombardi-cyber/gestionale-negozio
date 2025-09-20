<?php

namespace App\Services\GestioneTabelle\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service enterprise per gestione cache tabelle di configurazione
 * Ottimizza performance con strategie di caching avanzate
 */
class CacheGestioneTabelle
{
    private const PREFISSO_CACHE = 'gestione_tabelle';
    private const TTL_DEFAULT = 3600; // 1 ora
    private const TTL_STATISTICHE = 1800; // 30 minuti
    private const TTL_CONFIGURAZIONE = 7200; // 2 ore

    /**
     * Genera chiave cache univoca per query
     */
    public function generaChiaveCache(string $nomeTabella, array $parametri = []): string
    {
        $hash = md5(serialize($parametri));
        return sprintf('%s:%s:%s', self::PREFISSO_CACHE, $nomeTabella, $hash);
    }

    /**
     * Ricorda valore in cache con TTL
     */
    public function ricorda(string $chiave, \Closure $callback, ?int $ttl = null): mixed
    {
        $ttlEffettivo = $ttl ?? self::TTL_DEFAULT;
        
        try {
            return Cache::remember($chiave, $ttlEffettivo, $callback);
        } catch (\Exception $e) {
            Log::warning('Cache fallback per chiave: ' . $chiave, [
                'errore' => $e->getMessage()
            ]);
            
            // Fallback: esegui callback direttamente
            return $callback();
        }
    }

    /**
     * Mette valore in cache
     */
    public function metti(string $chiave, mixed $valore, ?int $ttl = null): bool
    {
        $ttlEffettivo = $ttl ?? self::TTL_DEFAULT;
        
        try {
            return Cache::put($chiave, $valore, $ttlEffettivo);
        } catch (\Exception $e) {
            Log::error('Errore salvataggio cache: ' . $chiave, [
                'errore' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Ottiene valore dalla cache
     */
    public function ottieni(string $chiave, mixed $default = null): mixed
    {
        try {
            return Cache::get($chiave, $default);
        } catch (\Exception $e) {
            Log::warning('Errore lettura cache: ' . $chiave, [
                'errore' => $e->getMessage()
            ]);
            return $default;
        }
    }

    /**
     * Invalida cache per una tabella specifica
     */
    public function invalidaTabella(string $nomeTabella): void
    {
        try {
            $pattern = self::PREFISSO_CACHE . ':' . $nomeTabella . ':*';
            $this->invalidaPattern($pattern);
            
            // Invalida anche statistiche
            $this->dimentica("statistiche_{$nomeTabella}");
            
            Log::info("Cache invalidata per tabella: {$nomeTabella}");
        } catch (\Exception $e) {
            Log::error("Errore invalidazione cache tabella {$nomeTabella}", [
                'errore' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalida tutta la cache delle tabelle
     */
    public function invalidaTutto(): void
    {
        try {
            $pattern = self::PREFISSO_CACHE . ':*';
            $this->invalidaPattern($pattern);
            
            Log::info('Cache completa invalidata per gestione tabelle');
        } catch (\Exception $e) {
            Log::error('Errore invalidazione cache completa', [
                'errore' => $e->getMessage()
            ]);
        }
    }

    /**
     * Rimuove chiave specifica dalla cache
     */
    public function dimentica(string $chiave): bool
    {
        try {
            return Cache::forget($chiave);
        } catch (\Exception $e) {
            Log::warning("Errore rimozione cache: {$chiave}", [
                'errore' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verifica se chiave esiste in cache
     */
    public function esiste(string $chiave): bool
    {
        try {
            return Cache::has($chiave);
        } catch (\Exception $e) {
            Log::warning("Errore verifica esistenza cache: {$chiave}", [
                'errore' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Pre-carica cache per tabelle critiche
     */
    public function precaricoTabelleCritiche(array $tabelleImportanti): void
    {
        foreach ($tabelleImportanti as $tabella) {
            try {
                $chiaveConfigurazione = "configurazione_{$tabella}";
                $chiaveStatistiche = "statistiche_{$tabella}";
                
                // Pre-caricamento configurazione
                if (!$this->esiste($chiaveConfigurazione)) {
                    Log::info("Pre-caricamento configurazione per: {$tabella}");
                }
                
                // Pre-caricamento statistiche base
                if (!$this->esiste($chiaveStatistiche)) {
                    Log::info("Pre-caricamento statistiche per: {$tabella}");
                }
                
            } catch (\Exception $e) {
                Log::warning("Errore pre-caricamento per {$tabella}", [
                    'errore' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Ottiene statistiche utilizzo cache
     */
    public function ottieniStatisticheCache(): array
    {
        try {
            $store = Cache::getStore();
            
            $stats = [
                'tipo_store' => get_class($store),
                'timestamp_verifica' => now()->toISOString()
            ];

            // Se Redis è disponibile, ottieni statistiche avanzate
            if (method_exists($store, 'getRedis')) {
                $redis = $store->getRedis();
                $info = $redis->info();
                
                $stats = array_merge($stats, [
                    'memoria_utilizzata' => $info['used_memory_human'] ?? 'N/A',
                    'connessioni_totali' => $info['total_connections_received'] ?? 'N/A',
                    'chiavi_totali' => $redis->dbsize(),
                    'hit_rate' => $info['keyspace_hits'] ?? 0,
                    'miss_rate' => $info['keyspace_misses'] ?? 0
                ]);
            }
            
            return $stats;
            
        } catch (\Exception $e) {
            Log::warning('Errore ottenimento statistiche cache', [
                'errore' => $e->getMessage()
            ]);
            
            return [
                'errore' => 'Impossibile ottenere statistiche cache',
                'timestamp' => now()->toISOString()
            ];
        }
    }

    /**
     * Pulisce cache obsoleta
     */
    public function pulisciCacheObsoleta(): int
    {
        $chiavi_rimosse = 0;
        
        try {
            // Implementazione specifica per Redis
            $store = Cache::getStore();
            
            if (method_exists($store, 'getRedis')) {
                $redis = $store->getRedis();
                $pattern = self::PREFISSO_CACHE . ':*';
                $chiavi = $redis->keys($pattern);
                
                foreach ($chiavi as $chiave) {
                    $ttl = $redis->ttl($chiave);
                    
                    // Rimuovi chiavi con TTL < 60 secondi (quasi scadute)
                    if ($ttl > 0 && $ttl < 60) {
                        if ($redis->del($chiave)) {
                            $chiavi_rimosse++;
                        }
                    }
                }
            }
            
            Log::info("Pulizia cache completata: {$chiavi_rimosse} chiavi rimosse");
            
        } catch (\Exception $e) {
            Log::error('Errore pulizia cache obsoleta', [
                'errore' => $e->getMessage()
            ]);
        }
        
        return $chiavi_rimosse;
    }

    /**
     * Cache con tag per invalidazione intelligente
     */
    public function ricordaConTag(string $chiave, array $tags, \Closure $callback, ?int $ttl = null): mixed
    {
        try {
            if (method_exists(Cache::class, 'tags')) {
                return Cache::tags($tags)->remember($chiave, $ttl ?? self::TTL_DEFAULT, $callback);
            }
            
            // Fallback senza tags
            return $this->ricorda($chiave, $callback, $ttl);
            
        } catch (\Exception $e) {
            Log::warning("Cache con tag fallback per: {$chiave}", [
                'errore' => $e->getMessage()
            ]);
            return $callback();
        }
    }

    /**
     * Invalida cache per tag
     */
    public function invalidaTag(string $tag): void
    {
        try {
            if (method_exists(Cache::class, 'tags')) {
                Cache::tags([$tag])->flush();
                Log::info("Cache invalidata per tag: {$tag}");
            }
        } catch (\Exception $e) {
            Log::error("Errore invalidazione tag {$tag}", [
                'errore' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalida pattern di chiavi (implementazione Redis-specific)
     */
    private function invalidaPattern(string $pattern): void
    {
        try {
            $store = Cache::getStore();
            
            if (method_exists($store, 'getRedis')) {
                $redis = $store->getRedis();
                $chiavi = $redis->keys($pattern);
                
                if (!empty($chiavi)) {
                    $redis->del($chiavi);
                }
            }
        } catch (\Exception $e) {
            Log::error("Errore invalidazione pattern: {$pattern}", [
                'errore' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ottiene TTL appropriato per tipo di dati
     */
    public function ottieniTtlAppropriat(string $tipo): int
    {
        return match($tipo) {
            'statistiche' => self::TTL_STATISTICHE,
            'configurazione' => self::TTL_CONFIGURAZIONE,
            'dati_frequenti' => 300, // 5 minuti
            'dati_rari' => 7200, // 2 ore
            default => self::TTL_DEFAULT
        };
    }

    /**
     * Cache con backup locale per resilienza
     */
    public function ricordaConBackup(string $chiave, \Closure $callback, ?int $ttl = null): mixed
    {
        try {
            // Prova cache primaria
            return $this->ricorda($chiave, $callback, $ttl);
            
        } catch (\Exception $e) {
            Log::warning("Cache primaria fallita per {$chiave}, uso backup locale");
            
            // Backup con file cache locale
            $backupFile = storage_path("framework/cache/backup_{$chiave}.php");
            
            if (file_exists($backupFile) && (time() - filemtime($backupFile)) < ($ttl ?? self::TTL_DEFAULT)) {
                return unserialize(file_get_contents($backupFile));
            }
            
            // Esegui callback e salva backup
            $risultato = $callback();
            file_put_contents($backupFile, serialize($risultato));
            
            return $risultato;
        }
    }
}