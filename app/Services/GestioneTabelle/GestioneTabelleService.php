<?php

namespace App\Services\GestioneTabelle;

use App\Services\GestioneTabelle\Strategie\StrategiaTabella;
use App\Services\GestioneTabelle\Cache\CacheGestioneTabelle;
use App\Services\GestioneTabelle\Validazione\ValidazioneTabelle;
use App\Services\GestioneTabelle\Sicurezza\SicurezzaTabelle;
use App\Repositories\GestioneTabelle\TabelleRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Service principale per la gestione delle tabelle di configurazione
 * Architettura enterprise con separation of concerns
 */
class GestioneTabelleService
{
    private array $strategieTabelle = [];
    
    public function __construct(
        private readonly TabelleRepository $repository,
        private readonly CacheGestioneTabelle $cache,
        private readonly ValidazioneTabelle $validazione,
        private readonly SicurezzaTabelle $sicurezza
    ) {}

    /**
     * Registra strategia per una tabella specifica
     */
    public function registraStrategia(string $nomeTabella, StrategiaTabella $strategia): void
    {
        $this->strategieTabelle[$nomeTabella] = $strategia;
    }

    /**
     * Ottiene dati tabella con caching e filtering
     */
    public function ottieniDatiTabella(string $nomeTabella, Request $request): LengthAwarePaginator
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'lettura');
        
        $cacheKey = $this->cache->generaChiaveCache($nomeTabella, $request->all());
        
        return $this->cache->ricorda($cacheKey, function() use ($nomeTabella, $request) {
            $strategia = $this->ottieniStrategia($nomeTabella);
            return $strategia->ottieniDati($request);
        });
    }

    /**
     * Crea nuovo elemento nella tabella
     */
    public function creaElemento(string $nomeTabella, array $dati): Model
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'creazione');
        
        $datiValidati = $this->validazione->valida($nomeTabella, $dati);
        
        $strategia = $this->ottieniStrategia($nomeTabella);
        $elemento = $strategia->crea($datiValidati);
        
        $this->cache->invalidaTabella($nomeTabella);
        $this->sicurezza->logOperazione('creazione', $nomeTabella, $elemento->id);
        
        return $elemento;
    }

    /**
     * Aggiorna elemento esistente
     */
    public function aggiornaElemento(string $nomeTabella, int $id, array $dati): Model
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'modifica');
        
        $datiValidati = $this->validazione->valida($nomeTabella, $dati, $id);
        
        $strategia = $this->ottieniStrategia($nomeTabella);
        $elemento = $strategia->aggiorna($id, $datiValidati);
        
        $this->cache->invalidaTabella($nomeTabella);
        $this->sicurezza->logOperazione('modifica', $nomeTabella, $id);
        
        return $elemento;
    }

    /**
     * Elimina elemento
     */
    public function eliminaElemento(string $nomeTabella, int $id): bool
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'eliminazione');
        
        $strategia = $this->ottieniStrategia($nomeTabella);
        $successo = $strategia->elimina($id);
        
        if ($successo) {
            $this->cache->invalidaTabella($nomeTabella);
            $this->sicurezza->logOperazione('eliminazione', $nomeTabella, $id);
        }
        
        return $successo;
    }

    /**
     * Ottiene dettagli singolo elemento
     */
    public function ottieniElemento(string $nomeTabella, int $id): ?Model
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'lettura');
        
        $strategia = $this->ottieniStrategia($nomeTabella);
        return $strategia->trova($id);
    }

    /**
     * Esporta dati tabella
     */
    public function esportaTabella(string $nomeTabella, string $formato = 'excel'): string
    {
        $this->sicurezza->verificaPermessi($nomeTabella, 'esportazione');
        
        $strategia = $this->ottieniStrategia($nomeTabella);
        $dati = $strategia->ottieniTuttiDati();
        
        $this->sicurezza->logOperazione('esportazione', $nomeTabella);
        
        return $strategia->esporta($dati, $formato);
    }

    /**
     * Ottiene statistiche tabella
     */
    public function ottieniStatistiche(string $nomeTabella): array
    {
        $cacheKey = "statistiche_{$nomeTabella}";
        
        return $this->cache->ricorda($cacheKey, function() use ($nomeTabella) {
            $strategia = $this->ottieniStrategia($nomeTabella);
            return $strategia->calcolaStatistiche();
        }, 3600);
    }

    /**
     * Ottiene configurazione tabella
     */
    public function ottieniConfigurazione(string $nomeTabella): array
    {
        $strategia = $this->ottieniStrategia($nomeTabella);
        return $strategia->ottieniConfigurazione();
    }

    /**
     * Lista tutte le tabelle disponibili
     */
    public function listaTabelleDisponibili(): Collection
    {
        return collect($this->strategieTabelle)
            ->keys()
            ->filter(function($nomeTabella) {
                // Per ora permettiamo l'accesso a tutte le tabelle registrate
                // TODO: Implementare controllo permessi quando il sistema di auth è pronto
                return true;
            })
            ->map(function($nomeTabella) {
                $strategia = $this->strategieTabelle[$nomeTabella];
                return [
                    'nome' => $nomeTabella,
                    'titolo' => $strategia->ottieniTitolo(),
                    'icona' => $strategia->ottieniIcona(),
                    'colore' => $strategia->ottieniColore(),
                    'descrizione' => $strategia->ottieniDescrizione()
                ];
            })
            ->values();
    }

    /**
     * Ottiene strategia per tabella specifica
     */
    private function ottieniStrategia(string $nomeTabella): StrategiaTabella
    {
        if (!isset($this->strategieTabelle[$nomeTabella])) {
            throw new \InvalidArgumentException("Strategia non trovata per tabella: {$nomeTabella}");
        }

        return $this->strategieTabelle[$nomeTabella];
    }

    /**
     * Verifica se tabella è gestita
     */
    public function tabellaGestita(string $nomeTabella): bool
    {
        return isset($this->strategieTabelle[$nomeTabella]);
    }

    /**
     * Ottiene tutte le tabelle registrate
     */
    public function ottieniTabelleRegistrate(): array
    {
        return array_keys($this->strategieTabelle);
    }
}