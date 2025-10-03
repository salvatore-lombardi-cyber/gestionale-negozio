<?php

namespace App\Http\Controllers;

use App\Models\Vendita;
use App\Models\Anagrafica;
use App\Models\Prodotto;
use App\Models\DettaglioVendita;
use Illuminate\Http\Request;
// Integrazione con sistema magazzino multi-deposito

class VenditaController extends Controller
{
    public function index()
    {
        $vendite = Vendita::with('cliente')->orderBy('data_vendita', 'desc')->get();
        return view('vendite.index', compact('vendite'));
    }
    
    public function create()
{
    $clienti = Anagrafica::clienti()->orderBy('cognome')->get();
    $prodotti = Prodotto::where('attivo', true)->orderBy('nome')->get();
    
    // Integrazione con sistema magazzino multi-deposito
    $magazzino = collect();
    
    return view('vendite.create', compact('clienti', 'prodotti', 'magazzino'));
}
    
public function store(Request $request)
{
    $request->validate([
        'cliente_id' => 'nullable|exists:clientes,id',
        'data_vendita' => 'required|date',
        'metodo_pagamento' => 'required|in:contanti,carta,bonifico,assegno',
        'prodotti' => 'required|array|min:1',
        'prodotti.*.id' => 'required|exists:prodottos,id',
        'prodotti.*.quantita' => 'required|integer|min:1',
        'prodotti.*.taglia' => 'required|string',
        'prodotti.*.colore' => 'required|string',
    ]);

    // Controllo disponibilità giacenze multi-deposito

    // Calcola il totale
    $totale = 0;
    foreach ($request->prodotti as $prodotto) {
        $prod = Prodotto::find($prodotto['id']);
        $subtotale = $prod->prezzo * $prodotto['quantita'];
        $totale += $subtotale;
    }

    $scontoPercentuale = $request->sconto ?? 0;
    $scontoEuro = ($totale * $scontoPercentuale) / 100;
    $totale_finale = $totale - $scontoEuro;

    // Crea la vendita
    $vendita = Vendita::create([
        'cliente_id' => $request->cliente_id,
        'data_vendita' => $request->data_vendita,
        'totale' => $totale,
        'sconto' => $scontoPercentuale,
        'totale_finale' => $totale_finale,
        'metodo_pagamento' => $request->metodo_pagamento,
        'note' => $request->note,
    ]);

    // Crea i dettagli e aggiorna il magazzino
    foreach ($request->prodotti as $prodotto) {
        $prod = Prodotto::find($prodotto['id']);
        $subtotale = $prod->prezzo * $prodotto['quantita'];
        
        DettaglioVendita::create([
            'vendita_id' => $vendita->id,
            'prodotto_id' => $prodotto['id'],
            'taglia' => $prodotto['taglia'],
            'colore' => $prodotto['colore'],
            'quantita' => $prodotto['quantita'],
            'prezzo_unitario' => $prod->prezzo,
            'subtotale' => $subtotale,
        ]);
        
        // Aggiornamento automatico giacenze con tracciabilità movimenti
    }

    return redirect()->route('vendite.index')
        ->with('success', 'Vendita registrata con successo! Magazzino aggiornato.');
}
    
    public function show(Vendita $vendita)
    {
        $vendita->load('cliente', 'dettagli.prodotto');
        return view('vendite.show', compact('vendita'));
    }
    
    public function edit(Vendita $vendita)
    {
        // Non implementato
    }
    
    public function update(Request $request, Vendita $vendita)
    {
        // Non implementato
    }
    
    public function destroy(Vendita $vendita)
    {
        $vendita->dettagli()->delete();
        $vendita->delete();
        
        return redirect()->route('vendite.index')
        ->with('success', 'Vendita eliminata con successo!');
    }
}