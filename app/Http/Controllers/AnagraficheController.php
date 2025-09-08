<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Fornitore;
use App\Models\Vettore;

class AnagraficheController extends Controller
{
    public function index()
    {
        // Statistiche anagrafiche enterprise
        $stats = [
            'clienti' => Cliente::count(),
            'fornitori' => Fornitore::count(),
            'vettori' => Vettore::count(),
            'agenti' => 0   // Da implementare
        ];
        
        return view('anagrafiche.index', compact('stats'));
    }
}
