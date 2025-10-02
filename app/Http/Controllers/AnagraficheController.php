<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anagrafica;

class AnagraficheController extends Controller
{
    public function index()
    {
        // Statistiche anagrafiche enterprise
        $stats = [
            'clienti' => Anagrafica::clienti()->count(),
            'fornitori' => Anagrafica::fornitori()->count(),
            'vettori' => Anagrafica::vettori()->count(),
            'agenti' => Anagrafica::agenti()->count()
        ];
        
        return view('anagrafiche.index', compact('stats'));
    }
}
