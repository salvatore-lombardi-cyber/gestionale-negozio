<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Services\QRCodeService;
use App\Services\LabelCodeService;
use Illuminate\Http\Request;

class ProdottoController extends Controller
{
    protected $qrService;
    protected $labelService;

    public function __construct(QRCodeService $qrService, LabelCodeService $labelService)
    {
        $this->qrService = $qrService;
        $this->labelService = $labelService;
    }

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
        
        // Genera automaticamente codice etichetta
        $this->labelService->generateProductCode($prodotto);
        
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
                    $magazzino = \App\Models\Magazzino::create([
                        'prodotto_id' => $prodotto->id,
                        'taglia' => $variante['taglia'],
                        'colore' => $variante['colore'],
                        'quantita' => $variante['quantita'],
                        'scorta_minima' => $scortaMinima,
                    ]);
                    
                    // Genera automaticamente codice variante
                    $this->labelService->generateVariantCode($magazzino);
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

    // Genera QR Code per prodotto
    public function generateQR(Request $request, $id)
    {
        $prodotto = Prodotto::findOrFail($id);
        
        try {
            // Assicura che abbia un codice etichetta
            $this->labelService->generateProductCode($prodotto);
            
            // Genera QR Code
            $qrPath = $this->qrService->generateProductQR($prodotto);
            
            return response()->json([
                'success' => true,
                'message' => 'QR Code generato con successo',
                'qr_url' => $prodotto->getQRCodeUrl(),
                'code' => $prodotto->codice_etichetta
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella generazione del QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    // Genera tutti i QR Code (prodotto + varianti)
    public function generateAllQR(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        try {
            $results = [];
            
            // QR Code prodotto principale
            $this->labelService->generateProductCode($prodotto);
            $results['product'] = [
                'path' => $this->qrService->generateProductQR($prodotto),
                'url' => $prodotto->getQRCodeUrl(),
                'code' => $prodotto->codice_etichetta
            ];
            
            // QR Code varianti
            $results['variants'] = [];
            foreach ($prodotto->magazzino as $magazzino) {
                $this->labelService->generateVariantCode($magazzino);
                $variantPath = $this->qrService->generateVariantQR($magazzino);
                
                $results['variants'][] = [
                    'id' => $magazzino->id,
                    'taglia' => $magazzino->taglia,
                    'colore' => $magazzino->colore,
                    'path' => $variantPath,
                    'url' => $magazzino->getVariantQRUrl(),
                    'code' => $magazzino->codice_variante
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tutti i QR Code generati con successo',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella generazione dei QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    // Pagina per stampare etichette
    public function printLabels(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        // Assicura che tutti i codici e QR Code esistano
        $this->labelService->generateProductCode($prodotto);
        
        foreach ($prodotto->magazzino as $magazzino) {
            $this->labelService->generateVariantCode($magazzino);
            
            if (!$magazzino->hasVariantQR()) {
                $this->qrService->generateVariantQR($magazzino);
            }
        }
        
        return view('prodotti.labels', compact('prodotto'));
    }
}