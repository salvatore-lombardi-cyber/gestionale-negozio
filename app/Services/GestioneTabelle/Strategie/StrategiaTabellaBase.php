<?php

namespace App\Services\GestioneTabelle\Strategie;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Classe base astratta che implementa comportamenti comuni
 * Le strategie specifiche estendono questa classe
 */
abstract class StrategiaTabellaBase implements StrategiaTabella
{
    protected Model $model;
    protected string $nomeTabella;
    protected array $configurazione;

    public function __construct()
    {
        $this->model = $this->creaModel();
        $this->nomeTabella = $this->ottieniNomeTabella();
        $this->configurazione = $this->definizioneConfigurazione();
    }

    /**
     * Template method per ottenere dati con pattern comune
     */
    public function ottieniDati(Request $request): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        
        // Applica filtri base
        $this->applicaFiltriBase($query, $request);
        
        // Applica filtri specifici della strategia
        $this->applicaFiltri($query, $request);
        
        // Applica ordinamento
        $this->applicaOrdinamento($query);
        
        return $query->paginate(20)->withQueryString();
    }

    /**
     * Template method per creazione
     */
    public function crea(array $dati): Model
    {
        $this->primaCreazione($dati);
        
        $elemento = $this->model->create($dati);
        
        $this->dopoCreazione($elemento);
        
        return $elemento;
    }

    /**
     * Template method per aggiornamento
     */
    public function aggiorna(int $id, array $dati): Model
    {
        $elemento = $this->model->findOrFail($id);
        
        $this->primaAggiornamento($elemento, $dati);
        
        $elemento->update($dati);
        
        $this->dopoAggiornamento($elemento);
        
        return $elemento;
    }

    /**
     * Template method per eliminazione
     */
    public function elimina(int $id): bool
    {
        $elemento = $this->model->findOrFail($id);
        
        $this->primaEliminazione($elemento);
        
        $successo = $elemento->delete();
        
        if ($successo) {
            $this->dopoEliminazione($id);
        }
        
        return $successo;
    }

    /**
     * Trova elemento per ID
     */
    public function trova(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Ottiene tutti i dati per export
     */
    public function ottieniTuttiDati(): Collection
    {
        return $this->model->all();
    }

    /**
     * Export base in Excel/CSV
     */
    public function esporta(Collection $dati, string $formato): string
    {
        // Implementazione base export
        $campi = $this->ottieniCampiEsportabili();
        
        switch ($formato) {
            case 'csv':
                return $this->esportaCsv($dati, $campi);
            case 'excel':
                return $this->esportaExcel($dati, $campi);
            default:
                throw new \InvalidArgumentException("Formato export non supportato: {$formato}");
        }
    }

    /**
     * Statistiche base
     */
    public function calcolaStatistiche(): array
    {
        return [
            'totale' => $this->model->count(),
            'attivi' => $this->model->where('active', true)->count(),
            'creati_oggi' => $this->model->whereDate('created_at', today())->count(),
            'creati_settimana' => $this->model->where('created_at', '>=', now()->subWeek())->count(),
        ];
    }

    /**
     * Configurazione base
     */
    public function ottieniConfigurazione(): array
    {
        return $this->configurazione;
    }

    /**
     * Verifica duplicati base (per codice)
     */
    public function verificaDuplicati(array $dati, ?int $excludeId = null): ?string
    {
        if (!isset($dati['code'])) {
            return null;
        }

        $query = $this->model->where('code', $dati['code']);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        if ($query->exists()) {
            return 'Un elemento con questo codice esiste già.';
        }
        
        return null;
    }

    /**
     * Filtri base comuni a tutte le tabelle
     */
    protected function applicaFiltriBase(Builder $query, Request $request): void
    {
        // Filtro ricerca testo
        if ($ricerca = $request->get('search')) {
            $campiRicercabili = $this->ottieniCampiRicercabili();
            
            $query->where(function($q) use ($ricerca, $campiRicercabili) {
                foreach ($campiRicercabili as $campo) {
                    $q->orWhere($campo, 'like', "%{$ricerca}%");
                }
            });
        }

        // Filtro stato attivo/inattivo
        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }
    }

    /**
     * Ordinamento predefinito
     */
    protected function applicaOrdinamento(Builder $query): void
    {
        $ordinamento = $this->ottieniOrdinamentoPredefinito();
        
        foreach ($ordinamento as $campo => $direzione) {
            $query->orderBy($campo, $direzione);
        }
    }

    /**
     * Export CSV
     */
    private function esportaCsv(Collection $dati, array $campi): string
    {
        $filename = storage_path("app/exports/{$this->nomeTabella}_" . now()->format('Y-m-d_H-i-s') . '.csv');
        
        $handle = fopen($filename, 'w');
        
        // Header
        fputcsv($handle, array_keys($campi));
        
        // Dati
        foreach ($dati as $elemento) {
            $riga = [];
            foreach ($campi as $campo => $label) {
                $riga[] = $elemento->{$campo} ?? '';
            }
            fputcsv($handle, $riga);
        }
        
        fclose($handle);
        
        return $filename;
    }

    /**
     * Export Excel (placeholder - implementazione completa con PhpSpreadsheet)
     */
    private function esportaExcel(Collection $dati, array $campi): string
    {
        // Per ora returnamo CSV, implementazione Excel richiede PhpSpreadsheet
        return $this->esportaCsv($dati, $campi);
    }

    /**
     * Hook base vuoti - le strategie concrete possono sovrascriverli
     */
    public function primaCreazione(array &$dati): void {}
    public function dopoCreazione(Model $elemento): void {}
    public function primaAggiornamento(Model $elemento, array &$dati): void {}
    public function dopoAggiornamento(Model $elemento): void {}
    public function primaEliminazione(Model $elemento): void {}
    public function dopoEliminazione(int $id): void {}

    /**
     * Metodi astratti che devono essere implementati dalle strategie concrete
     */
    abstract protected function creaModel(): Model;
    abstract protected function ottieniNomeTabella(): string;
    abstract protected function definizioneConfigurazione(): array;
    abstract public function ottieniRegoleValidazione(?int $id = null): array;
    abstract public function ottieniMessaggiValidazione(): array;
    abstract public function applicaFiltri($query, Request $request);
    abstract public function ottieniOrdinamentoPredefinito(): array;
    abstract public function ottieniTitolo(): string;
    abstract public function ottieniIcona(): string;
    abstract public function ottieniColore(): string;
    abstract public function ottieniDescrizione(): string;
    abstract public function ottieniCampiVisibili(): array;
    abstract public function ottieniCampiRicercabili(): array;
    abstract public function ottieniCampiEsportabili(): array;
    abstract public function azioniDisponibili(): array;
}