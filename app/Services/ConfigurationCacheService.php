<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\TaxRate;
use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\BankAccount;
use App\Models\CompanyProfile;

/**
 * Service per la gestione del caching delle configurazioni
 * Implementa strategie di caching ottimizzate per performance superiori
 */
class ConfigurationCacheService
{
    private const CACHE_TTL = 3600; // 1 ora
    private const CACHE_PREFIX = 'config_';

    /**
     * Ottieni tutte le configurazioni con caching intelligente
     */
    public function getAllConfigurations(): array
    {
        return Cache::remember(self::CACHE_PREFIX . 'all_configurations', self::CACHE_TTL, function () {
            return [
                'company_profile' => $this->getCompanyProfileData(),
                'system_tables' => $this->getSystemTablesData(),
                'bank_accounts' => $this->getBankAccountsData(),
                'statistics' => $this->getConfigurationStats()
            ];
        });
    }

    /**
     * Ottieni dati tabelle di sistema con query ottimizzate
     */
    public function getSystemTablesData(): array
    {
        $cacheKey = self::CACHE_PREFIX . 'system_tables';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            // Query ottimizzate con select specifici
            return [
                'tax_rates' => TaxRate::where('active', true)
                    ->select(['id', 'code', 'description', 'rate', 'created_at'])
                    ->orderBy('code')
                    ->get()
                    ->keyBy('code'),
                    
                'payment_methods' => PaymentMethod::where('active', true)
                    ->select(['id', 'code', 'description', 'type', 'created_at'])
                    ->orderBy('code')
                    ->get()
                    ->keyBy('code'),
                    
                'currencies' => Currency::where('active', true)
                    ->select(['id', 'code', 'name', 'symbol', 'exchange_rate', 'created_at'])
                    ->orderBy('code')
                    ->get()
                    ->keyBy('code')
            ];
        });
    }

    /**
     * Ottieni profilo aziendale con lazy loading
     */
    public function getCompanyProfileData(): ?CompanyProfile
    {
        $cacheKey = self::CACHE_PREFIX . 'company_profile';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return CompanyProfile::select([
                'id', 'ragione_sociale', 'nome', 'cognome', 'email', 
                'telefono1', 'partita_iva', 'logo_path', 'updated_at'
            ])->first();
        });
    }

    /**
     * Ottieni conti bancari con lazy loading (senza dati sensibili in cache)
     */
    public function getBankAccountsData(): array
    {
        $cacheKey = self::CACHE_PREFIX . 'bank_accounts_list';
        
        return Cache::remember($cacheKey, 900, function () { // Cache più breve per sicurezza
            return BankAccount::where('active', true)
                ->select(['uuid', 'nome_banca', 'created_at']) // Solo dati non sensibili
                ->orderBy('nome_banca')
                ->get()
                ->toArray();
        });
    }

    /**
     * Ottieni statistiche configurazioni per dashboard
     */
    public function getConfigurationStats(): array
    {
        $cacheKey = self::CACHE_PREFIX . 'stats';
        
        return Cache::remember($cacheKey, 1800, function () { // 30 minuti
            return [
                'total_tax_rates' => TaxRate::where('active', true)->count(),
                'total_payment_methods' => PaymentMethod::where('active', true)->count(),
                'total_currencies' => Currency::where('active', true)->count(),
                'total_bank_accounts' => BankAccount::where('active', true)->count(),
                'company_profile_complete' => $this->isCompanyProfileComplete(),
                'last_update' => $this->getLastConfigurationUpdate()
            ];
        });
    }

    /**
     * Invalida tutte le cache delle configurazioni
     */
    public function invalidateAll(): void
    {
        $keys = [
            self::CACHE_PREFIX . 'all_configurations',
            self::CACHE_PREFIX . 'system_tables',
            self::CACHE_PREFIX . 'company_profile',
            self::CACHE_PREFIX . 'bank_accounts_list',
            self::CACHE_PREFIX . 'stats'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Invalida cache specifica
     */
    public function invalidateSpecific(string $type): void
    {
        $cacheMap = [
            'company_profile' => [
                self::CACHE_PREFIX . 'company_profile',
                self::CACHE_PREFIX . 'stats',
                self::CACHE_PREFIX . 'all_configurations'
            ],
            'system_tables' => [
                self::CACHE_PREFIX . 'system_tables',
                self::CACHE_PREFIX . 'stats',
                self::CACHE_PREFIX . 'all_configurations'
            ],
            'bank_accounts' => [
                self::CACHE_PREFIX . 'bank_accounts_list',
                self::CACHE_PREFIX . 'stats',
                self::CACHE_PREFIX . 'all_configurations'
            ]
        ];

        if (isset($cacheMap[$type])) {
            foreach ($cacheMap[$type] as $key) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Pre-carica dati critici in cache
     */
    public function warmUpCache(): void
    {
        // Pre-carica i dati più utilizzati
        $this->getSystemTablesData();
        $this->getCompanyProfileData();
        $this->getConfigurationStats();
    }

    /**
     * Ottieni dimensione cache attuale
     */
    public function getCacheSize(): array
    {
        $keys = [
            'all_configurations' => self::CACHE_PREFIX . 'all_configurations',
            'system_tables' => self::CACHE_PREFIX . 'system_tables',
            'company_profile' => self::CACHE_PREFIX . 'company_profile',
            'bank_accounts' => self::CACHE_PREFIX . 'bank_accounts_list',
            'stats' => self::CACHE_PREFIX . 'stats'
        ];

        $result = [];
        foreach ($keys as $name => $key) {
            $data = Cache::get($key);
            $result[$name] = [
                'exists' => $data !== null,
                'size_bytes' => $data ? strlen(serialize($data)) : 0,
                'ttl' => Cache::get($key) ? 'active' : 'expired'
            ];
        }

        return $result;
    }

    /**
     * Verifica se il profilo aziendale è completo
     */
    private function isCompanyProfileComplete(): bool
    {
        $profile = CompanyProfile::first();
        
        if (!$profile) {
            return false;
        }

        $requiredFields = [
            'ragione_sociale', 'partita_iva', 'indirizzo_sede', 
            'cap', 'citta', 'provincia', 'email', 'telefono1'
        ];

        foreach ($requiredFields as $field) {
            if (empty($profile->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ottieni timestamp ultimo aggiornamento configurazioni
     */
    private function getLastConfigurationUpdate(): ?string
    {
        $tables = [
            'company_profiles' => 'updated_at',
            'tax_rates' => 'updated_at',
            'payment_methods' => 'updated_at',
            'currencies' => 'updated_at',
            'bank_accounts' => 'updated_at'
        ];

        $latestUpdate = null;

        foreach ($tables as $table => $column) {
            $latest = DB::table($table)
                ->where('active', true)
                ->max($column);
                
            if ($latest && (!$latestUpdate || $latest > $latestUpdate)) {
                $latestUpdate = $latest;
            }
        }

        return $latestUpdate;
    }

    /**
     * Health check del sistema di cache
     */
    public function healthCheck(): array
    {
        $health = [
            'cache_driver' => config('cache.default'),
            'cache_accessible' => false,
            'keys_count' => 0,
            'total_size_mb' => 0,
            'performance_test' => null
        ];

        try {
            // Test accessibilità cache
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $health['cache_accessible'] = Cache::get($testKey) === 'test';
            Cache::forget($testKey);

            // Test performance
            $start = microtime(true);
            $this->getSystemTablesData();
            $health['performance_test'] = [
                'load_time_ms' => round((microtime(true) - $start) * 1000, 2),
                'status' => 'OK'
            ];

            // Calcola statistiche cache
            $cacheData = $this->getCacheSize();
            $health['keys_count'] = count(array_filter($cacheData, fn($item) => $item['exists']));
            $health['total_size_mb'] = round(
                array_sum(array_column($cacheData, 'size_bytes')) / (1024 * 1024), 
                2
            );

        } catch (\Exception $e) {
            $health['error'] = $e->getMessage();
        }

        return $health;
    }
}