<?php

namespace App\Repositories\GestioneTabelle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Repository enterprise per operazioni database tabelle configurazione
 * Astrae le operazioni database dal business logic
 */
class TabelleRepository
{
    /**
     * Trova elemento per ID con caching opzionale
     */
    public function trovaElemento(Model $model, int $id, array $relazioni = []): ?Model
    {
        $query = $model->newQuery();
        
        if (!empty($relazioni)) {
            $query->with($relazioni);
        }
        
        return $query->find($id);
    }

    /**
     * Cerca elementi con filtri e paginazione
     */
    public function cercaConFiltri(
        Model $model, 
        callable $filtriCallback = null,
        int $perPage = 20,
        array $ordinamento = [],
        array $relazioni = []
    ): LengthAwarePaginator {
        $query = $model->newQuery();
        
        // Applica relazioni
        if (!empty($relazioni)) {
            $query->with($relazioni);
        }
        
        // Applica filtri personalizzati
        if ($filtriCallback) {
            $filtriCallback($query);
        }
        
        // Applica ordinamento
        if (!empty($ordinamento)) {
            foreach ($ordinamento as $campo => $direzione) {
                $query->orderBy($campo, $direzione);
            }
        }
        
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Conta elementi con condizioni
     */
    public function conta(Model $model, callable $filtriCallback = null): int
    {
        $query = $model->newQuery();
        
        if ($filtriCallback) {
            $filtriCallback($query);
        }
        
        return $query->count();
    }

    /**
     * Ottiene tutti gli elementi con relazioni
     */
    public function ottieniTutti(Model $model, array $relazioni = [], array $ordinamento = []): Collection
    {
        $query = $model->newQuery();
        
        if (!empty($relazioni)) {
            $query->with($relazioni);
        }
        
        if (!empty($ordinamento)) {
            foreach ($ordinamento as $campo => $direzione) {
                $query->orderBy($campo, $direzione);
            }
        }
        
        return $query->get();
    }

    /**
     * Crea nuovo elemento con transazione
     */
    public function creaElemento(Model $model, array $dati): Model
    {
        return DB::transaction(function () use ($model, $dati) {
            return $model->create($dati);
        });
    }

    /**
     * Aggiorna elemento esistente con transazione
     */
    public function aggiornaElemento(Model $elemento, array $dati): bool
    {
        return DB::transaction(function () use ($elemento, $dati) {
            return $elemento->update($dati);
        });
    }

    /**
     * Elimina elemento con transazione
     */
    public function eliminaElemento(Model $elemento): bool
    {
        return DB::transaction(function () use ($elemento) {
            return $elemento->delete();
        });
    }

    /**
     * Elimina elementi multipli con transazione
     */
    public function eliminaElementiMultipli(Model $model, array $ids): int
    {
        return DB::transaction(function () use ($model, $ids) {
            return $model->whereIn('id', $ids)->delete();
        });
    }

    /**
     * Verifica esistenza con condizioni
     */
    public function esiste(Model $model, array $condizioni): bool
    {
        $query = $model->newQuery();
        
        foreach ($condizioni as $campo => $valore) {
            $query->where($campo, $valore);
        }
        
        return $query->exists();
    }

    /**
     * Verifica unicità campo
     */
    public function verificaUnicita(
        Model $model, 
        string $campo, 
        $valore, 
        ?int $escludiId = null
    ): bool {
        $query = $model->where($campo, $valore);
        
        if ($escludiId) {
            $query->where('id', '!=', $escludiId);
        }
        
        return !$query->exists();
    }

    /**
     * Operazioni bulk per performance
     */
    public function inserimentoBulk(string $nomeTabella, array $dati): bool
    {
        return DB::transaction(function () use ($nomeTabella, $dati) {
            // Aggiungi timestamps se non presenti
            $now = now();
            foreach ($dati as &$record) {
                if (!isset($record['created_at'])) {
                    $record['created_at'] = $now;
                }
                if (!isset($record['updated_at'])) {
                    $record['updated_at'] = $now;
                }
            }
            
            return DB::table($nomeTabella)->insert($dati);
        });
    }

    /**
     * Aggiornamento bulk con condizioni
     */
    public function aggiornamentoBulk(
        string $nomeTabella, 
        array $dati, 
        array $condizioni
    ): int {
        return DB::transaction(function () use ($nomeTabella, $dati, $condizioni) {
            $query = DB::table($nomeTabella);
            
            foreach ($condizioni as $campo => $valore) {
                $query->where($campo, $valore);
            }
            
            $dati['updated_at'] = now();
            
            return $query->update($dati);
        });
    }

    /**
     * Ottiene statistiche aggregate
     */
    public function ottieniStatistiche(Model $model, callable $statsCallback = null): array
    {
        $query = $model->newQuery();
        
        if ($statsCallback) {
            return $statsCallback($query);
        }
        
        // Statistiche base
        return [
            'totale' => $query->count(),
            'attivi' => $query->where('active', true)->count(),
            'inattivi' => $query->where('active', false)->count(),
            'creati_oggi' => $query->whereDate('created_at', today())->count(),
            'creati_settimana' => $query->where('created_at', '>=', now()->subWeek())->count(),
            'creati_mese' => $query->where('created_at', '>=', now()->subMonth())->count(),
        ];
    }

    /**
     * Ricerca full-text su più campi
     */
    public function ricercaFullText(
        Model $model,
        string $termine,
        array $campi,
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = $model->newQuery();
        
        $query->where(function ($q) use ($termine, $campi) {
            foreach ($campi as $campo) {
                $q->orWhere($campo, 'like', "%{$termine}%");
            }
        });
        
        return $query->paginate($perPage);
    }

    /**
     * Operazioni raw query sicure per performance critiche
     */
    public function eseguiQuerySicura(string $sql, array $bindings = []): \Illuminate\Support\Collection
    {
        return DB::select($sql, $bindings);
    }

    /**
     * Ottiene schema tabella per validazioni dinamiche
     */
    public function ottieniSchemaTabella(string $nomeTabella): array
    {
        $colonne = DB::getSchemaBuilder()->getColumnListing($nomeTabella);
        $schema = [];
        
        foreach ($colonne as $colonna) {
            $tipoColonna = DB::getSchemaBuilder()->getColumnType($nomeTabella, $colonna);
            $schema[$colonna] = $tipoColonna;
        }
        
        return $schema;
    }

    /**
     * Backup dati prima di operazioni critiche
     */
    public function backupDati(Model $model, array $ids): array
    {
        return $model->whereIn('id', $ids)->get()->toArray();
    }

    /**
     * Ripristino dati da backup
     */
    public function ripristinaDaBackup(string $nomeTabella, array $backupDati): bool
    {
        return DB::transaction(function () use ($nomeTabella, $backupDati) {
            foreach ($backupDati as $record) {
                DB::table($nomeTabella)->updateOrInsert(
                    ['id' => $record['id']], 
                    $record
                );
            }
            return true;
        });
    }

    /**
     * Ottiene relazioni dinamiche per una tabella
     */
    public function ottieniRelazioni(Model $model): array
    {
        $relazioni = [];
        $methods = get_class_methods($model);
        
        foreach ($methods as $method) {
            try {
                $reflection = new \ReflectionMethod($model, $method);
                if ($reflection->isPublic() && !$reflection->isStatic()) {
                    $returnType = $reflection->getReturnType();
                    if ($returnType && str_contains($returnType->getName(), 'Illuminate\Database\Eloquent\Relations')) {
                        $relazioni[] = $method;
                    }
                }
            } catch (\Exception $e) {
                // Ignora errori di reflection
            }
        }
        
        return $relazioni;
    }

    /**
     * Cache query results per performance
     */
    public function conCache(string $chiaveCache, \Closure $callback, int $ttl = 3600)
    {
        return cache()->remember($chiaveCache, $ttl, $callback);
    }

    /**
     * Invalida cache per una tabella
     */
    public function invalidaCache(string $pattern): void
    {
        $keys = cache()->getStore()->getRedis()->keys($pattern);
        if (!empty($keys)) {
            cache()->getStore()->getRedis()->del($keys);
        }
    }
}