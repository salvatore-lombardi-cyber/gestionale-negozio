<?php

namespace App\Http\Controllers;

use App\Models\Ddt;
use App\Models\Vendita;
use App\Models\Anagrafica;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class DdtController extends Controller
{
    public function index()
    {
        $ddts = Ddt::with(['vendita', 'cliente'])->orderBy('created_at', 'desc')->get();
        
        return view('ddts.index', compact('ddts'));
    }
    
    public function create(Request $request)
    {
        if ($request->has('vendita_id')) {
            // Mostra il form per creare DDT per una vendita specifica
            $vendita = Vendita::with(['cliente', 'dettagli.prodotto'])->findOrFail($request->vendita_id);
            return view('ddts.form', compact('vendita'));
        }
        
        // Mostra la lista delle vendite senza DDT
        $vendite = Vendita::with('cliente')->whereDoesntHave('ddt')->get();
        return view('ddts.create', compact('vendite'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'vendita_id' => 'required|exists:venditas,id',
            'data_ddt' => 'required|date',
            'destinatario_nome' => 'required|string|max:255',
            'destinatario_cognome' => 'required|string|max:255',
            'destinatario_indirizzo' => 'required|string|max:255',
            'destinatario_cap' => 'required|string|max:10',
            'destinatario_citta' => 'required|string|max:255',
            'causale' => 'required|string',
        ]);
        
        $vendita = Vendita::findOrFail($request->vendita_id);
        
        $ddt = Ddt::create([
            'numero_ddt' => Ddt::generaNumeroDdt(),
            'data_ddt' => $request->data_ddt,
            'vendita_id' => $vendita->id,
            'cliente_id' => $vendita->cliente_id ?? null, // ← FIX: Permetti NULL
            'destinatario_nome' => $request->destinatario_nome,
            'destinatario_cognome' => $request->destinatario_cognome,
            'destinatario_indirizzo' => $request->destinatario_indirizzo,
            'destinatario_cap' => $request->destinatario_cap,
            'destinatario_citta' => $request->destinatario_citta,
            'causale' => $request->causale,
            'trasportatore' => $request->trasportatore,
            'note' => $request->note,
            'stato' => 'bozza',
        ]);
        
        return redirect()->route('ddts.index')->with('success', 'DDT creato con successo!');
    }
    
    public function show(Ddt $ddt)
    {
        $ddt->load(['vendita.dettagli.prodotto', 'cliente']);
        
        return view('ddts.show', compact('ddt'));
    }
    public function downloadPdf(Ddt $ddt)
    {
        $ddt->load(['vendita.dettagli.prodotto', 'cliente']);
        
        $pdf = Pdf::loadView('pdfs.ddt', compact('ddt'));
        
        return $pdf->download('DDT-' . $ddt->numero_ddt . '.pdf');
    }
    
    public function sendEmail(Ddt $ddt)
    {
        $ddt->load(['vendita.dettagli.prodotto', 'cliente']);
        
        // Verifica che il cliente abbia un'email
        if (!$ddt->cliente->email) {
            return redirect()->back()->with('error', 'Il cliente non ha un indirizzo email configurato.');
        }
        
        try {
            Mail::to($ddt->cliente->email)->send(new \App\Mail\DdtMail($ddt));
            
            return redirect()->back()->with('success', 'DDT inviato via email a ' . $ddt->cliente->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Errore nell\'invio email: ' . $e->getMessage());
        }
    }
}