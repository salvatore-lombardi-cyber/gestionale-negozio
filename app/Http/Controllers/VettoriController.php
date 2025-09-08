<?php

namespace App\Http\Controllers;

use App\Models\Vettore;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Controller Enterprise Vettori
 * 
 * Gestione vettori/spedizionieri superiore ai competitor con:
 * - Validazioni trasporti avanzate
 * - Calcolo automatico costi spedizione
 * - Integrazione API tracking
 * - Analisi performance consegne
 * - Export/Import dati logistici
 * - Audit trail completo
 */
class VettoriController extends Controller
{
    /**
     * Visualizza elenco vettori con ricerca avanzata
     */
    public function index(Request $request): View
    {
        $query = Vettore::query();
        
        // Filtro ricerca enterprise
        if ($request->filled('search')) {
            $query->ricerca($request->search);
        }
        
        // Filtro per tipo vettore
        if ($request->filled('tipo_vettore')) {
            $query->tipoVettore($request->tipo_vettore);
        }
        
        // Filtro per classe vettore
        if ($request->filled('classe_vettore')) {
            $query->classe($request->classe_vettore);
        }
        
        // Filtro geografico
        if ($request->filled('provincia') || $request->filled('citta')) {
            $query->areaGeografica($request->provincia, $request->citta);
        }
        
        // Filtro rating minimo
        if ($request->filled('rating_minimo')) {
            $query->conRatingMinimo($request->rating_minimo);
        }
        
        // Filtro solo attivi
        if ($request->boolean('solo_attivi')) {
            $query->attivi();
        }
        
        // Ordinamento enterprise
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSorts = ['ragione_sociale', 'nome_commerciale', 'tipo_vettore', 'classe_vettore', 'valutazione', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        $vettori = $query->paginate(15)->appends($request->all());
        
        // Statistiche dashboard
        $stats = [
            'totale' => Vettore::count(),
            'attivi' => Vettore::attivi()->count(),
            'premium' => Vettore::classe('premium')->count(),
            'corrieri_express' => Vettore::tipoVettore('corriere_espresso')->count(),
            'rating_alto' => Vettore::conRatingMinimo(4.0)->count(),
            'necessitano_verifica' => Vettore::whereNull('ultima_verifica_dati')
                                           ->orWhere('ultima_verifica_dati', '<', Carbon::now()->subYear())
                                           ->count()
        ];
        
        return view('vettori.index', compact('vettori', 'stats'));
    }

    /**
     * Form creazione nuovo vettore
     */
    public function create(): View
    {
        return view('vettori.create');
    }

    /**
     * Salva nuovo vettore con validazioni enterprise
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = $this->validateVettore($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            // Prepara dati per salvataggio
            $data = $request->all();
            
            // Converte array JSON per campi specifici
            if ($request->filled('servizi_offerti')) {
                $data['servizi_offerti'] = is_array($request->servizi_offerti) 
                    ? $request->servizi_offerti 
                    : [$request->servizi_offerti];
            }
            
            if ($request->filled('aree_copertura')) {
                $data['aree_copertura'] = is_array($request->aree_copertura) 
                    ? $request->aree_copertura 
                    : [$request->aree_copertura];
            }
            
            if ($request->filled('tipologie_merci')) {
                $data['tipologie_merci'] = is_array($request->tipologie_merci) 
                    ? $request->tipologie_merci 
                    : [$request->tipologie_merci];
            }
            
            if ($request->filled('abilitazioni_speciali')) {
                $data['abilitazioni_speciali'] = is_array($request->abilitazioni_speciali) 
                    ? $request->abilitazioni_speciali 
                    : [$request->abilitazioni_speciali];
            }
            
            $vettore = Vettore::create($data);
            
            // Log creazione per audit
            Log::info('Nuovo vettore creato', [
                'vettore_id' => $vettore->id,
                'user_id' => auth()->id(),
                'ragione_sociale' => $vettore->ragione_sociale
            ]);
            
            return redirect()->route('vettori.index')
                           ->with('success', 'Vettore creato con successo!');
                           
        } catch (\Exception $e) {
            Log::error('Errore creazione vettore', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante la creazione del vettore.')
                           ->withInput();
        }
    }

    /**
     * Visualizza dettagli vettore
     */
    public function show(Vettore $vettore): View
    {
        // Carica relazioni per dashboard vettore
        $vettore->load(['spedizioni']);
        
        // Statistiche vettore
        $stats = [
            'spedizioni_totali' => $vettore->spedizioni()->count(),
            'spedizioni_anno' => $vettore->spedizioni()
                                        ->whereYear('created_at', date('Y'))
                                        ->count(),
            'ultima_spedizione' => $vettore->spedizioni()
                                          ->latest()
                                          ->first()?->created_at,
            'rating_stelle' => $vettore->valutazione ? str_repeat('⭐', (int) round($vettore->valutazione)) : 'N/A'
        ];
        
        return view('vettori.show', compact('vettore', 'stats'));
    }

    /**
     * Form modifica vettore
     */
    public function edit(Vettore $vettore): View
    {
        return view('vettori.edit', compact('vettore'));
    }

    /**
     * Aggiorna vettore con sicurezza enterprise
     */
    public function update(Request $request, Vettore $vettore): RedirectResponse
    {
        $validator = $this->validateVettore($request, $vettore->id);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        try {
            $oldData = $vettore->toArray();
            
            // Prepara dati per aggiornamento
            $data = $request->all();
            
            // Converte array JSON per campi specifici
            if ($request->filled('servizi_offerti')) {
                $data['servizi_offerti'] = is_array($request->servizi_offerti) 
                    ? $request->servizi_offerti 
                    : [$request->servizi_offerti];
            }
            
            if ($request->filled('aree_copertura')) {
                $data['aree_copertura'] = is_array($request->aree_copertura) 
                    ? $request->aree_copertura 
                    : [$request->aree_copertura];
            }
            
            if ($request->filled('tipologie_merci')) {
                $data['tipologie_merci'] = is_array($request->tipologie_merci) 
                    ? $request->tipologie_merci 
                    : [$request->tipologie_merci];
            }
            
            if ($request->filled('abilitazioni_speciali')) {
                $data['abilitazioni_speciali'] = is_array($request->abilitazioni_speciali) 
                    ? $request->abilitazioni_speciali 
                    : [$request->abilitazioni_speciali];
            }
            
            $vettore->update($data);
            
            // Log modifiche per audit enterprise
            $changes = array_diff_assoc($data, $oldData);
            if (!empty($changes)) {
                Log::info('Vettore modificato', [
                    'vettore_id' => $vettore->id,
                    'user_id' => auth()->id(),
                    'changes' => $changes
                ]);
            }
            
            return redirect()->route('vettori.show', $vettore)
                           ->with('success', 'Vettore aggiornato con successo!');
                           
        } catch (\Exception $e) {
            Log::error('Errore aggiornamento vettore', [
                'vettore_id' => $vettore->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante l\'aggiornamento del vettore.')
                           ->withInput();
        }
    }

    /**
     * Elimina vettore (soft delete per audit)
     */
    public function destroy(Vettore $vettore): RedirectResponse
    {
        try {
            // Controlla se ha spedizioni attive - sicurezza business
            if ($vettore->spedizioni()->exists()) {
                return redirect()->back()
                               ->with('error', 'Impossibile eliminare: vettore ha spedizioni associate.');
            }
            
            $ragioneSociale = $vettore->nome_completo;
            
            // Soft delete per audit trail
            $vettore->delete();
            
            Log::info('Vettore eliminato', [
                'vettore_id' => $vettore->id,
                'ragione_sociale' => $ragioneSociale,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('vettori.index')
                           ->with('success', "Vettore \"{$ragioneSociale}\" eliminato con successo.");
                           
        } catch (\Exception $e) {
            Log::error('Errore eliminazione vettore', [
                'vettore_id' => $vettore->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Errore durante l\'eliminazione del vettore.');
        }
    }

    /**
     * Ricerca AJAX enterprise per dropdown/autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        $query = Vettore::attivi();
        
        if ($request->filled('q')) {
            $query->ricerca($request->q);
        }
        
        $vettori = $query->select('id', 'ragione_sociale', 'nome_commerciale', 'codice_vettore', 'tipo_vettore', 'valutazione')
                         ->limit(20)
                         ->get()
                         ->map(function ($vettore) {
                             return [
                                 'id' => $vettore->id,
                                 'text' => $vettore->nome_completo,
                                 'subtitle' => "Codice: {$vettore->codice_vettore} | Tipo: " . ucfirst($vettore->tipo_vettore),
                                 'data' => [
                                     'codice' => $vettore->codice_vettore,
                                     'tipo' => $vettore->tipo_vettore,
                                     'valutazione' => $vettore->valutazione
                                 ]
                             ];
                         });
        
        return response()->json($vettori);
    }

    /**
     * Calcola costo spedizione AJAX
     */
    public function calcolaCosto(Request $request): JsonResponse
    {
        try {
            $vettore = Vettore::findOrFail($request->vettore_id);
            $peso = (float) $request->peso;
            $servizio = $request->servizio ?? 'standard';
            $importoOrdine = (float) ($request->importo_ordine ?? 0);
            
            // Verifica se spedizione gratuita
            if ($vettore->isSpedizioneGratuita($importoOrdine)) {
                return response()->json([
                    'success' => true,
                    'costo' => 0,
                    'gratuita' => true,
                    'messaggio' => 'Spedizione gratuita'
                ]);
            }
            
            $costo = $vettore->calcolaCostoSpedizione($peso, $servizio);
            
            if ($costo === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile calcolare il costo per questo vettore'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'costo' => $costo,
                'gratuita' => false,
                'dettagli' => [
                    'peso' => $peso,
                    'servizio' => $servizio,
                    'costo_base_kg' => $vettore->costo_base_kg,
                    'tempo_consegna' => $servizio === 'express' 
                        ? $vettore->tempo_consegna_express 
                        : $vettore->tempo_consegna_standard
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel calcolo del costo'
            ], 500);
        }
    }

    /**
     * Aggiorna verifica dati vettore
     */
    public function verificaDati(Vettore $vettore): JsonResponse
    {
        try {
            $vettore->aggiornaVerifica();
            
            return response()->json([
                'success' => true,
                'message' => 'Verifica dati aggiornata con successo',
                'data' => [
                    'ultima_verifica' => $vettore->ultima_verifica_dati->format('d/m/Y H:i')
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
     * Export CSV vettori
     */
    public function exportCsv(Request $request)
    {
        $query = Vettore::query();
        
        // Applica stessi filtri dell'index
        if ($request->filled('search')) {
            $query->ricerca($request->search);
        }
        
        if ($request->filled('tipo_vettore')) {
            $query->tipoVettore($request->tipo_vettore);
        }
        
        $vettori = $query->get();
        
        $filename = 'vettori_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Headers per download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Header CSV
        fputcsv($handle, [
            'ID', 'Codice Vettore', 'Ragione Sociale', 'Nome Commerciale', 'Tipo Vettore', 
            'Classe', 'Email', 'Telefono', 'Città', 'Valutazione', 'Attivo', 'Creato il'
        ]);
        
        // Dati vettori (sicuri per export)
        foreach ($vettori as $vettore) {
            fputcsv($handle, [
                $vettore->id,
                $vettore->codice_vettore,
                $vettore->ragione_sociale,
                $vettore->nome_commerciale,
                $vettore->tipo_vettore,
                $vettore->classe_vettore,
                $vettore->email,
                $vettore->telefono,
                $vettore->citta,
                $vettore->valutazione,
                $vettore->attivo ? 'Sì' : 'No',
                $vettore->created_at->format('d/m/Y H:i')
            ]);
        }
        
        fclose($handle);
        exit;
    }

    /**
     * Validazioni enterprise per vettore
     */
    private function validateVettore(Request $request, ?int $excludeId = null): \Illuminate\Validation\Validator
    {
        $rules = [
            // Dati identificativi
            'ragione_sociale' => 'required|string|max:255',
            'nome_commerciale' => 'nullable|string|max:255',
            'codice_vettore' => [
                'required',
                'string',
                'max:10',
                'unique:vettori,codice_vettore' . ($excludeId ? ",{$excludeId}" : '')
            ],
            'tipo_soggetto' => 'required|in:persona_fisica,persona_giuridica,ente_pubblico',
            
            // Persona fisica
            'nome' => 'nullable|string|max:100',
            'cognome' => 'nullable|string|max:100',
            'data_nascita' => 'nullable|date|before:today',
            'genere' => 'nullable|in:M,F,altro',
            
            // Dati fiscali con validazioni custom
            'codice_fiscale' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]{11,16}$/',
                'unique:vettori,codice_fiscale' . ($excludeId ? ",{$excludeId}" : '')
            ],
            'partita_iva' => [
                'nullable',
                'string',
                'regex:/^[0-9]{11}$/',
                'unique:vettori,partita_iva' . ($excludeId ? ",{$excludeId}" : '')
            ],
            'regime_fiscale' => 'nullable|string|max:5',
            'split_payment' => 'nullable|boolean',
            
            // Contatti
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'telefono_mobile' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'sito_web' => 'nullable|url|max:500',
            
            // Indirizzo sede legale
            'indirizzo' => 'nullable|string|max:500',
            'citta' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|size:2',
            'cap' => 'nullable|string|size:5',
            'paese' => 'required|string|size:2',
            
            // Indirizzo operativo
            'indirizzo_operativo' => 'nullable|string|max:500',
            'citta_operativa' => 'nullable|string|max:100',
            'provincia_operativa' => 'nullable|string|size:2',
            'cap_operativo' => 'nullable|string|size:5',
            'paese_operativo' => 'nullable|string|size:2',
            
            // Trasporto
            'tipo_vettore' => 'required|in:corriere_espresso,trasporto_standard,trasporto_pesante,logistica_integrata,posta_ordinaria',
            
            // Contratto
            'costo_base_kg' => 'nullable|numeric|min:0|max:999.9999',
            'costo_minimo_spedizione' => 'nullable|numeric|min:0|max:99999.99',
            'soglia_franco' => 'nullable|numeric|min:0|max:9999999.99',
            
            // Performance
            'tempo_consegna_standard' => 'nullable|integer|min:1|max:30',
            'tempo_consegna_express' => 'nullable|integer|min:1|max:10',
            'percentuale_puntualita' => 'nullable|numeric|min:0|max:100',
            
            // Bancari
            'iban' => 'nullable|string|max:34',
            'modalita_pagamento' => 'required|in:bonifico,rid,contanti,assegno,carta',
            'giorni_pagamento' => 'required|integer|min:0|max:365',
            
            // Classificazione
            'classe_vettore' => 'required|in:premium,standard,economico,occasionale',
            'valutazione' => 'nullable|numeric|min:1|max:5',
            
            // Assicurazione
            'numero_polizza_assicurativa' => 'nullable|string|max:100',
            'massimale_assicurazione' => 'nullable|numeric|min:0|max:999999999.99',
            'scadenza_polizza' => 'nullable|date|after:today',
            'compagnia_assicurativa' => 'nullable|string|max:255',
            
            // Autorizzazioni
            'numero_iscrizione_albo' => 'nullable|string|max:100',
            'licenza_trasporti' => 'nullable|string|max:100',
            
            // Privacy
            'consenso_dati' => 'required|boolean',
            'consenso_marketing' => 'nullable|boolean'
        ];
        
        $messages = [
            'ragione_sociale.required' => 'La ragione sociale è obbligatoria.',
            'codice_vettore.required' => 'Il codice vettore è obbligatorio.',
            'codice_vettore.unique' => 'Questo codice vettore è già in uso.',
            'codice_fiscale.required' => 'Il codice fiscale è obbligatorio.',
            'codice_fiscale.regex' => 'Il codice fiscale non è in formato valido.',
            'codice_fiscale.unique' => 'Questo codice fiscale è già registrato.',
            'partita_iva.regex' => 'La partita IVA deve essere di 11 cifre numeriche.',
            'partita_iva.unique' => 'Questa partita IVA è già registrata.',
            'email.email' => 'Inserire un indirizzo email valido.',
            'pec.email' => 'Inserire un indirizzo PEC valido.',
            'tipo_vettore.required' => 'Il tipo di vettore è obbligatorio.',
            'classe_vettore.required' => 'La classe del vettore è obbligatoria.',
            'consenso_dati.required' => 'Il consenso al trattamento dati è obbligatorio.'
        ];
        
        return Validator::make($request->all(), $rules, $messages);
    }
}