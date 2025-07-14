<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\Magazzino;
use App\Services\QRCodeService;
use App\Services\LabelCodeService;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    protected $qrService;
    protected $labelService;

    public function __construct(QRCodeService $qrService, LabelCodeService $labelService)
    {
        $this->qrService = $qrService;
        $this->labelService = $labelService;
    }

    /**
     * Pagina principale gestione etichette
     */
    public function index()
    {
        $prodotti = Prodotto::with('magazzino')
            ->where('attivo', true)
            ->get();

        $stats = [
            'total_products' => $prodotti->count(),
            'products_with_qr' => $prodotti->where('qr_enabled', true)->count(),
            'total_variants' => $prodotti->sum(function($p) { return $p->magazzino->count(); }),
            'labels_printed_today' => 0 // Da implementare con tracking
        ];

        return view('labels.index', compact('prodotti', 'stats'));
    }

    /**
     * Genera codici e QR per singolo prodotto
     */
    public function generateSingle(Request $request, $id)
    {
        $prodotto = Prodotto::findOrFail($id);
        
        try {
            // Genera codice etichetta se non esiste
            $productCode = $this->labelService->generateProductCode($prodotto);
            
            // Genera QR Code se richiesto
            if ($request->get('generate_qr', true)) {
                $qrPath = $this->qrService->generateProductQR($prodotto);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Codici generati con successo',
                'data' => [
                    'product_code' => $productCode,
                    'qr_url' => $prodotto->getQRCodeUrl(),
                    'has_qr' => $prodotto->hasQRCode()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera codici per tutte le varianti di un prodotto
     */
    public function generateVariants(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        try {
            $results = [];
            
            // Assicura che il prodotto abbia un codice
            $this->labelService->generateProductCode($prodotto);
            
            foreach ($prodotto->magazzino as $magazzino) {
                $variantCode = $this->labelService->generateVariantCode($magazzino);
                
                if ($request->get('generate_qr', true)) {
                    $this->qrService->generateVariantQR($magazzino);
                }
                
                $results[] = [
                    'id' => $magazzino->id,
                    'taglia' => $magazzino->taglia,
                    'colore' => $magazzino->colore,
                    'code' => $variantCode,
                    'qr_url' => $magazzino->getVariantQRUrl(),
                    'has_qr' => $magazzino->hasVariantQR()
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Varianti generate con successo',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera tutto per un prodotto (codici + QR)
     */
    public function generateAll(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        try {
            $results = $this->labelService->generateAllCodes($prodotto);
            
            // Genera QR Code se richiesto
            if ($request->get('generate_qr', true)) {
                $this->qrService->generateProductQR($prodotto);
                
                foreach ($prodotto->magazzino as $magazzino) {
                    $this->qrService->generateVariantQR($magazzino);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tutti i codici e QR generati con successo',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview etichette prima della stampa
     */
    public function preview(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        // Assicura che tutti i codici esistano
        $this->labelService->generateProductCode($prodotto);
        
        foreach ($prodotto->magazzino as $magazzino) {
            $this->labelService->generateVariantCode($magazzino);
            
            // Genera QR se non esiste
            if (!$magazzino->hasVariantQR()) {
                $this->qrService->generateVariantQR($magazzino);
            }
        }
        
        $labelType = $request->get('type', 'variant'); // 'product' o 'variant'
        
        return view('labels.preview', compact('prodotto', 'labelType'));
    }

    /**
     * Stampa etichette (versione PDF)
     */
    public function print(Request $request, $id)
    {
        $prodotto = Prodotto::with('magazzino')->findOrFail($id);
        
        $request->validate([
            'variants' => 'array',
            'variants.*' => 'exists:magazzinos,id',
            'copies' => 'integer|min:1|max:10'
        ]);
        
        $selectedVariants = collect($prodotto->magazzino);
        
        if ($request->has('variants')) {
            $selectedVariants = $selectedVariants->whereIn('id', $request->variants);
        }
        
        $copies = $request->get('copies', 1);
        
        return view('labels.print', compact('prodotto', 'selectedVariants', 'copies'));
    }

    /**
     * Scanner di test per verificare QR Code
     */
    public function scanner()
    {
        return view('labels.scanner');
    }

    /**
     * API per decodificare QR Code scansionato
     */
    public function decode(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);
        
        $qrData = $request->qr_code;
        
        try {
            // Prova prima come variante
            $magazzino = $this->qrService->findVariantByQR($qrData);
            
            if ($magazzino) {
                return response()->json([
                    'success' => true,
                    'type' => 'variant',
                    'data' => [
                        'id' => $magazzino->id,
                        'prodotto' => $magazzino->prodotto->nome,
                        'taglia' => $magazzino->taglia,
                        'colore' => $magazzino->colore,
                        'quantita' => $magazzino->quantita,
                        'prezzo' => $magazzino->prodotto->prezzo,
                        'codice' => $magazzino->codice_variante
                    ]
                ]);
            }
            
            // Prova come prodotto
            $prodotto = $this->qrService->findProductByQR($qrData);
            
            if ($prodotto) {
                return response()->json([
                    'success' => true,
                    'type' => 'product',
                    'data' => [
                        'id' => $prodotto->id,
                        'nome' => $prodotto->nome,
                        'categoria' => $prodotto->categoria,
                        'brand' => $prodotto->brand,
                        'prezzo' => $prodotto->prezzo,
                        'codice' => $prodotto->codice_etichetta
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non trovato'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella decodifica: ' . $e->getMessage()
            ], 500);
        }
    }
}