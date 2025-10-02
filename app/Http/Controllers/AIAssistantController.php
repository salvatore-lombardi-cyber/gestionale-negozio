<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIAssistantService;

class AIAssistantController extends Controller
{
    private $aiService;

    public function __construct(AIAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $stats = [
            'products' => \App\Models\Prodotto::count(),
            'customers' => \App\Models\Anagrafica::clienti()->count(), 
            'sales' => \App\Models\Vendita::count(),
            'low_stock' => 0
        ];

        return view('ai.index', compact('stats'));
    }

    public function ask(Request $request)
{
    // Ottieni dati reali dal database
    $context = [
        'prodotti_totali' => \App\Models\Prodotto::count(),
        'clienti_totali' => \App\Models\Anagrafica::clienti()->count(),
        'vendite_totali' => \App\Models\Vendita::count(),
        'vendite_oggi' => \App\Models\Vendita::whereDate('created_at', today())->count(),
        'scorte_basse' => \App\Models\Magazzino::whereRaw('quantita <= scorta_minima')->count()
    ];
    
    $response = $this->aiService->ask($request->input('question'), $context);
    return response()->json($response);
}

    public function analyze(Request $request)
    {
        return response()->json(['success' => true, 'response' => 'Analisi completata']);
    }

    public function status()
    {
        return response()->json(['success' => true, 'ai_available' => true]);
    }

    public function suggestions()
    {
        return response()->json([
            'success' => true,
            'suggestions' => ['general' => ['Come vanno le vendite?']]
        ]);
    }

    public function askProducts(Request $request) { return $this->ask($request); }
    public function askCustomers(Request $request) { return $this->ask($request); }
    public function askSales(Request $request) { return $this->ask($request); }
}