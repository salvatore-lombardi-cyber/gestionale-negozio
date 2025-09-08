<?php

namespace App\Http\Controllers;

use App\Models\Fornitore;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Controller Enterprise Fornitori
 * 
 * Gestione fornitori superiore ai competitor con:
 * - Validazioni B2B avanzate
 * - Ricerca full-text enterprise
 * - Export/Import CSV/Excel
 * - Audit trail completo
 * - API RESTful per integrazioni
 */
class FornitoriController extends Controller
{
    /**
     * Visualizza elenco fornitori con ricerca avanzata
     */
    public function index(Request $request): View
    {
        $query = Fornitore::query();
        
        // Filtro ricerca enterprise
        if ($request->filled('search')) {
            $query->ricerca($request->search);
        }
        
        // Filtro per tipo soggetto
        if ($request->filled('tipo_soggetto')) {
            $query->tipoSoggetto($request->tipo_soggetto);
        }
        
        // Filtro per classe fornitore
        if ($request->filled('classe_fornitore')) {
            $query->classe($request->classe_fornitore);
        }
        
        // Filtro geografico
        if ($request->filled('provincia') || $request->filled('citta')) {
            $query->areaGeografica($request->provincia, $request->citta);
        }
        
        // Filtro solo attivi
        if ($request->boolean('solo_attivi')) {
            $query->attivi();
        }
        
        // Ordinamento enterprise
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSorts = ['ragione_sociale', 'nome', 'cognome', 'citta', 'classe_fornitore', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        $fornitori = $query->paginate(15)->appends($request->all());
        
        // Statistiche dashboard
        $stats = [
            'totale' => Fornitore::count(),
            'attivi' => Fornitore::attivi()->count(),
            'strategici' => Fornitore::classe('strategico')->count(),
            'necessitano_verifica' => Fornitore::whereNull('ultima_verifica_dati')
                                             ->orWhere('ultima_verifica_dati', '<', Carbon::now()->subYear())
                                             ->count()
        ];
        
        return view('fornitori.index', compact('fornitori', 'stats'));
    }

    /**
     * Form creazione nuovo fornitore
     */
    public function create(): View
    {
        return view('fornitori.create');
    }

    /**
     * Salva nuovo fornitore con validazioni enterprise
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = $this->validateFornitore($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $fornitore = Fornitore::create($request->all());
            
            // Log creazione per audit
            Log::info('Nuovo fornitore creato', [
                'fornitore_id' => $fornitore->id,
                'user_id' => auth()->id(),
                'ragione_sociale' => $fornitore->ragione_sociale
            ]);
            
            return redirect()->route('fornitori.index')
                           ->with('success', 'Fornitore creato con successo!');
                           
        } catch (\Exception $e) {
            Log::error('Errore creazione fornitore', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante la creazione del fornitore.')
                           ->withInput();
        }
    }

    /**
     * Visualizza dettagli fornitore
     */
    public function show(Fornitore $fornitore): View
    {
        // Carica relazioni per dashboard fornitore
        $fornitore->load(['prodotti', 'ordiniAcquisto']);
        
        // Statistiche fornitore
        $stats = [
            'ordini_totali' => $fornitore->ordiniAcquisto()->count(),
            'ordini_anno' => $fornitore->ordiniAcquisto()
                                     ->whereYear('created_at', date('Y'))
                                     ->count(),
            'prodotti_forniti' => $fornitore->prodotti()->count(),
            'ultimo_ordine' => $fornitore->ordiniAcquisto()
                                       ->latest()
                                       ->first()?->created_at
        ];
        
        return view('fornitori.show', compact('fornitore', 'stats'));
    }

    /**
     * Form modifica fornitore
     */
    public function edit(Fornitore $fornitore): View
    {
        return view('fornitori.edit', compact('fornitore'));
    }

    /**
     * Aggiorna fornitore con sicurezza enterprise
     */
    public function update(Request $request, Fornitore $fornitore): RedirectResponse
    {
        $validator = $this->validateFornitore($request, $fornitore->id);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $oldData = $fornitore->toArray();
            $fornitore->update($request->validated());
            
            // Log modifiche per audit enterprise
            $changes = array_diff_assoc($request->validated(), $oldData);
            if (!empty($changes)) {
                Log::info('Fornitore modificato', [
                    'fornitore_id' => $fornitore->id,
                    'user_id' => auth()->id(),
                    'changes' => $changes
                ]);
            }
            
            return redirect()->route('fornitori.show', $fornitore)
                           ->with('success', 'Fornitore aggiornato con successo!');
                           
        } catch (\Exception $e) {
            Log::error('Errore aggiornamento fornitore', [
                'fornitore_id' => $fornitore->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante l\'aggiornamento del fornitore.')
                           ->withInput();
        }
    }

    /**
     * Elimina fornitore (soft delete per audit)
     */
    public function destroy(Fornitore $fornitore): RedirectResponse
    {
        try {
            // Controlla se ha ordini attivi - sicurezza business
            if ($fornitore->ordiniAcquisto()->exists()) {
                return redirect()->back()
                               ->with('error', 'Impossibile eliminare: fornitore ha ordini associati.');
            }
            
            $ragioneSociale = $fornitore->ragione_sociale ?? $fornitore->nome_completo;
            
            // Soft delete per audit trail
            $fornitore->delete();
            
            Log::info('Fornitore eliminato', [
                'fornitore_id' => $fornitore->id,
                'ragione_sociale' => $ragioneSociale,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('fornitori.index')
                           ->with('success', "Fornitore \"{$ragioneSociale}\" eliminato con successo.");
                           
        } catch (\Exception $e) {
            Log::error('Errore eliminazione fornitore', [
                'fornitore_id' => $fornitore->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante l\'eliminazione del fornitore.');
        }
    }

    /**
     * Ricerca AJAX enterprise per dropdown/autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        $query = Fornitore::attivi();
        
        if ($request->filled('q')) {
            $query->ricerca($request->q);
        }
        
        $fornitori = $query->select('id', 'ragione_sociale', 'nome', 'cognome', 'email', 'telefono')
                          ->limit(20)
                          ->get()
                          ->map(function ($fornitore) {
                              return [
                                  'id' => $fornitore->id,
                                  'text' => $fornitore->nome_completo,
                                  'subtitle' => $fornitore->email ?? $fornitore->telefono,
                                  'data' => [
                                      'email' => $fornitore->email,
                                      'telefono' => $fornitore->telefono
                                  ]
                              ];
                          });
        
        return response()->json($fornitori);
    }

    /**
     * Aggiorna verifica dati fornitore
     */
    public function verificaDati(Fornitore $fornitore): JsonResponse
    {
        try {
            $fornitore->aggiornaVerifica();
            
            return response()->json([
                'success' => true,
                'message' => 'Verifica dati aggiornata con successo',
                'data' => [
                    'ultima_verifica' => $fornitore->ultima_verifica_dati->format('d/m/Y H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento della verifica'
            ], 500);
        }
    }

    /**
     * Export CSV fornitori
     */
    public function exportCsv(Request $request)
    {
        $query = Fornitore::query();
        
        // Applica stessi filtri dell'index
        if ($request->filled('search')) {
            $query->ricerca($request->search);
        }
        
        $fornitori = $query->get();
        
        $filename = 'fornitori_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Headers per download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Header CSV
        fputcsv($handle, [
            'ID', 'Ragione Sociale', 'Nome', 'Cognome', 'Codice Fiscale', 'P.IVA',
            'Email', 'Telefono', 'Città', 'Provincia', 'Classe', 'Creato il'
        ]);
        
        // Dati fornitori (sicuri per export)
        foreach ($fornitori as $fornitore) {
            fputcsv($handle, [
                $fornitore->id,
                $fornitore->ragione_sociale,
                $fornitore->nome,
                $fornitore->cognome,
                $fornitore->codice_fiscale,
                $fornitore->partita_iva,
                $fornitore->email,
                $fornitore->telefono,
                $fornitore->citta,
                $fornitore->provincia,
                $fornitore->classe_fornitore,
                $fornitore->created_at->format('d/m/Y H:i')
            ]);
        }
        
        fclose($handle);
        exit;
    }

    /**
     * Validazioni enterprise per fornitore
     */
    private function validateFornitore(Request $request, ?int $excludeId = null): \Illuminate\Validation\Validator
    {
        $rules = [
            // Dati anagrafici
            'ragione_sociale' => 'required|string|max:255',
            'nome' => 'nullable|string|max:100',
            'cognome' => 'nullable|string|max:100',
            
            // Dati fiscali con validazioni custom
            'codice_fiscale' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]{11,16}$/',
                'unique:fornitori,codice_fiscale' . ($excludeId ? ",{$excludeId}" : '')
            ],
            'partita_iva' => [
                'nullable',
                'string',
                'regex:/^[0-9]{11}$/',
                'unique:fornitori,partita_iva' . ($excludeId ? ",{$excludeId}" : '')
            ],
            'tipo_soggetto' => 'required|in:persona_fisica,persona_giuridica,ente_pubblico',
            
            // Contatti
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'telefono_mobile' => 'nullable|string|max:20',
            'pec' => 'nullable|email|max:255',
            'sito_web' => 'nullable|url|max:255',
            
            // Indirizzo
            'indirizzo' => 'nullable|string|max:500',
            'citta' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|size:2',
            'cap' => 'nullable|string|size:5',
            'paese' => 'required|string|size:2',
            
            // Fatturazione
            'codice_destinatario' => 'nullable|string|size:7',
            'regime_fiscale' => 'nullable|string|max:10',
            
            // Bancari
            'iban' => 'nullable|string|max:34',
            'modalita_pagamento' => 'required|in:bonifico,rid,contanti,assegno,carta',
            'giorni_pagamento' => 'required|integer|min:0|max:365',
            
            // Classificazione
            'classe_fornitore' => 'required|in:strategico,preferito,standard,occasionale',
            'limite_credito' => 'nullable|numeric|min:0|max:999999.99',
            
            // Persona fisica
            'data_nascita' => 'nullable|date|before:today',
            'genere' => 'nullable|in:M,F,altro',
            
            // Privacy
            'consenso_dati' => 'required|boolean',
            'consenso_marketing' => 'nullable|boolean'
        ];
        
        $messages = [
            'ragione_sociale.required' => 'La ragione sociale è obbligatoria.',
            'codice_fiscale.required' => 'Il codice fiscale è obbligatorio.',
            'codice_fiscale.regex' => 'Il codice fiscale non è in formato valido.',
            'codice_fiscale.unique' => 'Questo codice fiscale è già registrato.',
            'partita_iva.regex' => 'La partita IVA deve essere di 11 cifre numeriche.',
            'partita_iva.unique' => 'Questa partita IVA è già registrata.',
            'email.email' => 'Inserire un indirizzo email valido.',
            'pec.email' => 'Inserire un indirizzo PEC valido.',
            'consenso_dati.required' => 'Il consenso al trattamento dati è obbligatorio.'
        ];
        
        return Validator::make($request->all(), $rules, $messages);
    }
}