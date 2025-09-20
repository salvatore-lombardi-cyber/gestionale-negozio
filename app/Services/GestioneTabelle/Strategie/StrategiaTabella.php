<?php

namespace App\Services\GestioneTabelle\Strategie;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface per implementare Strategy Pattern
 * Ogni tabella ha la sua strategia specifica
 */
interface StrategiaTabella
{
    /**
     * Ottiene dati paginati con filtri
     */
    public function ottieniDati(Request $request): LengthAwarePaginator;

    /**
     * Crea nuovo elemento
     */
    public function crea(array $dati): Model;

    /**
     * Aggiorna elemento esistente
     */
    public function aggiorna(int $id, array $dati): Model;

    /**
     * Elimina elemento
     */
    public function elimina(int $id): bool;

    /**
     * Trova elemento per ID
     */
    public function trova(int $id): ?Model;

    /**
     * Ottiene tutti i dati (per export)
     */
    public function ottieniTuttiDati(): Collection;

    /**
     * Esporta dati in formato specificato
     */
    public function esporta(Collection $dati, string $formato): string;

    /**
     * Calcola statistiche specifiche della tabella
     */
    public function calcolaStatistiche(): array;

    /**
     * Ottiene configurazione tabella
     */
    public function ottieniConfigurazione(): array;

    /**
     * Ottiene regole di validazione
     */
    public function ottieniRegoleValidazione(?int $id = null): array;

    /**
     * Ottiene messaggi di validazione personalizzati
     */
    public function ottieniMessaggiValidazione(): array;

    /**
     * Verifica duplicati specifici della tabella
     */
    public function verificaDuplicati(array $dati, ?int $excludeId = null): ?string;

    /**
     * Applica filtri di ricerca specifici
     */
    public function applicaFiltri($query, Request $request);

    /**
     * Ottiene ordinamento predefinito
     */
    public function ottieniOrdinamentoPredefinito(): array;

    /**
     * Info display della tabella
     */
    public function ottieniTitolo(): string;
    public function ottieniIcona(): string;
    public function ottieniColore(): string;
    public function ottieniDescrizione(): string;

    /**
     * Campi specifici per la tabella
     */
    public function ottieniCampiVisibili(): array;
    public function ottieniCampiRicercabili(): array;
    public function ottieniCampiEsportabili(): array;

    /**
     * Azioni specifiche della tabella
     */
    public function azioniDisponibili(): array;

    /**
     * Hook per operazioni pre/post
     */
    public function primaCreazione(array &$dati): void;
    public function dopoCreazione(Model $elemento): void;
    public function primaAggiornamento(Model $elemento, array &$dati): void;
    public function dopoAggiornamento(Model $elemento): void;
    public function primaEliminazione(Model $elemento): void;
    public function dopoEliminazione(int $id): void;
}