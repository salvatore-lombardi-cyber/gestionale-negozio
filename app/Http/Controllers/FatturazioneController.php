<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendita;
use App\Models\Anagrafica;
// use App\Models\Prodotto; // Sostituito con Anagrafica
// TODO: Integrazione con nuovo modulo magazzino
use Barryvdh\DomPDF\Facade\Pdf;

class FatturazioneController extends Controller
{
    public function index()
    {
        // Statistiche per dashboard
        $stats = [
            'fatture_emesse' => 0, // Placeholder per future fatture
            'fatture_pagate' => 0,
            'fatture_scadute' => 0,
            'fatture_ricevute' => 0,
            'fatturato_mensile' => Vendita::whereMonth('created_at', now()->month)->sum('totale'),
            'clienti_attivi' => Anagrafica::clienti()->count()
        ];

        return view('fatturazione.index', compact('stats'));
    }

    public function create()
    {
        // Recupera clienti e prodotti per il form
        $clienti = Anagrafica::clienti()->orderBy('nome')->get();
        
        // Recupera articoli (sostituisce prodotti)
        $prodotti = Anagrafica::articoli()
                           ->orderBy('nome')
                           ->get();
        
        // Genera numero fattura progressivo
        $ultimaFattura = Vendita::where('tipo_documento', 'fattura')
                                ->orderBy('numero_documento', 'desc')
                                ->first();
        
        $numeroFattura = $ultimaFattura ? ($ultimaFattura->numero_documento + 1) : 1;
        
        // Prepara array JSON per JavaScript evitando problemi di parsing Blade
        $prodottiJson = $prodotti->map(function($p) {
            return [
                'id' => $p->id,
                'nome' => $p->nome,
                'prezzo' => $p->prezzo_vendita ?? 0,
                'quantita_disponibile' => $p->scorta_minima ?? 0
            ];
        });
        
        return view('fatturazione.create', compact('clienti', 'prodotti', 'numeroFattura', 'prodottiJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:definitivo,potenziale',
            'numero_documento' => 'required|integer|unique:venditas,numero_documento',
            'data_documento' => 'required|date',
            'cliente_id' => 'nullable|exists:clientes,id',
            'indirizzo' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:2',
            'citta' => 'nullable|string|max:100',
            'cap' => 'nullable|string|max:5',
            'paese' => 'nullable|string|max:100',
            'codice_fiscale' => 'nullable|string|max:16',
            'partita_iva' => 'nullable|string|max:11',
            'partita_iva_cee' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'telefax' => 'nullable|string|max:20',
            'contatto' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'www' => 'nullable|url|max:255',
            'pec' => 'nullable|email|max:100',
            'tessera' => 'nullable|string|max:50',
            'codice_sdi' => 'nullable|string|max:7',
            'pubblica_amministrazione' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Crea la fattura con tutti i dati
        $fattura = Vendita::create([
            'cliente_id' => $request->cliente_id,
            'numero_documento' => $request->numero_documento,
            'tipo_documento' => 'fattura',
            'data_documento' => $request->data_documento,
            'subtotale' => 0, // Per ora senza prodotti
            'iva' => 0,
            'totale' => 0,
            'data_vendita' => $request->data_documento,
            'totale_finale' => 0,
            'metodo_pagamento' => 'contanti', // Default
            'prodotti_vendita' => json_encode([
                'tipo' => $request->tipo,
                'dati_cliente' => [
                    'indirizzo' => $request->indirizzo,
                    'provincia' => $request->provincia,
                    'citta' => $request->citta,
                    'cap' => $request->cap,
                    'paese' => $request->paese,
                    'codice_fiscale' => $request->codice_fiscale,
                    'partita_iva' => $request->partita_iva,
                    'partita_iva_cee' => $request->partita_iva_cee,
                    'telefono' => $request->telefono,
                    'telefax' => $request->telefax,
                    'contatto' => $request->contatto,
                    'email' => $request->email,
                    'www' => $request->www,
                    'pec' => $request->pec,
                    'tessera' => $request->tessera,
                    'codice_sdi' => $request->codice_sdi,
                    'pubblica_amministrazione' => $request->has('pubblica_amministrazione')
                ]
            ]),
            'note' => $request->note
        ]);

        return redirect()->route('fatturazione.index')
                        ->with('success', 'Fattura creata con successo!');
    }

    public function show(Vendita $vendita)
    {
        // Verifica che sia una fattura
        if ($vendita->tipo_documento !== 'fattura') {
            abort(404, 'Documento non trovato');
        }
        
        return view('fatturazione.show', compact('vendita'));
    }

    public function edit(Vendita $vendita)
    {
        // Verifica che sia una fattura
        if ($vendita->tipo_documento !== 'fattura') {
            abort(404, 'Documento non trovato');
        }
        
        // Recupera clienti e articoli per il form
        $clienti = Anagrafica::clienti()->orderBy('nome')->get();
        $prodotti = Anagrafica::articoli()
                           ->orderBy('nome')
                           ->get();
        
        // Prepara array JSON per JavaScript
        $prodottiJson = $prodotti->map(function($p) {
            return [
                'id' => $p->id,
                'nome' => $p->nome,
                'prezzo' => $p->prezzo_vendita ?? 0,
                'quantita_disponibile' => $p->scorta_minima ?? 0
            ];
        });
        
        return view('fatturazione.edit', compact('vendita', 'clienti', 'prodotti', 'prodottiJson'));
    }

    public function update(Request $request, Vendita $vendita)
    {
        // Verifica che sia una fattura
        if ($vendita->tipo_documento !== 'fattura') {
            abort(404, 'Documento non trovato');
        }
        
        $request->validate([
            'tipo' => 'required|in:definitivo,potenziale',
            'numero_documento' => 'required|integer|unique:vendite,numero_documento,' . $vendita->id,
            'data_documento' => 'required|date',
            'cliente_id' => 'nullable|exists:clienti,id',
            'indirizzo' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:2',
            'citta' => 'nullable|string|max:100',
            'cap' => 'nullable|string|max:5',
            'paese' => 'nullable|string|max:100',
            'codice_fiscale' => 'nullable|string|max:16',
            'partita_iva' => 'nullable|string|max:11',
            'partita_iva_cee' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'telefax' => 'nullable|string|max:20',
            'contatto' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'www' => 'nullable|url|max:255',
            'pec' => 'nullable|email|max:100',
            'tessera' => 'nullable|string|max:50',
            'codice_sdi' => 'nullable|string|max:7',
            'pubblica_amministrazione' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        // Aggiorna la fattura
        $vendita->update([
            'cliente_id' => $request->cliente_id,
            'numero_documento' => $request->numero_documento,
            'data_documento' => $request->data_documento,
            'prodotti_vendita' => json_encode([
                'tipo' => $request->tipo,
                'dati_cliente' => [
                    'indirizzo' => $request->indirizzo,
                    'provincia' => $request->provincia,
                    'citta' => $request->citta,
                    'cap' => $request->cap,
                    'paese' => $request->paese,
                    'codice_fiscale' => $request->codice_fiscale,
                    'partita_iva' => $request->partita_iva,
                    'partita_iva_cee' => $request->partita_iva_cee,
                    'telefono' => $request->telefono,
                    'telefax' => $request->telefax,
                    'contatto' => $request->contatto,
                    'email' => $request->email,
                    'www' => $request->www,
                    'pec' => $request->pec,
                    'tessera' => $request->tessera,
                    'codice_sdi' => $request->codice_sdi,
                    'pubblica_amministrazione' => $request->has('pubblica_amministrazione')
                ]
            ]),
            'note' => $request->note
        ]);

        return redirect()->route('fatturazione.show', $vendita)
                        ->with('success', 'Fattura aggiornata con successo!');
    }

    public function destroy(Vendita $vendita)
    {
        // Verifica che sia una fattura
        if ($vendita->tipo_documento !== 'fattura') {
            abort(404, 'Documento non trovato');
        }
        
        $vendita->delete();
        
        return redirect()->route('fatturazione.index')
                        ->with('success', 'Fattura eliminata con successo!');
    }

    public function riepilogo(Request $request)
    {
        $dataInizio = $request->get('data_inizio', now()->startOfMonth()->format('Y-m-d'));
        $dataFine = $request->get('data_fine', now()->endOfMonth()->format('Y-m-d'));
        $clienteId = $request->get('cliente_id');
        $stato = $request->get('stato');

        $query = Vendita::where('tipo_documento', 'fattura')
                        ->whereBetween('data_documento', [$dataInizio, $dataFine])
                        ->with('cliente');

        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }

        if ($stato) {
            if ($stato === 'definitivo' || $stato === 'potenziale') {
                $query->whereJsonContains('prodotti_vendita->tipo', $stato);
            }
        }

        $fatture = $query->orderBy('data_documento', 'desc')->get();

        // Statistiche del periodo
        $statistiche = [
            'totale_fatture' => $fatture->count(),
            'fatture_definitive' => $fatture->filter(function($f) {
                $dati = json_decode($f->prodotti_vendita, true);
                return ($dati['tipo'] ?? 'definitivo') === 'definitivo';
            })->count(),
            'fatture_potenziali' => $fatture->filter(function($f) {
                $dati = json_decode($f->prodotti_vendita, true);
                return ($dati['tipo'] ?? 'definitivo') === 'potenziale';
            })->count(),
            'fatturato_totale' => $fatture->sum('totale'),
            'fatturato_definitivo' => $fatture->filter(function($f) {
                $dati = json_decode($f->prodotti_vendita, true);
                return ($dati['tipo'] ?? 'definitivo') === 'definitivo';
            })->sum('totale'),
            'fatturato_potenziale' => $fatture->filter(function($f) {
                $dati = json_decode($f->prodotti_vendita, true);
                return ($dati['tipo'] ?? 'definitivo') === 'potenziale';
            })->sum('totale'),
            'ticket_medio' => $fatture->count() > 0 ? $fatture->avg('totale') : 0
        ];

        // Dati per grafici - fatturato per giorno
        $fatturatoPeriodo = $fatture->groupBy(function($fattura) {
            return $fattura->data_documento->format('Y-m-d');
        })->map(function($gruppo) {
            return $gruppo->sum('totale');
        });

        // Top clienti del periodo
        $topClienti = $fatture->groupBy('cliente_id')
                            ->map(function($gruppo) {
                                return [
                                    'cliente' => $gruppo->first()->cliente,
                                    'fatture' => $gruppo->count(),
                                    'fatturato' => $gruppo->sum('totale')
                                ];
                            })
                            ->sortByDesc('fatturato')
                            ->take(10);

        $clienti = Anagrafica::clienti()->orderBy('nome')->get();

        return view('fatturazione.riepilogo', compact(
            'fatture', 'statistiche', 'fatturatoPeriodo', 'topClienti', 
            'clienti', 'dataInizio', 'dataFine', 'clienteId', 'stato'
        ));
    }

    public function fatture_ricevute()
    {
        // Per ora placeholder - qui andranno le fatture ricevute dai fornitori
        $stats = [
            'fatture_ricevute_totali' => 0,
            'fatture_da_pagare' => 0,
            'fatture_pagate' => 0,
            'totale_da_pagare' => 0
        ];

        return view('fatturazione.fatture_ricevute', compact('stats'));
    }

    public function analytics()
    {
        $annoCorrente = now()->year;
        
        // Fatturato mensile dell'anno corrente
        $fatturateMensili = [];
        for ($mese = 1; $mese <= 12; $mese++) {
            $fatturateMensili[] = Vendita::where('tipo_documento', 'fattura')
                                         ->whereYear('data_documento', $annoCorrente)
                                         ->whereMonth('data_documento', $mese)
                                         ->whereJsonContains('prodotti_vendita->tipo', 'definitivo')
                                         ->sum('totale');
        }

        // Confronto con anno precedente
        $fatturatoAnnoPrecedente = [];
        for ($mese = 1; $mese <= 12; $mese++) {
            $fatturatoAnnoPrecedente[] = Vendita::where('tipo_documento', 'fattura')
                                                ->whereYear('data_documento', $annoCorrente - 1)
                                                ->whereMonth('data_documento', $mese)
                                                ->whereJsonContains('prodotti_vendita->tipo', 'definitivo')
                                                ->sum('totale');
        }

        // Statistiche generali
        $statistiche = [
            'fatturato_anno_corrente' => array_sum($fatturateMensili),
            'fatturato_anno_precedente' => array_sum($fatturatoAnnoPrecedente),
            'crescita_percentuale' => array_sum($fatturatoAnnoPrecedente) > 0 ? 
                ((array_sum($fatturateMensili) - array_sum($fatturatoAnnoPrecedente)) / array_sum($fatturatoAnnoPrecedente)) * 100 : 0,
            'media_mensile' => array_sum($fatturateMensili) / 12,
            'mese_migliore' => array_keys($fatturateMensili, max($fatturateMensili))[0] + 1,
            'valore_mese_migliore' => max($fatturateMensili)
        ];

        return view('fatturazione.analytics', compact(
            'fatturateMensili', 'fatturatoAnnoPrecedente', 'statistiche', 'annoCorrente'
        ));
    }

    public function downloadPdf(Vendita $vendita)
    {
        // Verifica che sia una fattura
        if ($vendita->tipo_documento !== 'fattura') {
            abort(404, 'Documento non trovato');
        }

        // Decodifica i dati del cliente dal JSON
        $datiCliente = json_decode($vendita->prodotti_vendita, true);
        $tipo = $datiCliente['tipo'] ?? 'definitivo';
        $datiClienteAnagrafica = $datiCliente['dati_cliente'] ?? [];

        // Prepara i dati per il PDF
        $data = [
            'fattura' => $vendita,
            'tipo' => $tipo,
            'datiCliente' => $datiClienteAnagrafica,
            'numeroFormattato' => str_pad($vendita->numero_documento, 4, '0', STR_PAD_LEFT),
            'dataEmissione' => $vendita->data_documento ? $vendita->data_documento->format('d/m/Y') : now()->format('d/m/Y'),
            'azienda' => [
                'nome' => 'Finson',
                'indirizzo' => 'Via della Tua Azienda, 123',
                'citta' => '00100 Roma (RM)',
                'partita_iva' => 'IT12345678901',
                'codice_fiscale' => 'ABCDEF12G34H567I',
                'telefono' => '+39 06 123456789',
                'email' => 'info@finson.it',
                'pec' => 'pec@finson.it'
            ]
        ];

        // Genera il PDF
        $pdf = Pdf::loadView('fatturazione.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $nomeFile = "fattura_{$data['numeroFormattato']}_" . ($vendita->data_documento ? $vendita->data_documento->format('Y-m-d') : now()->format('Y-m-d')) . ".pdf";

        return $pdf->download($nomeFile);
    }
}
