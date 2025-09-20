<?php

namespace App\Services\GestioneTabelle\Strategie;

use App\Models\VatNatureAssociation;
use App\Models\TaxRate;
use App\Models\VatNature;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

/**
 * Strategia per Associazioni Nature IVA
 * Replica esatta della funzionalità originale del sistema
 */
class StrategiaAssociazioniNatureIva implements StrategiaTabella
{
    /**
     * {@inheritDoc}
     */
    public function ottieniTitolo(): string
    {
        return 'Associazioni Nature IVA';
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniIcona(): string
    {
        return 'bi-link-45deg';
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniColore(): string
    {
        return 'primary';
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniDescrizione(): string
    {
        return 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali';
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniDati(Request $request): LengthAwarePaginator
    {
        // Versione semplificata per debug - evita possibili loop
        $query = VatNatureAssociation::query()
            ->where('active', true)
            ->orderBy('nome_associazione');

        return $query->paginate(10);
    }

    /**
     * {@inheritDoc}
     */
    public function crea(array $dati): Model
    {
        // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
        if (!empty($dati['is_default'])) {
            VatNatureAssociation::where('tax_rate_id', $dati['tax_rate_id'])
                                 ->update(['is_default' => false]);
        }

        return VatNatureAssociation::create([
            'nome_associazione' => $dati['nome_associazione'],
            'descrizione' => $dati['descrizione'] ?? null,
            'tax_rate_id' => $dati['tax_rate_id'],
            'vat_nature_id' => $dati['vat_nature_id'],
            'is_default' => !empty($dati['is_default']),
            'active' => true
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function aggiorna(int $id, array $dati): Model
    {
        $associazione = VatNatureAssociation::findOrFail($id);

        // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
        if (!empty($dati['is_default']) && $dati['tax_rate_id']) {
            VatNatureAssociation::where('tax_rate_id', $dati['tax_rate_id'])
                                 ->where('id', '!=', $id)
                                 ->update(['is_default' => false]);
        }

        $associazione->update([
            'nome_associazione' => $dati['nome_associazione'],
            'descrizione' => $dati['descrizione'] ?? null,
            'tax_rate_id' => $dati['tax_rate_id'],
            'vat_nature_id' => $dati['vat_nature_id'],
            'is_default' => !empty($dati['is_default'])
        ]);

        return $associazione->fresh(['taxRate', 'vatNature']);
    }

    /**
     * {@inheritDoc}
     */
    public function elimina(int $id): bool
    {
        $associazione = VatNatureAssociation::findOrFail($id);
        return $associazione->update(['active' => false]);
    }

    /**
     * {@inheritDoc}
     */
    public function trova(int $id): ?Model
    {
        return VatNatureAssociation::with(['taxRate', 'vatNature'])
                                   ->where('active', true)
                                   ->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniTuttiDati(): Collection
    {
        return VatNatureAssociation::with(['taxRate', 'vatNature'])
                                   ->where('active', true)
                                   ->orderBy('nome_associazione')
                                   ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function calcolaStatistiche(): array
    {
        $associations = VatNatureAssociation::where('active', true);
        $taxRates = TaxRate::where('active', true);
        $vatNatures = VatNature::where('active', true);

        return [
            'totale_associazioni' => $associations->count(),
            'aliquote_iva' => $taxRates->count(),
            'nature_iva_attive' => $vatNatures->count(),
            'predefinite' => $associations->where('is_default', true)->count(),
            'statistiche_dettagliate' => [
                'associate_per_aliquota' => $this->calcolaAssociazioniPerAliquota(),
                'associate_per_natura' => $this->calcolaAssociazioniPerNatura(),
                'ultime_create' => $associations->latest()->limit(5)->get(['nome_associazione', 'created_at'])
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function esporta(Collection $dati, string $formato = 'excel'): string
    {
        // Implementazione basic export - da estendere con libreria specifica
        $nomeFile = 'associazioni_nature_iva_' . date('Y-m-d_H-i-s') . '.csv';
        $percorso = storage_path('app/exports/' . $nomeFile);

        // Crea directory se non esiste
        if (!file_exists(dirname($percorso))) {
            mkdir(dirname($percorso), 0755, true);
        }

        $file = fopen($percorso, 'w');
        
        // Header CSV
        fputcsv($file, [
            'ID',
            'Nome Associazione',
            'Descrizione',
            'Codice Aliquota',
            'Percentuale Aliquota',
            'Codice Natura IVA',
            'Nome Natura IVA',
            'Predefinita',
            'Data Creazione'
        ]);

        // Dati
        foreach ($dati as $associazione) {
            fputcsv($file, [
                $associazione->id,
                $associazione->nome_associazione,
                $associazione->descrizione,
                $associazione->taxRate->code ?? '',
                $associazione->taxRate->rate ?? '',
                $associazione->vatNature->code ?? '',
                $associazione->vatNature->name ?? '',
                $associazione->is_default ? 'Sì' : 'No',
                $associazione->created_at ? $associazione->created_at->format('d/m/Y H:i') : ''
            ]);
        }

        fclose($file);
        return $percorso;
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniConfigurazione(): array
    {
        return [
            'nome' => $this->ottieniTitolo(),
            'icona' => $this->ottieniIcona(),
            'colore' => $this->ottieniColore(),
            'descrizione' => $this->ottieniDescrizione(),
            'campi' => [
                'nome_associazione' => [
                    'tipo' => 'text',
                    'etichetta' => 'Nome Associazione',
                    'obbligatorio' => true,
                    'lunghezza_max' => 255,
                    'lunghezza_min' => 3
                ],
                'descrizione' => [
                    'tipo' => 'text',
                    'etichetta' => 'Descrizione',
                    'obbligatorio' => false,
                    'lunghezza_max' => 500
                ],
                'tax_rate_id' => [
                    'tipo' => 'select',
                    'etichetta' => 'Aliquota IVA',
                    'obbligatorio' => true,
                    'relazione' => 'tax_rates'
                ],
                'vat_nature_id' => [
                    'tipo' => 'select',
                    'etichetta' => 'Natura IVA',
                    'obbligatorio' => true,
                    'relazione' => 'vat_natures'
                ],
                'is_default' => [
                    'tipo' => 'boolean',
                    'etichetta' => 'Predefinita',
                    'obbligatorio' => false
                ]
            ],
            'regole_validazione' => $this->ottieniRegoleValidazione(),
            'opzioni_extra' => [
                'has_special_configurator' => true,
                'supporta_ricerca' => true,
                'supporta_filtri' => true,
                'supporta_ordinamento' => true,
                'colonne_visibili' => [
                    'nome_associazione',
                    'tax_rate.code',
                    'vat_nature.code',
                    'is_default',
                    'created_at'
                ]
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniRegoleValidazione(int $id = null): array
    {
        return [
            'nome_associazione' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s\-\._%]+$/u',
                'min:3'
            ],
            'descrizione' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{L}\p{N}\s\-\.,;:!?()\[\]{}"\'\/\\@#&*+=_]+$/u'
            ],
            'tax_rate_id' => [
                'required',
                'integer',
                'exists:tax_rates,id'
            ],
            'vat_nature_id' => [
                'required',
                'integer',
                'exists:vat_natures,id'
            ],
            'is_default' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    /**
     * Calcola associazioni per aliquota IVA
     */
    private function calcolaAssociazioniPerAliquota(): array
    {
        return VatNatureAssociation::with('taxRate')
            ->where('active', true)
            ->get()
            ->groupBy('tax_rate_id')
            ->map(function($group) {
                $first = $group->first();
                return [
                    'codice_aliquota' => $first->taxRate->code ?? 'N/A',
                    'percentuale' => $first->taxRate->rate ?? 0,
                    'numero_associazioni' => $group->count()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Calcola associazioni per natura IVA
     */
    private function calcolaAssociazioniPerNatura(): array
    {
        return VatNatureAssociation::with('vatNature')
            ->where('active', true)
            ->get()
            ->groupBy('vat_nature_id')
            ->map(function($group) {
                $first = $group->first();
                return [
                    'codice_natura' => $first->vatNature->code ?? 'N/A',
                    'nome_natura' => $first->vatNature->name ?? 'N/A',
                    'numero_associazioni' => $group->count()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniMessaggiValidazione(): array
    {
        return [
            'nome_associazione.required' => 'Il nome dell\'associazione è obbligatorio.',
            'nome_associazione.min' => 'Il nome deve essere di almeno 3 caratteri.',
            'nome_associazione.max' => 'Il nome non può superare i 255 caratteri.',
            'nome_associazione.regex' => 'Il nome contiene caratteri non validi.',
            'descrizione.max' => 'La descrizione non può superare i 500 caratteri.',
            'tax_rate_id.required' => 'Devi selezionare un\'aliquota IVA.',
            'tax_rate_id.exists' => 'L\'aliquota IVA selezionata non è valida.',
            'vat_nature_id.required' => 'Devi selezionare una natura IVA.',
            'vat_nature_id.exists' => 'La natura IVA selezionata non è valida.'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function verificaDuplicati(array $dati, ?int $excludeId = null): ?string
    {
        $query = VatNatureAssociation::where('tax_rate_id', $dati['tax_rate_id'])
                                     ->where('vat_nature_id', $dati['vat_nature_id'])
                                     ->where('active', true);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            return 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.';
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function applicaFiltri($query, Request $request)
    {
        // Filtro ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome_associazione', 'like', "%{$search}%")
                  ->orWhere('descrizione', 'like', "%{$search}%")
                  ->orWhereHas('taxRate', function($tq) use ($search) {
                      $tq->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro aliquota IVA
        if ($request->filled('tax_rate_id')) {
            $query->where('tax_rate_id', $request->get('tax_rate_id'));
        }

        // Filtro predefinita
        if ($request->filled('is_default')) {
            $query->where('is_default', $request->get('is_default'));
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniOrdinamentoPredefinito(): array
    {
        return ['nome_associazione' => 'asc'];
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniCampiVisibili(): array
    {
        return [
            'nome_associazione',
            'tax_rate.code',
            'vat_nature.code', 
            'is_default',
            'created_at'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniCampiRicercabili(): array
    {
        return [
            'nome_associazione',
            'descrizione',
            'taxRate.code',
            'taxRate.name',
            'vatNature.code',
            'vatNature.name'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function ottieniCampiEsportabili(): array
    {
        return [
            'id',
            'nome_associazione',
            'descrizione',
            'tax_rate.code',
            'tax_rate.rate',
            'vat_nature.code',
            'vat_nature.name',
            'is_default',
            'created_at'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function azioniDisponibili(): array
    {
        return [
            'view' => ['icona' => 'bi-eye', 'colore' => 'info'],
            'edit' => ['icona' => 'bi-pencil', 'colore' => 'warning'],
            'delete' => ['icona' => 'bi-trash', 'colore' => 'danger'],
            'duplicate' => ['icona' => 'bi-files', 'colore' => 'secondary']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function primaCreazione(array &$dati): void
    {
        // Verifico duplicati
        $errore = $this->verificaDuplicati($dati);
        if ($errore) {
            throw new \InvalidArgumentException($errore);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dopoCreazione(Model $elemento): void
    {
        // Log dell'operazione se necessario
        // \Log::info("Creata associazione Nature IVA", ['id' => $elemento->id]);
    }

    /**
     * {@inheritDoc}
     */
    public function primaAggiornamento(Model $elemento, array &$dati): void
    {
        // Verifico duplicati escludendo l'elemento corrente
        $errore = $this->verificaDuplicati($dati, $elemento->id);
        if ($errore) {
            throw new \InvalidArgumentException($errore);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dopoAggiornamento(Model $elemento): void
    {
        // Log dell'operazione se necessario
        // \Log::info("Aggiornata associazione Nature IVA", ['id' => $elemento->id]);
    }

    /**
     * {@inheritDoc}
     */
    public function primaEliminazione(Model $elemento): void
    {
        // Verifiche prima dell'eliminazione se necessario
        // Ad esempio verificare se l'associazione è in uso
    }

    /**
     * {@inheritDoc}
     */
    public function dopoEliminazione(int $id): void
    {
        // Log dell'operazione se necessario
        // \Log::info("Eliminata associazione Nature IVA", ['id' => $id]);
    }
}