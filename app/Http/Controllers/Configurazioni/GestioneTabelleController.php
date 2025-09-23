<?php

namespace App\Http\Controllers\Configurazioni;

use App\Http\Controllers\Controller;
// use App\Services\GestioneTabelle\GestioneTabelleService; // Temporaneamente disabilitato
use App\Models\VatNatureAssociation;
use App\Models\TaxRate;
use App\Models\VatNature;
use App\Models\SystemTable;
use App\Models\SizeColor;
use App\Models\WarehouseCause;
use App\Models\FixedPriceDenomination;
use App\Models\Deposit;
use App\Models\PriceList;
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
        // Disabilitato temporaneamente per test: $this->middleware('auth');
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
                ],
                [
                    'nome' => 'taglie-colori',
                    'titolo' => 'Taglie e Colori',
                    'icona' => 'bi-palette',
                    'colore' => 'warning',
                    'descrizione' => 'Gestione varianti prodotto: taglie e colori per catalogazione articoli',
                    'color_from' => '#ffecd2',
                    'color_to' => '#fcb69f'
                ],
                [
                    'nome' => 'causali-magazzino',
                    'titolo' => 'Causali di Magazzino',
                    'icona' => 'bi-building',
                    'colore' => 'danger',
                    'descrizione' => 'Classificazione movimenti di magazzino: carico, scarico, trasferimenti',
                    'color_from' => '#ee0979',
                    'color_to' => '#ff6a00'
                ],
                [
                    'nome' => 'colori-varianti',
                    'titolo' => 'Colori Varianti',
                    'icona' => 'bi-droplet-fill',
                    'colore' => 'info',
                    'descrizione' => 'Gestione semplice colori per varianti prodotti',
                    'color_from' => '#a8edea',
                    'color_to' => '#fed6e3'
                ],
                [
                    'nome' => 'condizioni',
                    'titolo' => 'Condizioni',
                    'icona' => 'bi-list-ul',
                    'colore' => 'info',
                    'descrizione' => 'Gestione semplice condizioni di pagamento e vendita',
                    'color_from' => '#00f2fe',
                    'color_to' => '#4facfe'
                ],
                [
                    'nome' => 'denominazioni-prezzi-fissi',
                    'titolo' => 'Denominazione Prezzi Fissi',
                    'icona' => 'bi-currency-euro',
                    'colore' => 'brown',
                    'descrizione' => 'Gestione denominazioni con descrizione e commento per prezzi fissi',
                    'color_from' => '#8b4513',
                    'color_to' => '#d2691e'
                ],
                [
                    'nome' => 'depositi',
                    'titolo' => 'Depositi',
                    'icona' => 'bi-archive',
                    'colore' => 'orange',
                    'descrizione' => 'Gestione depositi e ubicazioni magazzino',
                    'color_from' => '#ff8c00',
                    'color_to' => '#ff4500'
                ],
                [
                    'nome' => 'listini',
                    'titolo' => 'Listini',
                    'icona' => 'bi-list-columns',
                    'colore' => 'teal',
                    'descrizione' => 'Gestione listini con descrizione e percentuale',
                    'color_from' => '#2d6a4f',
                    'color_to' => '#1b4332'
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
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini'])) {
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
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini'])) {
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
                ],
                'taglie-colori' => [
                    'nome' => 'Taglie e Colori',
                    'nome_singolare' => 'Taglia/Colore',
                    'icona' => 'bi-file-earmark-richtext',
                    'colore' => 'pink',
                    'descrizione' => 'Gestione varianti prodotto: taglie e colori per catalogazione articoli'
                ],
                'causali-magazzino' => [
                    'nome' => 'Causali di Magazzino',
                    'nome_singolare' => 'Causale Magazzino',
                    'icona' => 'bi-boxes',
                    'colore' => 'primary',
                    'descrizione' => 'Classificazione movimenti di magazzino: carico, scarico, trasferimenti'
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
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini'])) {
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

            if ($nomeTabella === 'taglie-colori') {
                return $this->storeTaglieColori($request);
            }

            if ($nomeTabella === 'causali-magazzino') {
                return $this->storeCausaliMagazzino($request);
            }
            if ($nomeTabella === 'colori-varianti') {
                return $this->storeColoriVarianti($request);
            }
            if ($nomeTabella === 'condizioni') {
                return $this->storeCondizioni($request);
            }
            if ($nomeTabella === 'denominazioni-prezzi-fissi') {
                return $this->storeDenominazioniPrezzisFissi($request);
            }
            if ($nomeTabella === 'depositi') {
                return $this->storeDepositi($request);
            }
            if ($nomeTabella === 'listini') {
                return $this->storeListini($request);
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
                } elseif ($nomeTabella === 'taglie-colori') {
                    $element = \App\Models\SizeColor::findOrFail($id);
                } elseif ($nomeTabella === 'causali-magazzino') {
                    $element = \App\Models\WarehouseCause::findOrFail($id);
                } elseif ($nomeTabella === 'colori-varianti') {
                    $element = \App\Models\ColorVariant::findOrFail($id);
                } elseif ($nomeTabella === 'condizioni') {
                    $element = \App\Models\Condition::findOrFail($id);
                } elseif ($nomeTabella === 'denominazioni-prezzi-fissi') {
                    $element = \App\Models\FixedPriceDenomination::findOrFail($id);
                } elseif ($nomeTabella === 'depositi') {
                    $element = \App\Models\Deposit::findOrFail($id);
                } elseif ($nomeTabella === 'listini') {
                    $element = \App\Models\PriceList::findOrFail($id);
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
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini'])) {
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

            if ($nomeTabella === 'taglie-colori') {
                return $this->updateTaglieColori($request, $id);
            }

            if ($nomeTabella === 'causali-magazzino') {
                return $this->updateCausaliMagazzino($request, $id);
            }
            if ($nomeTabella === 'colori-varianti') {
                return $this->updateColoriVarianti($request, $id);
            }
            if ($nomeTabella === 'condizioni') {
                return $this->updateCondizioni($request, $id);
            }
            if ($nomeTabella === 'denominazioni-prezzi-fissi') {
                return $this->updateDenominazioniPrezzisFissi($request, $id);
            }
            if ($nomeTabella === 'depositi') {
                return $this->updateDepositi($request, $id);
            }
            if ($nomeTabella === 'listini') {
                return $this->updateListini($request, $id);
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
            } elseif ($nomeTabella === 'taglie-colori') {
                return $this->destroyTaglieColori($id);
            } elseif ($nomeTabella === 'causali-magazzino') {
                return $this->destroyCausaliMagazzino($id);
            } elseif ($nomeTabella === 'colori-varianti') {
                return $this->destroyColoriVarianti($id);
            } elseif ($nomeTabella === 'condizioni') {
                return $this->destroyCondizioni($id);
            } elseif ($nomeTabella === 'denominazioni-prezzi-fissi') {
                return $this->destroyDenominazioniPrezzisFissi($id);
            } elseif ($nomeTabella === 'depositi') {
                return $this->destroyDepositi($id);
            } elseif ($nomeTabella === 'listini') {
                return $this->destroyListini($id);
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
            ],
            'taglie-colori' => [
                'modello' => \App\Models\SizeColor::class,
                'nome' => 'Taglie e Colori',
                'nome_singolare' => 'Taglia/Colore',
                'icona' => 'bi-palette',
                'colore' => 'warning',
                'color_from' => '#ffecd2',
                'color_to' => '#fcb69f',
                'descrizione' => 'Gestione varianti prodotto: taglie e colori per catalogazione articoli',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'name' => 'Nome',
                    'type' => 'Tipo',
                    'active' => 'Attivo'
                ]
            ],
            'causali-magazzino' => [
                'modello' => \App\Models\WarehouseCause::class,
                'nome' => 'Causali di Magazzino',
                'nome_singolare' => 'Causale Magazzino',
                'icona' => 'bi-building',
                'colore' => 'danger',
                'color_from' => '#ee0979',
                'color_to' => '#ff6a00',
                'descrizione' => 'Gestione semplificata causali di magazzino con codice e descrizione',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'colori-varianti' => [
                'modello' => \App\Models\ColorVariant::class,
                'nome' => 'Colori Varianti',
                'nome_singolare' => 'Colore Variante',
                'icona' => 'bi-droplet-fill',
                'colore' => 'info',
                'color_from' => '#a8edea',
                'color_to' => '#fed6e3',
                'descrizione' => 'Gestione semplice colori per varianti prodotti',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'condizioni' => [
                'modello' => \App\Models\Condition::class,
                'nome' => 'Condizioni',
                'nome_singolare' => 'Condizione',
                'icona' => 'bi-list-ul',
                'colore' => 'info',
                'color_from' => '#00f2fe',
                'color_to' => '#4facfe',
                'descrizione' => 'Gestione semplice condizioni di pagamento e vendita',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'denominazioni-prezzi-fissi' => [
                'modello' => \App\Models\FixedPriceDenomination::class,
                'nome' => 'Denominazione Prezzi Fissi',
                'nome_singolare' => 'Denominazione',
                'icona' => 'bi-currency-euro',
                'colore' => 'brown',
                'color_from' => '#8b4513',
                'color_to' => '#d2691e',
                'descrizione' => 'Gestione denominazioni con descrizione e commento per prezzi fissi',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ]
            ],
            'depositi' => [
                'modello' => \App\Models\Deposit::class,
                'nome' => 'Depositi',
                'nome_singolare' => 'Deposito',
                'icona' => 'bi-archive',
                'colore' => 'orange',
                'color_from' => '#ff8c00',
                'color_to' => '#ff4500',
                'descrizione' => 'Gestione depositi e ubicazioni magazzino',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'address' => 'Indirizzo',
                    'city' => 'Località',
                    'state' => 'Stato',
                    'province' => 'Provincia',
                    'postal_code' => 'CAP',
                    'phone' => 'Telefono',
                    'fax' => 'Fax'
                ]
            ],
            'listini' => [
                'modello' => \App\Models\PriceList::class,
                'nome' => 'Listini',
                'nome_singolare' => 'Listino',
                'icona' => 'bi-list-columns',
                'colore' => 'teal',
                'color_from' => '#2d6a4f',
                'color_to' => '#1b4332',
                'descrizione' => 'Gestione listini con descrizione e percentuale',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'discount_percentage' => 'Percentuale'
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
        } elseif ($nomeTabella === 'taglie-colori') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'causali-magazzino') {
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
            } elseif ($nomeTabella === 'taglie-colori') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('type', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'causali-magazzino') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'colori-varianti') {
                $query->where(function($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'condizioni') {
                $query->where(function($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%");
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
        } elseif ($nomeTabella === 'taglie-colori') {
            $query->orderBy('type', 'asc')->orderBy('code', 'asc');
        } elseif ($nomeTabella === 'causali-magazzino') {
            $query->orderBy('code', 'asc');
        } elseif ($nomeTabella === 'colori-varianti') {
            $query->orderBy('description', 'asc');
        } elseif ($nomeTabella === 'condizioni') {
            $query->orderBy('description', 'asc');
        } elseif ($nomeTabella === 'denominazioni-prezzi-fissi') {
            $query->orderBy('description', 'asc');
        } elseif ($nomeTabella === 'depositi') {
            $query->orderBy('code', 'asc');
        } elseif ($nomeTabella === 'listini') {
            $query->orderBy('description', 'asc');
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
            \Log::info('=== DEBUG CATEGORIE CLIENTI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Converti code in maiuscolo PRIMA della validazione
            $code = strtoupper($request->input('code', ''));
            $request->merge(['code' => $code]);
            
            \Log::info('Code convertito in maiuscolo:', ['code' => $code]);
            
            // Validazione semplificata
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:customer_categories,code',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\CustomerCategory::create([
                'code' => $validated['code'],
                'name' => $validated['description'],
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

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
            \Log::info('=== DEBUG CATEGORIE FORNITORI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Converti code in maiuscolo PRIMA della validazione
            $code = strtoupper($request->input('code', ''));
            $request->merge(['code' => $code]);
            
            \Log::info('Code convertito in maiuscolo:', ['code' => $code]);
            
            // Validazione semplificata
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:supplier_categories,code',
                'description' => 'required|string|min:3|max:255',
                'active' => 'nullable|boolean'
            ]);
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\SupplierCategory::create([
                'code' => $validated['code'],
                'name' => $validated['description'],
                'description' => $validated['description'],
                'active' => !empty($validated['active'])
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

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

    /**
     * Store per Taglie e Colori
     */
    public function storeTaglieColori(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG TAGLIE E COLORI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Taglie e Colori
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:size_colors,code|regex:/^[A-Z0-9_-]+$/',
                'name' => 'required|string|min:1|max:255',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:TAGLIA,COLORE',
                'active' => 'nullable|boolean'
            ]);
            
            \Log::info('Validazione passata:', $validated);

            $elemento = SizeColor::create([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'active' => !empty($validated['active'])
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Taglia/Colore creato con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'taglie-colori')
                ->with('success', 'Taglia/Colore creato con successo');

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
            Log::error('Errore creazione taglia/colore', [
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
     * Update per Taglie e Colori
     */
    public function updateTaglieColori(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $tagliaColore = SizeColor::findOrFail($id);
            
            // Validazione specifica per Taglie e Colori (esclude ID corrente per unique)
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:size_colors,code,' . $id . '|regex:/^[A-Z0-9_-]+$/',
                'name' => 'required|string|min:1|max:255',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:TAGLIA,COLORE',
                'active' => 'nullable|boolean'
            ]);

            $tagliaColore->update([
                'code' => strtoupper($validated['code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'active' => !empty($validated['active'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Taglia/Colore aggiornato con successo',
                    'data' => $tagliaColore
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'taglie-colori')
                ->with('success', 'Taglia/Colore aggiornato con successo');

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
            Log::error('Errore aggiornamento taglia/colore', [
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
     * Delete per Taglie e Colori
     */
    public function destroyTaglieColori(int $id): JsonResponse
    {
        try {
            $tagliaColore = SizeColor::findOrFail($id);
            
            // Verifica se può essere eliminato (business logic)
            if (!$tagliaColore->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: la taglia/colore è utilizzata in altri elementi'
                ], 422);
            }

            $tagliaColore->delete();

            return response()->json([
                'success' => true,
                'message' => 'Taglia/Colore eliminato con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione taglia/colore', [
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
     * Store per Causali di Magazzino
     */
    public function storeCausaliMagazzino(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG CAUSALI MAGAZZINO ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Causali di Magazzino
            $validated = $request->validate(WarehouseCause::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = WarehouseCause::create([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description']
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Causale Magazzino creata con successo',
                    'data' => $elemento
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'causali-magazzino')
                ->with('success', 'Causale Magazzino creata con successo');

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
            Log::error('Errore creazione causale magazzino', [
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
     * Update per Causali di Magazzino
     */
    public function updateCausaliMagazzino(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $causale = WarehouseCause::findOrFail($id);
            
            // Validazione specifica per Causali di Magazzino (esclude ID corrente per unique)
            $validated = $request->validate(WarehouseCause::validationRules($id));

            $causale->update([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description']
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Causale Magazzino aggiornata con successo',
                    'data' => $causale
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'causali-magazzino')
                ->with('success', 'Causale Magazzino aggiornata con successo');

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
            Log::error('Errore aggiornamento causale magazzino', [
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
     * Delete per Causali di Magazzino
     */
    public function destroyCausaliMagazzino(int $id): JsonResponse
    {
        try {
            $causale = WarehouseCause::findOrFail($id);
            
            // Verifica se può essere eliminata (business logic)
            if (!$causale->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: la causale è utilizzata in movimenti di magazzino'
                ], 422);
            }

            $causale->delete();

            return response()->json([
                'success' => true,
                'message' => 'Causale Magazzino eliminata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore eliminazione causale magazzino', [
                'id' => $id,
                'errore' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione'
            ], 500);
        }
    }

    // =====================================================
    // METODI SPECIFICI PER COLORI VARIANTI
    // =====================================================

    public function storeColoriVarianti(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG COLORI VARIANTI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Colori Varianti
            $validated = $request->validate(\App\Models\ColorVariant::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\ColorVariant::create([
                'description' => $validated['description']
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Colore Variante creato con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'colori-varianti')
                ->with('success', 'Colore Variante creato con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Colori Varianti:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Colore Variante:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'colori-varianti')
                ->with('error', 'Errore durante creazione colore: ' . $e->getMessage());
        }
    }

    public function updateColoriVarianti(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $colore = \App\Models\ColorVariant::findOrFail($id);
            
            // Validazione specifica per Colori Varianti (esclude ID corrente per unique)
            $validated = $request->validate(\App\Models\ColorVariant::validationRules($id));

            $colore->update([
                'description' => $validated['description']
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Colore Variante aggiornato con successo',
                    'data' => $colore
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'colori-varianti')
                ->with('success', 'Colore Variante aggiornato con successo');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Colore Variante:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'colori-varianti')
                ->with('error', 'Errore durante aggiornamento colore: ' . $e->getMessage());
        }
    }

    public function destroyColoriVarianti(int $id): JsonResponse
    {
        try {
            $colore = \App\Models\ColorVariant::findOrFail($id);
            
            // Verifica se può essere eliminato (business logic)
            if (!$colore->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: il colore è utilizzato in prodotti esistenti'
                ], 422);
            }
            
            $colore->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Colore Variante eliminato con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Colore Variante:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // METODI SPECIFICI PER CONDIZIONI
    // =====================================================

    public function storeCondizioni(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG CONDIZIONI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Condizioni
            $validated = $request->validate(\App\Models\Condition::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\Condition::create([
                'description' => $validated['description']
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Condizione creata con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'condizioni')
                ->with('success', 'Condizione creata con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Condizioni:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Condizione:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'condizioni')
                ->with('error', 'Errore durante creazione condizione: ' . $e->getMessage());
        }
    }

    public function updateCondizioni(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $condizione = \App\Models\Condition::findOrFail($id);
            
            // Validazione specifica per Condizioni (esclude ID corrente per unique)
            $validated = $request->validate(\App\Models\Condition::validationRules($id));

            $condizione->update([
                'description' => $validated['description']
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Condizione aggiornata con successo',
                    'data' => $condizione
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'condizioni')
                ->with('success', 'Condizione aggiornata con successo');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Condizione:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'condizioni')
                ->with('error', 'Errore durante aggiornamento condizione: ' . $e->getMessage());
        }
    }

    public function destroyCondizioni(int $id): JsonResponse
    {
        try {
            $condizione = \App\Models\Condition::findOrFail($id);
            
            // Verifica se può essere eliminata (business logic)
            if (!$condizione->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: la condizione è utilizzata in documenti esistenti'
                ], 422);
            }
            
            $condizione->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Condizione eliminata con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Condizione:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // METODI SPECIFICI PER DENOMINAZIONI PREZZI FISSI
    // =====================================================

    public function storeDenominazioniPrezzisFissi(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG DENOMINAZIONI PREZZI FISSI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Denominazioni Prezzi Fissi
            $validated = $request->validate(\App\Models\FixedPriceDenomination::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\FixedPriceDenomination::create([
                'description' => $validated['description'],
                'comment' => $validated['comment'] ?? null
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Denominazione creata con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'denominazioni-prezzi-fissi')
                ->with('success', 'Denominazione creata con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Denominazioni Prezzi Fissi:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Denominazione Prezzo Fisso:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'denominazioni-prezzi-fissi')
                ->with('error', 'Errore durante creazione denominazione: ' . $e->getMessage());
        }
    }

    public function updateDenominazioniPrezzisFissi(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $denominazione = \App\Models\FixedPriceDenomination::findOrFail($id);
            
            // Validazione specifica per Denominazioni Prezzi Fissi (esclude ID corrente per unique)
            $validated = $request->validate(\App\Models\FixedPriceDenomination::validationRules($id));

            $denominazione->update([
                'description' => $validated['description'],
                'comment' => $validated['comment'] ?? null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Denominazione aggiornata con successo',
                    'data' => $denominazione->fresh()
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'denominazioni-prezzi-fissi')
                ->with('success', 'Denominazione aggiornata con successo');
                
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Denominazione Prezzo Fisso:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'denominazioni-prezzi-fissi')
                ->with('error', 'Errore durante aggiornamento denominazione: ' . $e->getMessage());
        }
    }

    public function destroyDenominazioniPrezzisFissi(int $id): JsonResponse
    {
        try {
            $denominazione = \App\Models\FixedPriceDenomination::findOrFail($id);
            
            // Verifica se può essere eliminata (business logic)
            if (!$denominazione->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: la denominazione è utilizzata nel sistema'
                ], 422);
            }
            
            $denominazione->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Denominazione eliminata con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Denominazione Prezzo Fisso:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // METODI SPECIFICI PER DEPOSITI
    // =====================================================

    public function storeDepositi(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG DEPOSITI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Depositi
            $validated = $request->validate(\App\Models\Deposit::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\Deposit::create([
                'code' => $validated['code'],
                'description' => $validated['description'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'province' => $validated['province'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'fax' => $validated['fax'] ?? null
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposito creato con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('success', 'Deposito creato con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Depositi:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Deposito:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('error', 'Errore durante creazione deposito: ' . $e->getMessage());
        }
    }

    public function updateDepositi(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $deposito = \App\Models\Deposit::findOrFail($id);
            
            // Validazione specifica per Depositi (esclude ID corrente per unique)
            $validated = $request->validate(\App\Models\Deposit::validationRules($id));

            $deposito->update([
                'code' => $validated['code'],
                'description' => $validated['description'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'province' => $validated['province'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'fax' => $validated['fax'] ?? null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposito aggiornato con successo',
                    'data' => $deposito->fresh()
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('success', 'Deposito aggiornato con successo');
                
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Deposito:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('error', 'Errore durante aggiornamento deposito: ' . $e->getMessage());
        }
    }

    public function destroyDepositi(int $id): JsonResponse
    {
        try {
            $deposito = \App\Models\Deposit::findOrFail($id);
            
            // Verifica se può essere eliminato (business logic)
            if (!$deposito->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: il deposito è utilizzato nel sistema'
                ], 422);
            }
            
            $deposito->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Deposito eliminato con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Deposito:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // METODI SPECIFICI PER LISTINI
    // =====================================================

    public function storeListini(Request $request): JsonResponse|RedirectResponse
    {
        try {
            \Log::info('=== DEBUG LISTINI ===');
            \Log::info('Dati ricevuti:', $request->all());
            
            // Validazione specifica per Listini
            $validated = $request->validate(\App\Models\PriceList::validationRules());
            
            \Log::info('Validazione passata:', $validated);

            $elemento = \App\Models\PriceList::create([
                'description' => $validated['description'],
                'discount_percentage' => $validated['discount_percentage']
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listino creato con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'listini')
                ->with('success', 'Listino creato con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Listini:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Listino:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'listini')
                ->with('error', 'Errore durante creazione listino: ' . $e->getMessage());
        }
    }

    public function updateListini(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $listino = \App\Models\PriceList::findOrFail($id);
            
            // Validazione specifica per Listini (esclude ID corrente per unique)
            $validated = $request->validate(\App\Models\PriceList::validationRules($id));

            $listino->update([
                'description' => $validated['description'],
                'discount_percentage' => $validated['discount_percentage']
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listino aggiornato con successo',
                    'data' => $listino->fresh()
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'listini')
                ->with('success', 'Listino aggiornato con successo');
                
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Listino:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'listini')
                ->with('error', 'Errore durante aggiornamento listino: ' . $e->getMessage());
        }
    }

    public function destroyListini(int $id): JsonResponse
    {
        try {
            $listino = \App\Models\PriceList::findOrFail($id);
            
            // Verifica se può essere eliminato (business logic)
            if (!$listino->canBeDeleted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile eliminare: il listino è utilizzato nel sistema'
                ], 422);
            }
            
            $listino->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Listino eliminato con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Listino:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }
}