<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Exports\AnagraficheExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnagraficaController extends Controller
{
    /**
     * Dashboard principale anagrafiche con selezione tipo
     */
    public function index()
    {
        $stats = [
            'clienti' => Anagrafica::clienti()->attivi()->count(),
            'fornitori' => Anagrafica::fornitori()->attivi()->count(),
            'vettori' => Anagrafica::vettori()->attivi()->count(),
            'agenti' => Anagrafica::agenti()->attivi()->count(),
            'articoli' => Anagrafica::articoli()->attivi()->count(),
            'servizi' => Anagrafica::servizi()->attivi()->count(),
        ];

        return view('anagrafiche.index', compact('stats'));
    }

    /**
     * Elenco anagrafiche per tipo specifico
     */
    public function lista($tipo, Request $request)
    {
        $this->validateTipo($tipo);

        $query = Anagrafica::where('tipo', $tipo)->attivi();

        // Ricerca multi-campo
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('cognome', 'LIKE', "%{$search}%")
                  ->orWhere('codice_interno', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('codice_fiscale', 'LIKE', "%{$search}%")
                  ->orWhere('partita_iva', 'LIKE', "%{$search}%");
            });
        }

        // Filtri specifici per tipo
        $this->applicaFiltriPerTipo($query, $tipo, $request);

        // Ordinamento
        $sortBy = $request->get('sort', 'nome');
        $sortDir = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $sortDir);

        // Paginazione configurabile
        $perPage = $request->get('per_page', 20);
        $anagrafiche = $query->paginate($perPage)->withQueryString();

        return view('anagrafiche.lista', [
            'tipo' => $tipo,
            'anagrafiche' => $anagrafiche,
            'filtri' => $this->getFiltriPerTipo($tipo),
            'colonne' => $this->getColonnePerTipo($tipo),
            'tabs' => $this->getTabsPerTipo($tipo),
        ]);
    }

    /**
     * Form creazione nuova anagrafica
     */
    public function create($tipo)
    {
        $this->validateTipo($tipo);

        $anagrafica = new Anagrafica(['tipo' => $tipo]);
        $tabs = $this->getTabsPerTipo($tipo);

        return view('anagrafiche.create', [
            'tipo' => $tipo,
            'anagrafica' => $anagrafica,
            'config' => $this->getConfigPerTipo($tipo),
            'tabs' => $tabs,
            'province' => $this->getProvince(),
            'nazioni' => $this->getNazioni(),
        ]);
    }

    /**
     * Salvataggio nuova anagrafica
     */
    public function store(Request $request)
    {
        $tipo = $request->input('tipo');
        $this->validateTipo($tipo);

        $rules = $this->getValidationRules($tipo);
        $validated = $request->validate($rules);

        $validated['tipo'] = $tipo;
        
        // Generazione automatica codice interno se non fornito
        if (empty($validated['codice_interno'])) {
            $validated['codice_interno'] = Anagrafica::generaCodiceInterno($tipo);
        }

        $anagrafica = Anagrafica::create($validated);

        $tipoLabel = $this->getTipoLabel($tipo);

        return redirect()
            ->route('anagrafiche.lista', $tipo)
            ->with('success', "{$tipoLabel} creato con successo! Codice: {$anagrafica->codice_interno}");
    }

    /**
     * Visualizzazione dettaglio anagrafica con tab
     */
    public function show($tipo, $id, Request $request)
    {
        $this->validateTipo($tipo);

        $anagrafica = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        $activeTab = $request->get('tab', 'gestione');
        $tabs = $this->getTabsPerTipo($tipo);

        // Verifica che la tab richiesta esista per questo tipo
        if (!isset($tabs[$activeTab])) {
            $activeTab = array_key_first($tabs);
        }

        return view('anagrafiche.show', [
            'tipo' => $tipo,
            'anagrafica' => $anagrafica,
            'config' => $this->getConfigPerTipo($tipo),
            'activeTab' => $activeTab,
            'tabs' => $tabs,
            'datiTab' => $this->getDatiPerTab($anagrafica, $activeTab),
        ]);
    }

    /**
     * Form modifica anagrafica
     */
    public function edit($tipo, $id, Request $request)
    {
        $this->validateTipo($tipo);

        $anagrafica = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        $activeTab = $request->get('tab', 'dettaglio');
        $tabs = $this->getTabsPerTipo($tipo);

        return view('anagrafiche.edit', [
            'tipo' => $tipo,
            'anagrafica' => $anagrafica,
            'config' => $this->getConfigPerTipo($tipo),
            'activeTab' => $activeTab,
            'tabs' => $tabs,
            'province' => $this->getProvince(),
            'nazioni' => $this->getNazioni(),
        ]);
    }

    /**
     * Aggiornamento anagrafica
     */
    public function update($tipo, $id, Request $request)
    {
        $this->validateTipo($tipo);

        $anagrafica = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        
        $rules = $this->getValidationRules($tipo, $anagrafica->id);
        $validated = $request->validate($rules);

        $anagrafica->update($validated);

        $tipoLabel = $this->getTipoLabel($tipo);

        return redirect()
            ->route('anagrafiche.lista', ['tipo' => $tipo])
            ->with('success', "{$tipoLabel} aggiornato con successo!");
    }

    /**
     * Eliminazione anagrafica (eliminazione fisica)
     */
    public function destroy($tipo, $id)
    {
        $this->validateTipo($tipo);

        $anagrafica = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        
        // Verifica se l'anagrafica è utilizzata in altri moduli
        if ($this->isAnagraficaInUse($anagrafica)) {
            $tipoLabel = $this->getTipoLabel($tipo);
            return redirect()
                ->route('anagrafiche.lista', ['tipo' => $tipo])
                ->with('error', "Impossibile eliminare {$tipoLabel}: è utilizzato in altri documenti. Disattivalo invece di eliminarlo.");
        }
        
        $anagrafica->delete();

        $tipoLabel = $this->getTipoLabel($tipo);

        return redirect()
            ->route('anagrafiche.lista', ['tipo' => $tipo])
            ->with('success', "{$tipoLabel} eliminato definitivamente!");
    }

    /**
     * Duplicazione anagrafica
     */
    public function duplicate($tipo, $id)
    {
        $this->validateTipo($tipo);

        $original = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        
        $duplicated = $original->replicate();
        $duplicated->codice_interno = Anagrafica::generaCodiceInterno($tipo);
        $duplicated->nome = $original->nome . ' (Copia)';
        $duplicated->email = null; // Reset email per evitare duplicati
        $duplicated->save();

        $tipoLabel = $this->getTipoLabel($tipo);

        return redirect()
            ->route('anagrafiche.lista', ['tipo' => $tipo])
            ->with('success', "{$tipoLabel} duplicato con successo! Codice: {$duplicated->codice_interno}");
    }

    /**
     * Export generico (dispatcher per formato)
     */
    public function export($tipo, $format, Request $request)
    {
        $this->validateTipo($tipo);

        if ($format === 'pdf') {
            $id = $request->get('id');
            if (!$id) {
                return redirect()
                    ->route('anagrafiche.lista', ['tipo' => $tipo])
                    ->with('error', 'ID anagrafica mancante per export PDF');
            }
            return $this->exportPdf($tipo, $id);
        }

        return redirect()
            ->route('anagrafiche.lista', ['tipo' => $tipo])
            ->with('error', 'Formato export non supportato');
    }

    /**
     * Export PDF anagrafica singola
     */
    public function exportPdf($tipo, $id)
    {
        $this->validateTipo($tipo);

        $anagrafica = Anagrafica::where('tipo', $tipo)->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('anagrafiche.pdf', compact('anagrafica'));
        
        $filename = "{$tipo}_{$anagrafica->codice_interno}_" . now()->format('Y-m-d') . ".pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Export Excel/CSV per tipo
     */
    public function exportExcel($tipo, Request $request)
    {
        $this->validateTipo($tipo);
        
        $formato = $request->get('formato', 'excel');
        
        // Ottieni eventuali filtri di ricerca
        $filtri = [];
        if ($search = $request->get('search')) {
            $filtri['search'] = $search;
        }
        
        // Crea il file di export
        $export = new AnagraficheExport($tipo, $filtri);
        $tipoCapitalized = ucfirst($tipo);
        $filename = "{$tipoCapitalized}_" . now()->format('Y-m-d_H-i-s');
        
        if ($formato === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        
        // Default: Excel
        return \Maatwebsite\Excel\Facades\Excel::download($export, $filename . '.xlsx');
    }

    /**
     * API AJAX per ricerca dinamica
     */
    public function search($tipo, Request $request)
    {
        $this->validateTipo($tipo);

        $query = $request->get('q', '');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $results = Anagrafica::where('tipo', $tipo)
            ->attivi()
            ->where(function($q) use ($query) {
                $q->where('nome', 'LIKE', "%{$query}%")
                  ->orWhere('cognome', 'LIKE', "%{$query}%")
                  ->orWhere('codice_interno', 'LIKE', "%{$query}%");
            })
            ->select('id', 'codice_interno', 'nome', 'cognome', 'email')
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->codice_interno} - {$item->nome_completo}",
                    'email' => $item->email,
                ];
            });

        return response()->json($results);
    }

    /**
     * API per caricamento comuni da provincia
     */
    public function comuni($provincia)
    {
        // TODO: Implementare caricamento comuni
        return response()->json([]);
    }

    // METODI PRIVATI DI SUPPORTO

    private function validateTipo($tipo)
    {
        $tipiConsentiti = ['cliente', 'fornitore', 'vettore', 'agente', 'articolo', 'servizio'];
        
        if (!in_array($tipo, $tipiConsentiti)) {
            abort(404, "Tipo anagrafica '{$tipo}' non valido");
        }
    }

    private function getTipoLabel($tipo)
    {
        return match($tipo) {
            'cliente' => 'Cliente',
            'fornitore' => 'Fornitore',
            'vettore' => 'Vettore',
            'agente' => 'Agente',
            'articolo' => 'Articolo',
            'servizio' => 'Servizio',
            default => ucfirst($tipo),
        };
    }

    private function getTabsPerTipo($tipo)
    {
        return match($tipo) {
            'cliente' => [
                'gestione' => 'Gestione Cliente',
                'dettaglio' => 'Dettaglio Cliente',
                'varie' => 'Varie',
                'iva' => 'IVA',
                'banche' => 'Banche',
            ],
            'fornitore' => [
                'gestione' => 'Gestione',
                'dettaglio' => 'Dettaglio',
                'commerciale' => 'Commerciale',
                'fiscale' => 'Fiscale',
                'ordini' => 'Ordini',
            ],
            'vettore' => [
                'gestione' => 'Gestione',
                'dettaglio' => 'Dettaglio',
                'zone' => 'Zone',
                'tariffe' => 'Tariffe',
                'consegne' => 'Consegne',
            ],
            'agente' => [
                'gestione' => 'Gestione',
                'dettaglio' => 'Dettaglio',
                'provvigioni' => 'Provvigioni',
                'zone' => 'Zone',
                'vendite' => 'Vendite',
            ],
            'articolo' => [
                'gestione' => 'Gestione',
                'dettaglio' => 'Dettaglio',
                'prezzi' => 'Prezzi',
                'scorte' => 'Scorte',
                'fornitori' => 'Fornitori',
            ],
            'servizio' => [
                'gestione' => 'Gestione',
                'dettaglio' => 'Dettaglio',
                'tariffe' => 'Tariffe',
                'pianificazione' => 'Pianificazione',
                'risorse' => 'Risorse',
            ],
        };
    }

    private function getColonnePerTipo($tipo)
    {
        $base = ['codice_interno', 'nome', 'email', 'telefono_1'];

        return match($tipo) {
            'cliente', 'fornitore', 'vettore', 'agente' => array_merge($base, ['comune', 'provincia']),
            'articolo' => ['codice_articolo', 'nome', 'categoria_articolo', 'prezzo_vendita', 'scorta_minima'],
            'servizio' => ['codice_servizio', 'nome', 'categoria_servizio', 'tariffa_oraria'],
            default => $base,
        };
    }

    private function getFiltriPerTipo($tipo)
    {
        return match($tipo) {
            'cliente' => ['tipo_soggetto', 'provincia', 'is_pubblica_amministrazione'],
            'fornitore' => ['categoria_merceologica', 'provincia'],
            'vettore' => ['tipo_trasporto', 'assicurazione_disponibile'],
            'agente' => ['tipo_contratto', 'zone_competenza'],
            'articolo' => ['categoria_articolo', 'fornitore_principale_id'],
            'servizio' => ['categoria_servizio'],
            default => [],
        };
    }

    private function applicaFiltriPerTipo($query, $tipo, $request)
    {
        foreach ($this->getFiltriPerTipo($tipo) as $filtro) {
            if ($value = $request->get($filtro)) {
                $query->where($filtro, $value);
            }
        }
    }

    private function getDatiPerTab($anagrafica, $tab)
    {
        // Dati specifici per ogni tab
        return match($tab) {
            'gestione' => $this->getDatiGestione($anagrafica),
            'vendite', 'ordini', 'consegne' => $this->getDatiStorici($anagrafica, $tab),
            default => [],
        };
    }

    private function getDatiGestione($anagrafica)
    {
        return [
            'ultimo_aggiornamento' => $anagrafica->updated_at,
            'creato_il' => $anagrafica->created_at,
        ];
    }

    private function getDatiStorici($anagrafica, $tipo)
    {
        // TODO: Implementare recupero dati storici
        return [];
    }

    private function getValidationRules($tipo, $excludeId = null)
    {
        $baseRules = [
            'nome' => 'required|string|max:255',
            'cognome' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('anagrafiche')->ignore($excludeId)],
            'telefono_1' => 'nullable|string|max:20',
            'indirizzo' => 'nullable|string|max:500',
            'cap' => 'nullable|string|size:5',
            'comune' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|size:2',
        ];

        return match($tipo) {
            'cliente' => array_merge($baseRules, [
                'codice_fiscale' => 'nullable|string|size:16',
                'partita_iva' => 'nullable|string|size:11',
                'tipo_soggetto' => 'nullable|in:definitivo,potenziale',
            ]),
            'fornitore' => array_merge($baseRules, [
                'codice_fornitore' => ['nullable', 'string', Rule::unique('anagrafiche')->ignore($excludeId)],
                'categoria_merceologica' => 'nullable|string|max:100',
                'lead_time_giorni' => 'nullable|integer|min:0|max:365',
            ]),
            'vettore' => array_merge($baseRules, [
                'codice_vettore' => ['nullable', 'string', Rule::unique('anagrafiche')->ignore($excludeId)],
                'tipo_trasporto' => 'nullable|in:proprio,terzi',
                'tempi_standard_ore' => 'nullable|integer|min:0|max:720',
            ]),
            'agente' => array_merge($baseRules, [
                'codice_agente' => ['nullable', 'string', Rule::unique('anagrafiche')->ignore($excludeId)],
                'percentuale_provvigione' => 'nullable|numeric|min:0|max:100',
                'tipo_contratto' => 'nullable|in:dipendente,collaboratore,esterno',
            ]),
            'articolo' => [
                'nome' => 'required|string|max:255',
                'codice_articolo' => ['nullable', 'string', Rule::unique('anagrafiche')->ignore($excludeId)],
                'categoria_articolo' => 'nullable|string|max:100',
                'prezzo_vendita' => 'nullable|numeric|min:0',
                'prezzo_acquisto' => 'nullable|numeric|min:0',
                'scorta_minima' => 'nullable|integer|min:0',
            ],
            'servizio' => [
                'nome' => 'required|string|max:255',
                'codice_servizio' => ['nullable', 'string', Rule::unique('anagrafiche')->ignore($excludeId)],
                'categoria_servizio' => 'nullable|string|max:100',
                'tariffa_oraria' => 'nullable|numeric|min:0',
                'durata_standard_minuti' => 'nullable|integer|min:0',
            ],
            default => $baseRules,
        };
    }

    private function getProvince()
    {
        // TODO: Implementare caricamento province
        return collect([]);
    }

    private function getNazioni()
    {
        // TODO: Implementare caricamento nazioni
        return collect([]);
    }

    /**
     * Verifica se l'anagrafica è utilizzata in altri moduli
     */
    private function isAnagraficaInUse($anagrafica)
    {
        // Controlla se è utilizzata come fornitore principale
        if ($anagrafica->tipo === 'fornitore') {
            $usedAsSupplier = Anagrafica::where('fornitore_principale_id', $anagrafica->id)->exists();
            if ($usedAsSupplier) return true;
        }
        
        // TODO: Aggiungere controlli per vendite, DDT, fatture quando disponibili
        // if ($anagrafica->tipo === 'cliente' && $anagrafica->vendite()->exists()) return true;
        // if ($anagrafica->tipo === 'vettore' && $anagrafica->ddts()->exists()) return true;
        
        return false;
    }

    private function getConfigPerTipo($tipo)
    {
        return match($tipo) {
            'cliente' => [
                'icon' => 'bi bi-people',
                'descrizione' => 'Gestione completa anagrafica clienti con dati fiscali, commerciali e coordinate bancarie',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-person-fill'],
                    'indirizzo' => ['titolo' => 'Indirizzo', 'icon' => 'bi bi-geo-alt-fill'],
                    'contatti' => ['titolo' => 'Contatti', 'icon' => 'bi bi-telephone-fill'],
                    'fiscali' => ['titolo' => 'Fiscali', 'icon' => 'bi bi-receipt'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                    'bancari' => ['titolo' => 'Bancari', 'icon' => 'bi bi-bank'],
                ]
            ],
            'fornitore' => [
                'icon' => 'bi bi-building',
                'descrizione' => 'Anagrafica fornitori con condizioni commerciali, tempi di consegna e categorie merceologiche',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-person-fill'],
                    'indirizzo' => ['titolo' => 'Indirizzo', 'icon' => 'bi bi-geo-alt-fill'],
                    'contatti' => ['titolo' => 'Contatti', 'icon' => 'bi bi-telephone-fill'],
                    'fiscali' => ['titolo' => 'Fiscali', 'icon' => 'bi bi-receipt'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                    'bancari' => ['titolo' => 'Bancari', 'icon' => 'bi bi-bank'],
                ]
            ],
            'vettore' => [
                'icon' => 'bi bi-truck',
                'descrizione' => 'Gestione corrieri e trasportatori con zone di consegna, tariffe e tempi standard',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-person-fill'],
                    'indirizzo' => ['titolo' => 'Indirizzo', 'icon' => 'bi bi-geo-alt-fill'],
                    'contatti' => ['titolo' => 'Contatti', 'icon' => 'bi bi-telephone-fill'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                ]
            ],
            'agente' => [
                'icon' => 'bi bi-person-badge',
                'descrizione' => 'Anagrafica agenti commerciali con provvigioni, zone di competenza e obiettivi di vendita',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-person-fill'],
                    'indirizzo' => ['titolo' => 'Indirizzo', 'icon' => 'bi bi-geo-alt-fill'],
                    'contatti' => ['titolo' => 'Contatti', 'icon' => 'bi bi-telephone-fill'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                ]
            ],
            'articolo' => [
                'icon' => 'bi bi-box-seam',
                'descrizione' => 'Gestione articoli con prezzi, scorte, fornitori principali e categorie merceologiche',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-box'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                    'specifici' => ['titolo' => 'Specifici Articolo', 'icon' => 'bi bi-gear'],
                ]
            ],
            'servizio' => [
                'icon' => 'bi bi-tools',
                'descrizione' => 'Anagrafica servizi con tariffe orarie, durata standard e competenze richieste',
                'tabs' => [
                    'dati-base' => ['titolo' => 'Dati Base', 'icon' => 'bi bi-tools'],
                    'commerciali' => ['titolo' => 'Commerciali', 'icon' => 'bi bi-cash-coin'],
                    'specifici' => ['titolo' => 'Specifici Servizio', 'icon' => 'bi bi-gear'],
                ]
            ]
        };
    }
}
