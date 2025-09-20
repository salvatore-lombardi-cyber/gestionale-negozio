<?php

namespace App\Http\Controllers\Configurazioni;

use App\Http\Controllers\Controller;
// use App\Services\GestioneTabelle\GestioneTabelleService; // Temporaneamente disabilitato
use App\Models\VatNatureAssociation;
use App\Models\TaxRate;
use App\Models\VatNature;
use App\Models\SystemTable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Controller enterprise per gestione tabelle di configurazione
 * Architettura pulita con separazione delle responsabilità
 */
class GestioneTabelleController extends Controller
{
    public function __construct()
    {
        // Versione semplificata senza dependency injection complessa per evitare loop
    }

    /**
     * Dashboard principale gestione tabelle
     */
    public function index(): View
    {
        try {
            // Lista tabelle con colori specifici del vecchio modulo
            $tabelleDisponibili = collect([
                [
                    'nome' => 'associazioni-nature-iva',
                    'titolo' => 'Associazioni Nature IVA',
                    'icona' => 'bi-link-45deg',
                    'colore' => 'primary',
                    'descrizione' => 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali',
                    'color_from' => '#4ecdc4',
                    'color_to' => '#44a08d'
                ],
                [
                    'nome' => 'aliquote-iva',
                    'titolo' => 'Aliquote IVA',
                    'icona' => 'bi-percent',
                    'colore' => 'danger',
                    'descrizione' => 'Gestione completa delle aliquote IVA del sistema',
                    'color_from' => '#dc3545',
                    'color_to' => '#c82333'
                ],
                [
                    'nome' => 'banche',
                    'titolo' => 'Banche',
                    'icona' => 'bi-bank',
                    'colore' => 'info',
                    'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
                    'color_from' => '#48cae4',
                    'color_to' => '#023e8a'
                ],
                [
                    'nome' => 'categorie-articoli',
                    'titolo' => 'Categorie Articoli',
                    'icona' => 'bi-grid-3x3-gap',
                    'colore' => 'success',
                    'descrizione' => 'Sistema gerarchico per classificazione e organizzazione prodotti',
                    'color_from' => '#38b000',
                    'color_to' => '#70e000'
                ],
                [
                    'nome' => 'categorie-clienti',
                    'titolo' => 'Categorie Clienti',
                    'icona' => 'bi-people',
                    'colore' => 'warning',
                    'descrizione' => 'Segmentazione e classificazione clienti per gestione commerciale',
                    'color_from' => '#f093fb',
                    'color_to' => '#f5576c'
                ],
                [
                    'nome' => 'categorie-fornitori',
                    'titolo' => 'Categorie Fornitori',
                    'icona' => 'bi-person-badge',
                    'colore' => 'purple',
                    'descrizione' => 'Classificazione e gestione categorie fornitori per procurement',
                    'color_from' => '#9c27b0',
                    'color_to' => '#7b1fa2'
                ]
            ]);
            
            return view('configurazioni.gestione-tabelle.index', [
                'tabelle' => $tabelleDisponibili,
                'title' => 'Gestione Tabelle di Sistema',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Configurazioni', 'url' => route('configurations.index')],
                    ['title' => 'Gestione Tabelle', 'active' => true]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Errore dashboard gestione tabelle', [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id() // Commentato per test senza auth
            ]);
            
            throw $e;
        }
    }

