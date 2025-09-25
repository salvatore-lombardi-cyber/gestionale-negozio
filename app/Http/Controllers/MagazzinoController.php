<?php

namespace App\Http\Controllers;

use App\Models\Magazzino;
use App\Models\Anagrafica;
use Illuminate\Http\Request;

class MagazzinoController extends Controller
{
   public function index()
{
    // Raggruppiamo per articolo e calcoliamo totali
    $prodotti = Anagrafica::where('tipo', 'articolo')
        ->with(['magazzino' => function($query) {
            $query->orderBy('taglia')->orderBy('colore');
        }])
        ->whereHas('magazzino')
        ->orderBy('nome')
        ->get();

    // Calcoliamo le statistiche per ogni prodotto
    $prodottiConStatistiche = $prodotti->map(function($prodotto) {
        $magazzino = $prodotto->magazzino;
        
        return (object)[
            'prodotto' => $prodotto,
            'totale_quantita' => $magazzino->sum('quantita'),
            'varianti_totali' => $magazzino->count(),
            'varianti_disponibili' => $magazzino->where('quantita', '>', 0)->count(),
            'varianti_esaurite' => $magazzino->where('quantita', 0)->count(),
            'varianti_scorte_basse' => $magazzino->filter(function($item) {
                return $item->quantita <= $item->scorta_minima && $item->quantita > 0;
            })->count(),
            'stato_generale' => $magazzino->where('quantita', 0)->count() == $magazzino->count() ? 'esaurito' : 
                              ($magazzino->filter(function($item) { return $item->quantita <= $item->scorta_minima; })->count() > 0 ? 'attenzione' : 'ok')
        ];
    });

    return view('magazzino.index', compact('prodottiConStatistiche'));
}
    
    public function create()
    {
        $prodotti = Prodotto::where('attivo', true)->orderBy('nome')->get();
        return view('magazzino.create', compact('prodotti'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'prodotto_id' => 'required|exists:prodottos,id',
            'taglia' => 'required|string',
            'colore' => 'required|string',
            'quantita' => 'required|integer|min:0',
            'scorta_minima' => 'required|integer|min:0',
        ]);
        
        Magazzino::create($request->all());
        
        return redirect()->route('magazzino.index')
        ->with('success', 'Scorta aggiunta con successo!');
    }
    
    public function show(Magazzino $magazzino)
    {
        return view('magazzino.show', compact('magazzino'));
    }
    
    public function edit(Magazzino $magazzino)
    {
        $prodotti = Prodotto::where('attivo', true)->orderBy('nome')->get();
        return view('magazzino.edit', compact('magazzino', 'prodotti'));
    }
    
    public function update(Request $request, Magazzino $magazzino)
    {
        $request->validate([
            'prodotto_id' => 'required|exists:prodottos,id',
            'taglia' => 'required|string',
            'colore' => 'required|string',
            'quantita' => 'required|integer|min:0',
            'scorta_minima' => 'required|integer|min:0',
        ]);
        
        $magazzino->update($request->all());
        
        return redirect()->route('magazzino.index')
        ->with('success', 'Scorta aggiornata con successo!');
    }
    
    public function destroy(Magazzino $magazzino)
    {
        $magazzino->delete();
        
        return redirect()->route('magazzino.index')
        ->with('success', 'Scorta eliminata con successo!');
    }
    public function caricamentoMultiplo()
    {
        $prodotti = Prodotto::where('attivo', true)->orderBy('nome')->get();
        return view('magazzino.caricamento-multiplo', compact('prodotti'));
    }
    
    public function salvaMultiplo(Request $request)
    {
        $request->validate([
            'prodotto_id' => 'required|exists:prodottos,id',
            'varianti' => 'required|array|min:1',
            'varianti.*.taglia' => 'required|string',
            'varianti.*.colore' => 'required|string',
            'varianti.*.quantita' => 'required|integer|min:0',
            'scorta_minima_globale' => 'required|integer|min:0',
        ]);
        
        $salvate = 0;
        $errori = [];
        
        foreach ($request->varianti as $index => $variante) {
            // Controlla se esiste già questa combinazione
            $esistente = Magazzino::where('prodotto_id', $request->prodotto_id)
            ->where('taglia', $variante['taglia'])
            ->where('colore', $variante['colore'])
            ->first();
            
            if ($esistente) {
                $errori[] = "Combinazione {$variante['taglia']} - {$variante['colore']} già esistente";
                continue;
            }
            
            Magazzino::create([
                'prodotto_id' => $request->prodotto_id,
                'taglia' => $variante['taglia'],
                'colore' => $variante['colore'],
                'quantita' => $variante['quantita'],
                'scorta_minima' => $request->scorta_minima_globale,
            ]);
            
            $salvate++;
        }
        
        $messaggio = "Salvate {$salvate} varianti con successo!";
        if (!empty($errori)) {
            $messaggio .= " Errori: " . implode(', ', $errori);
        }
        
        return redirect()->route('magazzino.index')->with('success', $messaggio);
    }
    public function dettaglioProdotto($prodotto)
{
    $prodotto = Anagrafica::where('tipo', 'articolo')->with('magazzino')->findOrFail($prodotto);
    
    return view('magazzino.dettaglio-prodotto', compact('prodotto'));
}
}