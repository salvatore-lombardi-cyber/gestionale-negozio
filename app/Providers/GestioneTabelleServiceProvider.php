<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GestioneTabelle\GestioneTabelleService;
use App\Services\GestioneTabelle\Cache\CacheGestioneTabelle;
use App\Services\GestioneTabelle\Validazione\ValidazioneTabelle;
use App\Services\GestioneTabelle\Sicurezza\SicurezzaTabelle;
use App\Repositories\GestioneTabelle\TabelleRepository;
use App\Services\GestioneTabelle\Strategie\StrategiaAssociazioniNatureIva;
use App\Services\GestioneTabelle\Strategie\StrategiaBanche;

/**
 * Service Provider per registrazione servizi gestione tabelle
 * Configura dependency injection per architettura enterprise
 */
class GestioneTabelleServiceProvider extends ServiceProvider
{
    /**
     * Registra servizi nel container
     */
    public function register(): void
    {
        // Registra Repository
        $this->app->singleton(TabelleRepository::class, function ($app) {
            return new TabelleRepository();
        });

        // Registra Cache Service
        $this->app->singleton(CacheGestioneTabelle::class, function ($app) {
            return new CacheGestioneTabelle();
        });

        // Registra Validation Service
        $this->app->singleton(ValidazioneTabelle::class, function ($app) {
            return new ValidazioneTabelle();
        });

        // Registra Security Service
        $this->app->singleton(SicurezzaTabelle::class, function ($app) {
            return new SicurezzaTabelle();
        });

        // Registra Service principale con dependency injection
        $this->app->singleton(GestioneTabelleService::class, function ($app) {
            return new GestioneTabelleService(
                $app->make(TabelleRepository::class),
                $app->make(CacheGestioneTabelle::class),
                $app->make(ValidazioneTabelle::class),
                $app->make(SicurezzaTabelle::class)
            );
        });
    }

    /**
     * Bootstrap servizi dopo registrazione
     */
    public function boot(): void
    {
        // Registra strategie SENZA istanziare servizi per evitare loop circolari
        $this->app->afterResolving(GestioneTabelleService::class, function($service) {
            $this->registraStrategieTabelle($service);
        });

        // Inizializza configurazioni di sicurezza (senza dipendenze)
        $this->inizializzaSicurezza();

        // Registra comandi Artisan se necessario
        $this->registraComandi();
    }

    /**
     * Registra tutte le strategie per le tabelle
     */
    private function registraStrategieTabelle(GestioneTabelleService $gestioneService): void
    {
        $validazioneService = $this->app->make(ValidazioneTabelle::class);

        // Strategia Associazioni Nature IVA (versione semplificata per debug)
        $strategiaAssociazioniNatureIva = new StrategiaAssociazioniNatureIva();
        $gestioneService->registraStrategia('associazioni-nature-iva', $strategiaAssociazioniNatureIva);
        $validazioneService->registraStrategia('associazioni-nature-iva', $strategiaAssociazioniNatureIva);

        // Strategia Banche
        $strategiaBanche = new StrategiaBanche();
        $gestioneService->registraStrategia('banche', $strategiaBanche);
        $validazioneService->registraStrategia('banche', $strategiaBanche);

        // TODO: Aggiungere altre strategie per le restanti 24 tabelle
        // $strategiaClienti = new StrategiaClienti();
        // $gestioneService->registraStrategia('clienti', $strategiaClienti);
        // $validazioneService->registraStrategia('clienti', $strategiaClienti);
        
        // $strategiaFornitori = new StrategiaFornitori();
        // $gestioneService->registraStrategia('fornitori', $strategiaFornitori);
        // $validazioneService->registraStrategia('fornitori', $strategiaFornitori);
    }

    /**
     * Configura cache per tabelle ad alta frequenza
     */
    private function configuraCacheTabelleCritiche(): void
    {
        // Pre-caricamento disabilitato per evitare loop circolari
        // Il cache verrà gestito on-demand nelle strategie
    }

    /**
     * Inizializza configurazioni di sicurezza
     */
    private function inizializzaSicurezza(): void
    {
        // Pubblica configurazioni se non esistono
        $configFile = config_path('gestione_tabelle.php');
        
        if (!file_exists($configFile)) {
            $this->publishes([
                __DIR__.'/../../config/gestione_tabelle.php' => $configFile,
            ], 'gestione-tabelle-config');
        }

        // Registra gate per permessi se non esistono
        $this->registraGatePermessi();
    }

    /**
     * Registra gate per controllo permessi
     */
    private function registraGatePermessi(): void
    {
        $gate = $this->app->make('Illuminate\Contracts\Auth\Access\Gate');

        // Gate generici per tutte le tabelle
        $operazioni = ['view', 'create', 'edit', 'delete', 'export'];
        $tabelle = ['associazioni-nature-iva', 'clients', 'suppliers', 'categories', 'units']; // etc.

        foreach ($tabelle as $tabella) {
            foreach ($operazioni as $operazione) {
                $permesso = "{$operazione}_{$tabella}";
                
                $gate->define($permesso, function ($user) use ($permesso) {
                    // Verifica tramite sistema permessi esistente
                    return method_exists($user, 'hasPermissionTo') ? 
                           $user->hasPermissionTo($permesso) : 
                           $user->can($permesso);
                });
            }
        }

        // Gate speciali per operazioni critiche
        $gate->define('gestione_tabelle_admin', function ($user) {
            return $user->hasRole('admin') || $user->hasPermissionTo('gestione_tabelle_admin');
        });

        $gate->define('gestione_tabelle_bulk_operations', function ($user) {
            return $user->hasRole('admin') || $user->hasPermissionTo('bulk_operations');
        });
    }

    /**
     * Registra comandi Artisan per gestione
     */
    private function registraComandi(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // TODO: Implementare comandi specifici
                // \App\Console\Commands\GestioneTabelle\ClearCache::class,
                // \App\Console\Commands\GestioneTabelle\GenerateStrategies::class,
                // \App\Console\Commands\GestioneTabelle\MigrateData::class,
            ]);
        }
    }

    /**
     * Servizi forniti da questo provider
     */
    public function provides(): array
    {
        return [
            GestioneTabelleService::class,
            TabelleRepository::class,
            CacheGestioneTabelle::class,
            ValidazioneTabelle::class,
            SicurezzaTabelle::class,
        ];
    }
}