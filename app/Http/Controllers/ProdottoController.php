<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class ProdottoController extends Controller
{
    // Mostra tutti i prodotti
    public function index()
    {
        $prodotti = Prodotto::where('attivo', true)->get();
        return view('prodotti.index', compact('prodotti'));
    }
    
    // Mostra form per creare nuovo prodotto
    public function create()
    {
        return view('prodotti.create');
    }
    
    // Salva nuovo prodotto
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'prezzo' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'codice_prodotto' => 'required|string|unique:prodottos,codice_prodotto',
            'varianti' => 'array',
            'varianti.*.taglia' => 'required_with:varianti|string',
            'varianti.*.colore' => 'required_with:varianti|string',
            'varianti.*.quantita' => 'required_with:varianti|integer|min:0',
        ]);
        
        // Crea il prodotto
        $prodotto = Prodotto::create($request->only([
            'nome', 'descrizione', 'prezzo', 'categoria', 'brand', 'codice_prodotto', 'attivo'
        ]));
        
        // Crea le varianti di magazzino se presenti
        if ($request->has('varianti') && is_array($request->varianti)) {
            $scortaMinima = $request->input('scorta_minima_globale', 5);
            
            foreach ($request->varianti as $variante) {
                // Controlla se la combinazione taglia/colore esiste già per questo prodotto
                $esistente = \App\Models\Magazzino::where('prodotto_id', $prodotto->id)
                ->where('taglia', $variante['taglia'])
                ->where('colore', $variante['colore'])
                ->first();
                
                if (!$esistente) {
                    \App\Models\Magazzino::create([
                        'prodotto_id' => $prodotto->id,
                        'taglia' => $variante['taglia'],
                        'colore' => $variante['colore'],
                        'quantita' => $variante['quantita'],
                        'scorta_minima' => $scortaMinima,
                    ]);
                }
            }
        }
        
        return redirect()->route('prodotti.index')
        ->with('success', 'Prodotto creato con successo! ' . 
        (isset($request->varianti) ? count($request->varianti) . ' varianti aggiunte al magazzino.' : ''));
    }
    
    // Mostra singolo prodotto
    public function show(Prodotto $prodotto)
    {
        return view('prodotti.show', compact('prodotto'));
    }
    
    // Mostra form per modificare prodotto
    public function edit(Prodotto $prodotto)
    {
        return view('prodotti.edit', compact('prodotto'));
    }
    
    // Salva modifiche al prodotto
    public function update(Request $request, Prodotto $prodotto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'prezzo' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'codice_prodotto' => 'required|string|unique:prodottos,codice_prodotto,' . $prodotto->id,
        ]);
        
        $prodotto->update($request->all());
        
        return redirect()->route('prodotti.index')
        ->with('success', 'Prodotto aggiornato con successo!');
    }
    
    // Elimina prodotto
    public function destroy(Prodotto $prodotto)
    {
        $prodotto->update(['attivo' => false]);
        
        return redirect()->route('prodotti.index')
        ->with('success', 'Prodotto disattivato con successo!');
    }
}