    /**
     * Visualizza elenco elementi di una tabella specifica
     */
    public function mostraTabella(string $nomeTabella, Request $request): View|RedirectResponse
    {
        try {
            // Supporto per le tabelle implementate
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // Gestione unificata per TUTTE le tabelle v2
            return $this->gestisciTabellaV2($nomeTabella, $request);
            
        } catch (\Exception $e) {
            Log::error("Errore visualizzazione tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id(), // Commentato per test senza auth
                'request' => $request->all()
            ]);
            
            return back()->with('error', 'Errore caricamento dati tabella');
        }
    }

    /**
     * Form creazione nuovo elemento
     */
    public function create(string $nomeTabella): View|RedirectResponse
    {
        try {
            // Supporto per le tabelle v2
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // Configurazioni per ogni tabella
            $configurazioni = [
                'associazioni-nature-iva' => [
                    'nome' => 'Associazioni Nature IVA',
                    'nome_singolare' => 'Associazione Nature IVA',
                    'icona' => 'bi-link-45deg',
                    'colore' => 'primary',
                    'descrizione' => 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali'
                ],
                'aliquote-iva' => [
                    'nome' => 'Aliquote IVA',
                    'nome_singolare' => 'Aliquota IVA',
                    'icona' => 'bi-percent',
                    'colore' => 'success',
                    'descrizione' => 'Gestione completa delle aliquote IVA del sistema'
                ],
                'banche' => [
                    'nome' => 'Banche',
                    'nome_singolare' => 'Banca',
                    'icona' => 'bi-bank',
                    'colore' => 'info',
                    'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti'
                ],
                'categorie-articoli' => [
                    'nome' => 'Categorie Articoli',
                    'nome_singolare' => 'Categoria Articoli',
                    'icona' => 'bi-grid-3x3-gap',
                    'colore' => 'success',
                    'descrizione' => 'Sistema gerarchico per classificazione e organizzazione prodotti'
                ],
                'categorie-clienti' => [
                    'nome' => 'Categorie Clienti',
                    'nome_singolare' => 'Categoria Clienti',
                    'icona' => 'bi-people',
                    'colore' => 'warning',
                    'descrizione' => 'Segmentazione e classificazione clienti per gestione commerciale'
                ],
                'categorie-fornitori' => [
                    'nome' => 'Categorie Fornitori',
                    'nome_singolare' => 'Categoria Fornitori',
                    'icona' => 'bi-person-badge',
                    'colore' => 'purple',
                    'descrizione' => 'Classificazione e gestione categorie fornitori per procurement'
                ]
            ];

            $configurazione = $configurazioni[$nomeTabella];
            
            return view('configurazioni.gestione-tabelle.create', [
                'nomeTabella' => $nomeTabella,
                'configurazione' => $configurazione,
                'title' => 'Nuova ' . $configurazione['nome_singolare'],
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Gestione Tabelle', 'url' => route('configurations.gestione-tabelle.index')],
                    ['title' => $configurazione['nome'], 'url' => route('configurations.gestione-tabelle.tabella', $nomeTabella)],
                    ['title' => 'Nuova ' . $configurazione['nome_singolare'], 'active' => true]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore form creazione {$nomeTabella}", [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Errore caricamento form');
        }
    }

    /**
     * Salva nuovo elemento
     */
    public function store(string $nomeTabella, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Supporto per le tabelle v2
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // Gestione specifica per ogni tabella
            if ($nomeTabella === 'aliquote-iva') {
                return $this->storeAliquotaIva($request);
            }

            if ($nomeTabella === 'banche') {
                return $this->storeBanca($request);
            }

            if ($nomeTabella === 'categorie-articoli') {
                return $this->storeCategoriaArticoli($request);
            }

            if ($nomeTabella === 'categorie-clienti') {
                return $this->storeCategoriaClienti($request);
            }

            if ($nomeTabella === 'categorie-fornitori') {
                return $this->storeCategoriaFornitori($request);
            }

            // Default: Associazioni Nature IVA

            // Validazione specifica per Associazioni Nature IVA
            $validated = $request->validate([
                'nome_associazione' => 'required|string|min:3|max:255',
                'descrizione' => 'nullable|string|max:500',
                'tax_rate_id' => 'required|integer|exists:tax_rates,id',
                'vat_nature_id' => 'required|integer|exists:vat_natures,id',
                'is_default' => 'nullable|boolean'
            ]);

            // Verifica duplicati
            $exists = VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                         ->where('vat_nature_id', $validated['vat_nature_id'])
                                         ->where('active', true)
                                         ->exists();

            if ($exists) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.',
                        'errors' => ['duplicate' => 'Associazione già esistente']
                    ], 422);
                }
                
                return back()->withErrors([
                    'duplicate' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.'
                ])->withInput();
            }

            // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
            if (!empty($validated['is_default'])) {
                VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                     ->update(['is_default' => false]);
            }

            try {
                $elemento = VatNatureAssociation::create([
                    'nome_associazione' => $validated['nome_associazione'],
                    'name' => $validated['nome_associazione'],
                    'descrizione' => $validated['descrizione'] ?? null,
                    'tax_rate_id' => $validated['tax_rate_id'],
                    'vat_nature_id' => $validated['vat_nature_id'],
                    'is_default' => !empty($validated['is_default']),
                    'active' => true
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Gestisci errore di constraint duplicato
                if ($e->errorInfo[1] == 1062) { // Duplicate entry error
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.',
                            'errors' => ['duplicate' => 'Associazione già esistente']
                        ], 422);
                    }
                    
                    return back()->withErrors([
                        'duplicate' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.'
                    ])->withInput();
                }
                
                // Re-throw altri errori
                throw $e;
            }
            
            $messaggio = 'Associazione creata con successo';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $messaggio,
                    'data' => $elemento,
                    'redirect' => route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ]);
            }
            
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ->with('success', $messaggio);
                
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error("Errore creazione elemento {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'dati' => $request->all(),
                // 'utente_id' => auth()->id() // Commentato per test senza auth
            ]);
            
            $messaggio = $e->getMessage() ?: 'Errore durante la creazione';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $messaggio
                ], 500);
            }
            
            return back()->with('error', $messaggio)->withInput();
        }
    }

    /**
     * Visualizza dettagli elemento
     */
    public function show(string $nomeTabella, int $id, Request $request): JsonResponse|RedirectResponse
    {
        try {
            if ($request->expectsJson()) {
                // Restituisci JSON per AJAX
                if ($nomeTabella === 'banche') {
                    $element = \App\Models\Bank::findOrFail($id);
                } elseif ($nomeTabella === 'aliquote-iva') {
                    $element = \App\Models\TaxRate::findOrFail($id);
                } elseif ($nomeTabella === 'associazioni-nature-iva') {
                    $element = VatNatureAssociation::with(['taxRate', 'vatNature'])->findOrFail($id);
                } elseif ($nomeTabella === 'categorie-articoli') {
                    $element = \App\Models\ProductCategory::with(['parent', 'children'])->findOrFail($id);
                } elseif ($nomeTabella === 'categorie-clienti') {
                    $element = \App\Models\CustomerCategory::findOrFail($id);
                } elseif ($nomeTabella === 'categorie-fornitori') {
                    $element = \App\Models\SupplierCategory::findOrFail($id);
                } else {
                    return response()->json(['error' => 'Tabella non supportata'], 404);
                }
                
                return response()->json($element);
            }
            
            // Per richieste normali, redirect alla tabella
            return redirect()->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ->with('info', 'Usa il pulsante occhio per visualizzare i dettagli');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Elemento non trovato'], 404);
            }
            
            return redirect()->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ->with('error', 'Elemento non trovato');
        }
    }

    /**
     * Form modifica elemento
     */
    public function edit(string $nomeTabella, int $id): View|RedirectResponse
    {
        // Redirect semplice alla tabella principale
        return redirect()->route('configurations.gestione-tabelle.tabella', $nomeTabella)
            ->with('info', 'Modifica tramite modale in sviluppo');
    }

    /**
     * Aggiorna elemento esistente
     */
    public function update(string $nomeTabella, int $id, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Supporto per le tabelle v2
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // Gestione specifica per ogni tabella
            if ($nomeTabella === 'aliquote-iva') {
                return $this->updateAliquotaIva($request, $id);
            }

            if ($nomeTabella === 'banche') {
                return $this->updateBanca($request, $id);
            }

            if ($nomeTabella === 'categorie-articoli') {
                return $this->updateCategoriaArticoli($request, $id);
            }

            if ($nomeTabella === 'categorie-clienti') {
                return $this->updateCategoriaClienti($request, $id);
            }

            if ($nomeTabella === 'categorie-fornitori') {
                return $this->updateCategoriaFornitori($request, $id);
            }

            // Default: Associazioni Nature IVA
            return $this->updateAssociazioneNatureIva($request, $id);
            
        } catch (\Exception $e) {
            Log::error("Errore aggiornamento elemento {$nomeTabella}:{$id}", [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'aggiornamento')->withInput();
        }
    }

    /**
     * Elimina elemento
     */
    public function destroy(string $nomeTabella, int $id, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Gestione per tabelle v2
            if ($nomeTabella === 'aliquote-iva') {
                return $this->destroyAliquotaIva($id);
            } elseif ($nomeTabella === 'banche') {
                return $this->destroyBanca($id);
            } elseif ($nomeTabella === 'categorie-articoli') {
                return $this->destroyCategoriaArticoli($id);
            } elseif ($nomeTabella === 'categorie-clienti') {
                return $this->destroyCategoriaClienti($id);
            } elseif ($nomeTabella === 'categorie-fornitori') {
                return $this->destroyCategoriaFornitori($id);
            } elseif ($nomeTabella === 'associazioni-nature-iva') {
                $elemento = VatNatureAssociation::findOrFail($id);
                $elemento->delete();
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Associazione eliminata con successo'
                    ]);
                }
                
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('success', 'Associazione eliminata con successo');
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Eliminazione non ancora implementata per {$nomeTabella}"
                ], 501);
            }
            
            return back()->with('error', "Eliminazione non ancora implementata per {$nomeTabella}");
            
        } catch (\Exception $e) {
            Log::error("Errore eliminazione elemento {$nomeTabella}:{$id}", [
                'errore' => $e->getMessage(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'eliminazione'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'eliminazione');
        }
    }

    /**
     * Esporta dati tabella
     */
    public function export(string $nomeTabella, Request $request)
    {
        try {
            $formato = $request->get('formato', 'excel');
            $percorsoFile = $this->gestioneService->esportaTabella($nomeTabella, $formato);
            
            $nomeFile = basename($percorsoFile);
            
            return response()->download($percorsoFile, $nomeFile)->deleteFileAfterSend();
            
        } catch (\Exception $e) {
            Log::error("Errore export tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'formato' => $request->get('formato'),
                'utente_id' => auth()->id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'esportazione'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'esportazione');
        }
    }

    /**
     * API: Ottieni statistiche tabella
     */
    public function statistiche(string $nomeTabella): JsonResponse
    {
        try {
            $statistiche = $this->gestioneService->ottieniStatistiche($nomeTabella);
            
            return response()->json([
                'success' => true,
                'data' => $statistiche
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore statistiche tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'utente_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore caricamento statistiche'
            ], 500);
        }
    }

    /**
     * API: Ricerca elementi
     */
    public function ricerca(string $nomeTabella, Request $request): JsonResponse
    {
        try {
            $dati = $this->gestioneService->ottieniDatiTabella($nomeTabella, $request);
            
            return response()->json([
                'success' => true,
                'data' => $dati->items(),
                'pagination' => [
                    'current_page' => $dati->currentPage(),
                    'last_page' => $dati->lastPage(),
                    'per_page' => $dati->perPage(),
                    'total' => $dati->total()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore ricerca tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'parametri' => $request->all(),
                'utente_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la ricerca'
            ], 500);
        }
    }

    /**
     * Gestione specifica per tabella Aliquote IVA
     */
    private function gestisciAliquoteIva(Request $request): View
    {
        // Carica tutte le aliquote IVA
        $taxRates = TaxRate::orderBy('percentuale')->get();

        return view('configurations.system-tables.aliquote-iva', [
            'taxRates' => $taxRates,
            'title' => 'Aliquote IVA',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Gestione Tabelle', 'url' => route('configurations.gestione-tabelle.index')],
                ['title' => 'Aliquote IVA', 'active' => true]
            ]
        ]);
    }

    /**
     * Store per Aliquote IVA
     */
    public function storeAliquotaIva(Request $request): JsonResponse|RedirectResponse
    {
        Log::info('=== STORE ALIQUOTA IVA CHIAMATO ===', [
            'metodo' => 'storeAliquotaIva',
            'dati_ricevuti' => $request->all(),
            'url' => $request->url(),
            'metodo_http' => $request->method()
        ]);
        
        try {
            // Validazione specifica per Aliquote IVA
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:tax_rates,code',
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:5|max:500',
                'percentuale' => 'required|numeric|min:0|max:100',
                'riferimento_normativo' => 'nullable|string|max:1000',
                'active' => 'nullable|boolean'
            ]);
            
            Log::info('Validazione PASSATA', ['validated' => $validated]);

            $elemento = TaxRate::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'],
                'percentuale' => $validated['percentuale'],
                'riferimento_normativo' => $validated['riferimento_normativo'] ?? null,
                'active' => !empty($validated['active'])
            ]);
            
            Log::info('Elemento CREATO con successo', ['elemento_id' => $elemento->id, 'elemento' => $elemento->toArray()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aliquota IVA creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'aliquote-iva')
                ->with('success', 'Aliquota IVA creata con successo');

        } catch (ValidationException $e) {
            Log::error('ERRORE di validazione', ['errori' => $e->errors(), 'dati' => $request->all()]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('ERRORE GENERALE creazione aliquota IVA', [
                'errore' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante la creazione dell\'aliquota IVA'])
                ->withInput();
        }
    }

    /**
     * Show per Aliquote IVA
     */
    public function showAliquotaIva(int $id): JsonResponse
    {
        try {
            $aliquota = TaxRate::findOrFail($id);
            
            return response()->json($aliquota);
            
        } catch (\Exception $e) {
            Log::error('Errore visualizzazione aliquota IVA', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Aliquota IVA non trovata'
            ], 404);
        }
    }

    /**
     * Update per Aliquote IVA
     */
    public function updateAliquotaIva(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $aliquota = TaxRate::findOrFail($id);
            
            // Validazione con esclusione dell'ID corrente per il codice
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:tax_rates,code,' . $id,
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:5|max:500',
                'percentuale' => 'required|numeric|min:0|max:100',
                'riferimento_normativo' => 'nullable|string|max:1000',
                'active' => 'nullable|boolean'
            ]);

            $aliquota->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'],
                'percentuale' => $validated['percentuale'],
                'riferimento_normativo' => $validated['riferimento_normativo'] ?? null,
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aliquota IVA aggiornata con successo',
                    'data' => $aliquota
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'aliquote-iva')
                ->with('success', 'Aliquota IVA aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento aliquota IVA', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante l\'aggiornamento dell\'aliquota IVA'])
                ->withInput();
        }
    }

    /**
     * Delete per Aliquote IVA
     */
    public function destroyAliquotaIva(int $id): JsonResponse
    {
        try {
            $aliquota = TaxRate::findOrFail($id);
            
            // Verifica se l'aliquota è utilizzata in associazioni
            $utilizzi = VatNatureAssociation::where('tax_rate_id', $id)->where('active', true)->count();
            
            if ($utilizzi > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossibile eliminare: l'aliquota è utilizzata in {$utilizzi} associazioni attive"
                ], 422);
            }

            $aliquota->delete();

            return response()->json([
                'success' => true,
                'message' => 'Aliquota IVA eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione aliquota IVA', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }

    /**
     * Gestisce la tabella Banche con dati e statistiche
     */
    private function gestisciBanche(Request $request): View
    {
        // Ottieni dati banche con paginazione e filtri
        $query = \App\Models\Bank::query();

        // Filtro ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome_banca', 'LIKE', "%{$search}%")
                  ->orWhere('iban', 'LIKE', "%{$search}%")
                  ->orWhere('abi', 'LIKE', "%{$search}%")
                  ->orWhere('cab', 'LIKE', "%{$search}%");
            });
        }

        // Filtro attive
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        // Ordinamento
        $sortBy = $request->get('sort_by', 'nome_banca');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $dati = $query->paginate(20);

        // Configurazione tabella
        $configurazione = [
            'nome' => 'Banche',
            'icona' => 'bi-bank',
            'colore' => 'info',
            'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
            'color_from' => '#48cae4',
            'color_to' => '#023e8a',
            'campi_visibili' => [
                'nome_banca' => 'Nome Banca',
                'abi' => 'ABI',
                'cab' => 'CAB',
                'iban' => 'IBAN',
                'swift' => 'SWIFT',
                'active' => 'Attivo'
            ]
        ];

        return view('configurazioni.gestione-tabelle.tabella', [
            'dati' => $dati,
            'configurazione' => $configurazione,
            'nomeTabella' => 'banche'
        ]);
    }

    /**
     * Gestisce tutte le tabelle v2 in modo unificato e semplice
     */
    private function gestisciTabellaV2(string $nomeTabella, Request $request): View
    {
        // Configurazioni per ogni tabella v2
        $configurazioni = [
            'associazioni-nature-iva' => [
                'modello' => \App\Models\VatNatureAssociation::class,
                'nome' => 'Associazioni Nature IVA',
                'icona' => 'bi-link-45deg',
                'colore' => 'primary',
                'color_from' => '#4ecdc4',
                'color_to' => '#44a08d',
                'descrizione' => 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali',
                'campi_visibili' => [
                    'nome_associazione' => 'Nome Associazione',
                    'aliquota_iva' => 'Aliquota IVA',
                    'natura_iva' => 'Natura IVA',
                    'predefinita' => 'Predefinita',
                    'active' => 'Attivo'
                ]
            ],
            'aliquote-iva' => [
                'modello' => \App\Models\TaxRate::class,
                'nome' => 'Aliquote IVA',
                'nome_singolare' => 'Aliquota IVA',
                'icona' => 'bi-percent',
                'colore' => 'danger',
                'color_from' => '#dc3545',
                'color_to' => '#c82333',
                'descrizione' => 'Gestione completa delle aliquote IVA del sistema',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'name' => 'Nome',
                    'percentuale' => 'Percentuale',
                    'active' => 'Attivo'
                ]
            ],
            'banche' => [
                'modello' => \App\Models\Bank::class,
                'nome' => 'Banche',
                'nome_singolare' => 'Banca',
                'icona' => 'bi-bank',
                'colore' => 'info',
                'color_from' => '#48cae4',
                'color_to' => '#023e8a',
                'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
                'campi_visibili' => [
                    'name' => 'Nome Banca',
                    'abi_code' => 'ABI',
                    'bic_swift' => 'BIC/SWIFT',
                    'active' => 'Attivo'
                ]
            ],
            'categorie-articoli' => [
                'modello' => \App\Models\ProductCategory::class,
                'nome' => 'Categorie Articoli',
                'nome_singolare' => 'Categoria Articoli',
                'icona' => 'bi-grid-3x3-gap',
                'colore' => 'success',
                'color_from' => '#38b000',
                'color_to' => '#70e000',
                'descrizione' => 'Sistema gerarchico per classificazione e organizzazione prodotti',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'active' => 'Attivo'
                ]
            ],
            'categorie-clienti' => [
                'modello' => \App\Models\CustomerCategory::class,
                'nome' => 'Categorie Clienti',
                'nome_singolare' => 'Categoria Clienti',
                'icona' => 'bi-people',
                'colore' => 'warning',
                'color_from' => '#f093fb',
                'color_to' => '#f5576c',
                'descrizione' => 'Segmentazione e classificazione clienti per gestione commerciale',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'active' => 'Attivo'
                ]
            ],
            'categorie-fornitori' => [
                'modello' => \App\Models\SupplierCategory::class,
                'nome' => 'Categorie Fornitori',
                'nome_singolare' => 'Categoria Fornitori',
                'icona' => 'bi-person-badge',
                'colore' => 'purple',
                'color_from' => '#9c27b0',
                'color_to' => '#7b1fa2',
                'descrizione' => 'Classificazione e gestione categorie fornitori per procurement',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'active' => 'Attivo'
                ]
            ]
        ];

        $config = $configurazioni[$nomeTabella];
        $modelClass = $config['modello'];

        // Query con relazioni specifiche per ogni tabella
        if ($nomeTabella === 'associazioni-nature-iva') {
            $query = $modelClass::with(['taxRate', 'vatNature'])->where('active', true);
        } elseif ($nomeTabella === 'categorie-articoli') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'categorie-clienti') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'categorie-fornitori') {
            $query = $modelClass::query();
        } else {
            $query = $modelClass::query();
        }

        // Filtro ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            if ($nomeTabella === 'associazioni-nature-iva') {
                $query->where(function($q) use ($search) {
                    $q->where('nome_associazione', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'aliquote-iva') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'banche') {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('bic_swift', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-articoli') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-clienti') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-fornitori') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }
        }

        // Ordinamento
        if ($nomeTabella === 'associazioni-nature-iva') {
            $query->orderBy('nome_associazione', 'asc');
        } elseif ($nomeTabella === 'aliquote-iva') {
            $query->orderBy('percentuale', 'asc');
        } elseif ($nomeTabella === 'banche') {
            $query->orderBy('name', 'asc');
        } elseif ($nomeTabella === 'categorie-articoli') {
            $query->orderBy('code', 'asc');
        } elseif ($nomeTabella === 'categorie-clienti') {
            $query->orderBy('code', 'asc');
        } elseif ($nomeTabella === 'categorie-fornitori') {
            $query->orderBy('code', 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $dati = $query->paginate(20);

        // Dati extra per Associazioni Nature IVA
        $extraData = [];
        if ($nomeTabella === 'associazioni-nature-iva') {
            $extraData['associations'] = $dati;
            $extraData['taxRates'] = \App\Models\TaxRate::where('active', true)->orderBy('percentuale')->get();
            $extraData['vatNatures'] = \App\Models\VatNature::where('active', true)->orderBy('code')->get();
        }

        return view('configurazioni.gestione-tabelle.tabella', array_merge([
            'dati' => $dati,
            'configurazione' => $config,
            'nomeTabella' => $nomeTabella
        ], $extraData));
    }

    /**
     * Store per Banche
     */
    public function storeBanca(Request $request): JsonResponse|RedirectResponse
    {
        try {
            // Validazione specifica per Banche
            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:banks,code|regex:/^[A-Z0-9_-]+$/',
                'name' => 'required|string|min:3|max:100',
                'description' => 'nullable|string|max:255',
                'abi_code' => 'nullable|string|size:5|regex:/^\d{5}$/',
                'bic_swift' => 'nullable|string|min:8|max:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email:rfc,dns|max:100',
                'is_italian' => 'nullable|boolean',
                'active' => 'nullable|boolean'
            ]);

            $elemento = \App\Models\Bank::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'abi_code' => $validated['abi_code'] ?? null,
                'bic_swift' => $validated['bic_swift'] ? strtoupper($validated['bic_swift']) : null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ? strtolower($validated['email']) : null,
                'is_italian' => !empty($validated['is_italian']),
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banca creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'banche')
                ->with('success', 'Banca creata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore creazione banca', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante la creazione della banca'])
                ->withInput();
        }
    }

    /**
     * Update per Associazioni Nature IVA
     */
    public function updateAssociazioneNatureIva(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $associazione = VatNatureAssociation::findOrFail($id);
            
            // Validazione specifica per Associazioni Nature IVA
            $validated = $request->validate([
                'nome_associazione' => 'required|string|min:3|max:255',
                'descrizione' => 'nullable|string|max:500',
                'tax_rate_id' => 'required|integer|exists:tax_rates,id',
                'vat_nature_id' => 'required|integer|exists:vat_natures,id',
                'is_default' => 'nullable|boolean'
            ]);

            // Verifica duplicati (escluso l'ID corrente)
            $exists = VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                         ->where('vat_nature_id', $validated['vat_nature_id'])
                                         ->where('active', true)
                                         ->where('id', '!=', $id)
                                         ->exists();

            if ($exists) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.',
                        'errors' => ['duplicate' => 'Associazione già esistente']
                    ], 422);
                }
                
                return back()->withErrors([
                    'duplicate' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.'
                ])->withInput();
            }

            // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
            if (!empty($validated['is_default'])) {
                VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                     ->where('id', '!=', $id)
                                     ->update(['is_default' => false]);
            }

            $associazione->update([
                'nome_associazione' => $validated['nome_associazione'],
                'name' => $validated['nome_associazione'],
                'descrizione' => $validated['descrizione'] ?? null,
                'tax_rate_id' => $validated['tax_rate_id'],
                'vat_nature_id' => $validated['vat_nature_id'],
                'is_default' => !empty($validated['is_default'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Associazione aggiornata con successo',
                    'data' => $associazione
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'associazioni-nature-iva')
                ->with('success', 'Associazione aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento associazione nature IVA', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante l\'aggiornamento dell\'associazione'])
                ->withInput();
        }
    }

    /**
     * Update per Banche
     */
    public function updateBanca(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $banca = \App\Models\Bank::findOrFail($id);
            
            // Validazione specifica per Banche (esclude ID corrente per unique)
            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:banks,code,' . $id . '|regex:/^[A-Z0-9_-]+$/',
                'name' => 'required|string|min:3|max:100',
                'description' => 'nullable|string|max:255',
                'abi_code' => 'nullable|string|size:5|regex:/^\d{5}$/',
                'bic_swift' => 'nullable|string|min:8|max:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email:rfc,dns|max:100',
                'is_italian' => 'nullable|boolean',
                'active' => 'nullable|boolean'
            ]);

            $banca->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'abi_code' => $validated['abi_code'] ?? null,
                'bic_swift' => $validated['bic_swift'] ? strtoupper($validated['bic_swift']) : null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ? strtolower($validated['email']) : null,
                'is_italian' => !empty($validated['is_italian']),
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banca aggiornata con successo',
                    'data' => $banca
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'banche')
                ->with('success', 'Banca aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento banca', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante l\'aggiornamento della banca'])
                ->withInput();
        }
    }

    /**
     * Delete per Banche
     */
    public function destroyBanca(int $id): JsonResponse
    {
        try {
            $banca = \App\Models\Bank::findOrFail($id);
            
            // TODO: Verifica se la banca è utilizzata in altre tabelle
            // $utilizzi = ...
            
            $banca->delete();

            return response()->json([
                'success' => true,
                'message' => 'Banca eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione banca', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }

    /**
     * Store per Categorie Articoli
     */
    public function storeCategoriaArticoli(Request $request): JsonResponse|RedirectResponse
    {
        try {
            // Validazione specifica per Categorie Articoli
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:product_categories,code|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $elemento = \App\Models\ProductCategory::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Articoli creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-articoli')
                ->with('success', 'Categoria Articoli creata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore creazione categoria articoli', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante la creazione della categoria'])
                ->withInput();
        }
    }

    /**
     * Update per Categorie Articoli
     */
    public function updateCategoriaArticoli(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $categoria = \App\Models\ProductCategory::findOrFail($id);
            
            // Validazione specifica per Categorie Articoli (esclude ID corrente per unique)
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:product_categories,code,' . $id . '|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $categoria->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Articoli aggiornata con successo',
                    'data' => $categoria
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-articoli')
                ->with('success', 'Categoria Articoli aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento categoria articoli', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante l\'aggiornamento della categoria'])
                ->withInput();
        }
    }

    /**
     * Delete per Categorie Articoli
     */
    public function destroyCategoriaArticoli(int $id): JsonResponse
    {
        try {
            $categoria = \App\Models\ProductCategory::findOrFail($id);
            

            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoria Articoli eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione categoria articoli', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }

    /**
     * Store per Categorie Clienti
     */
    public function storeCategoriaClienti(Request $request): JsonResponse|RedirectResponse
    {
        try {
            // Validazione specifica per Categorie Clienti (pattern identico a categorie articoli)
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:customer_categories,code|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $elemento = \App\Models\CustomerCategory::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Clienti creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-clienti')
                ->with('success', 'Categoria Clienti creata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore creazione categoria clienti', [
                'dati' => $request->all(),
                'errore' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione'
                ], 500);
            }

            return back()->with('error', 'Errore durante la creazione')->withInput();
        }
    }

    /**
     * Update per Categorie Clienti
     */
    public function updateCategoriaClienti(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $categoria = \App\Models\CustomerCategory::findOrFail($id);
            
            // Validazione specifica per Categorie Clienti (esclude ID corrente per unique) - pattern identico a categorie articoli
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:customer_categories,code,' . $id . '|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $categoria->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Clienti aggiornata con successo',
                    'data' => $categoria
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-clienti')
                ->with('success', 'Categoria Clienti aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento categoria clienti', [
                'id' => $id,
                'dati' => $request->all(),
                'errore' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()->with('error', 'Errore durante l\'aggiornamento')->withInput();
        }
    }

    /**
     * Destroy per Categorie Clienti
     */
    public function destroyCategoriaClienti(int $id): JsonResponse
    {
        try {
            $categoria = \App\Models\CustomerCategory::findOrFail($id);
            
            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoria Clienti eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione categoria clienti', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }

    /**
     * Store per Categorie Fornitori
     */
    public function storeCategoriaFornitori(Request $request): JsonResponse|RedirectResponse
    {
        try {
            // Validazione specifica per Categorie Fornitori (pattern identico a categorie clienti)
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:supplier_categories,code|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $elemento = \App\Models\SupplierCategory::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Fornitori creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-fornitori')
                ->with('success', 'Categoria Fornitori creata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore creazione categoria fornitori', [
                'dati' => $request->all(),
                'errore' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione'
                ], 500);
            }

            return back()->with('error', 'Errore durante la creazione')->withInput();
        }
    }

    /**
     * Update per Categorie Fornitori
     */
    public function updateCategoriaFornitori(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $categoria = \App\Models\SupplierCategory::findOrFail($id);
            
            // Validazione specifica per Categorie Fornitori (esclude ID corrente per unique) - pattern identico a categorie clienti
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:supplier_categories,code,' . $id . '|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);

            $categoria->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['description'], // Usiamo description come name
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria Fornitori aggiornata con successo',
                    'data' => $categoria
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'categorie-fornitori')
                ->with('success', 'Categoria Fornitori aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento categoria fornitori', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()->with('error', 'Errore durante l\'aggiornamento')->withInput();
        }
    }

    /**
     * Delete per Categorie Fornitori
     */
    public function destroyCategoriaFornitori(int $id): JsonResponse
    {
        try {
            $categoria = \App\Models\SupplierCategory::findOrFail($id);
            
            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoria Fornitori eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione categoria fornitori', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }
}