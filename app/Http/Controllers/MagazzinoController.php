<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Anagrafica;
use App\Models\MovimentoMagazzino;
use App\Models\CausaleMagazzino;
use App\Models\GiacenzaMagazzino;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MagazzinoController extends Controller
{
    /**
     * Dashboard principale del modulo magazzino
     * Mostra le 4 sezioni principali: Movimenti, Gestione Giacenze, Stato Giacenze, DDT
     */
    public function index()
    {
        // Statistiche per le card della dashboard
        $stats = [
            'movimenti_oggi' => 0, // Implementare conteggio movimenti giornalieri
            'articoli_totali' => 0, // Conteggio articoli in magazzino
            'depositi_attivi' => 0, // Numero depositi configurati
            'ddt_pendenti' => 0, // DDT da evadere
            'scorte_minime' => 0, // Articoli sotto scorta minima
            'valore_magazzino' => 0, // Valorizzazione totale magazzino
        ];

        return view('magazzino.index', compact('stats'));
    }

    /**
     * Sezione Movimenti di Magazzino
     * Gestione entrate, uscite, trasferimenti tra depositi
     */
    public function movimenti(Request $request)
    {
        // Gestisci message di successo da URL parameter
        if ($request->has('success')) {
            session()->flash('success', $request->get('success'));
        }
        
        if ($request->has('error')) {
            session()->flash('error', $request->get('error'));
        }
        // Carica depositi attivi dalle configurazioni
        $depositi = Deposit::orderBy('description')->get();
        
        // Carica articoli, clienti e fornitori dalle anagrafiche
        $articoli = Anagrafica::articoli()->attivi()->orderBy('nome')->get();
        $clienti = Anagrafica::clienti()->attivi()->orderBy('nome')->get();
        $fornitori = Anagrafica::fornitori()->attivi()->orderBy('nome')->get();
        
        // Carica tutte le causali per tutti i dropdown nell'ordine richiesto
        $causali = CausaleMagazzino::attive()
            ->orderByRaw("FIELD(descrizione, 'Acquisto', 'Vendita', 'Trasferimento')")
            ->get();
        
        // Carica movimenti per lo storico con relazioni
        $movimenti = MovimentoMagazzino::with(['prodotto', 'deposito', 'causale', 'cliente', 'fornitore', 'user'])
            ->orderBy('data_movimento', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('magazzino.movimenti.index', compact('depositi', 'articoli', 'clienti', 'fornitori', 'causali', 'movimenti'));
    }

    /**
     * Sezione Gestione Giacenze
     * CRUD articoli, configurazione depositi, gestione varianti
     */
    public function gestione()
    {
        return view('magazzino.gestione.index');
    }

    /**
     * Sezione Stato Giacenze
     * Visualizzazione giacenze, reportistica, valorizzazione
     */
    public function stato()
    {
        return view('magazzino.stato.index');
    }

    /**
     * Sezione DDT (Documenti di Trasporto)
     * Gestione DDT integrata con movimenti magazzino
     */
    public function ddt()
    {
        return view('magazzino.ddt.index');
    }

    /**
     * Esegue un carico di magazzino
     */
    public function eseguiCarico(Request $request)
    {
        $request->validate([
            'causale_id' => 'required|integer',
            'deposito_id' => 'required|exists:depositi,id',
            'cliente_id' => 'nullable|exists:anagrafiche,id',
            'fornitore_id' => 'nullable|exists:anagrafiche,id',
            'articolo_id' => 'required|exists:anagrafiche,id',
            'quantita' => 'required|integer|min:1',
            'data_movimento' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Crea il movimento di carico
            $movimento = MovimentoMagazzino::creaCarico(
                $request->articolo_id,
                $request->deposito_id,
                $request->quantita,
                $request->causale_id,
                $request->data_movimento,
                [
                    'cliente_id' => $request->cliente_id,
                    'fornitore_id' => $request->fornitore_id
                ]
            );

            DB::commit();

            Log::info('Carico magazzino eseguito', [
                'movimento_id' => $movimento->id,
                'articolo_id' => $request->articolo_id,
                'quantita' => $request->quantita,
                'deposito_id' => $request->deposito_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Carico registrato con successo',
                'movimento_id' => $movimento->id,
                'redirect' => route('magazzino.movimenti')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Errore durante carico magazzino', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il carico: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esegue uno scarico di magazzino
     */
    public function eseguiScarico(Request $request)
    {
        $request->validate([
            'causale_id' => 'required|integer',
            'deposito_id' => 'required|exists:depositi,id',
            'cliente_id' => 'nullable|exists:anagrafiche,id',
            'fornitore_id' => 'nullable|exists:anagrafiche,id',
            'articolo_id' => 'required|exists:anagrafiche,id',
            'quantita' => 'required|integer|min:1',
            'data_movimento' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Verifica disponibilità giacenza
            $giacenza = GiacenzaMagazzino::where('prodotto_id', $request->articolo_id)
                ->where('deposito_id', $request->deposito_id)
                ->first();

            if (!$giacenza || $giacenza->quantita_attuale < $request->quantita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantità non disponibile in magazzino'
                ], 400);
            }

            // Crea il movimento di scarico
            $movimento = MovimentoMagazzino::creaScarico(
                $request->articolo_id,
                $request->deposito_id,
                $request->quantita,
                $request->causale_id,
                $request->data_movimento,
                [
                    'cliente_id' => $request->cliente_id,
                    'fornitore_id' => $request->fornitore_id
                ]
            );

            DB::commit();

            Log::info('Scarico magazzino eseguito', [
                'movimento_id' => $movimento->id,
                'articolo_id' => $request->articolo_id,
                'quantita' => $request->quantita,
                'deposito_id' => $request->deposito_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scarico registrato con successo',
                'movimento_id' => $movimento->id,
                'redirect' => route('magazzino.movimenti')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Errore durante scarico magazzino', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante lo scarico: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esegue un trasferimento tra depositi
     */
    public function eseguiTrasferimento(Request $request)
    {
        $request->validate([
            'causale_id' => 'required|integer',
            'deposito_sorgente_id' => 'required|exists:depositi,id',
            'deposito_destinazione_id' => 'required|exists:depositi,id|different:deposito_sorgente_id',
            'articolo_id' => 'required|exists:anagrafiche,id',
            'quantita' => 'required|integer|min:1',
            'data_movimento' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Verifica disponibilità nel deposito sorgente
            $giacenza = GiacenzaMagazzino::where('prodotto_id', $request->articolo_id)
                ->where('deposito_id', $request->deposito_sorgente_id)
                ->first();

            if (!$giacenza || $giacenza->quantita_attuale < $request->quantita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantità non disponibile nel deposito sorgente'
                ], 400);
            }

            // Crea il movimento di trasferimento
            $movimenti = MovimentoMagazzino::creaTrasferimento(
                $request->articolo_id,
                $request->deposito_sorgente_id,
                $request->deposito_destinazione_id,
                $request->quantita,
                $request->causale_id,
                $request->data_movimento,
                []
            );

            DB::commit();

            Log::info('Trasferimento magazzino eseguito', [
                'movimento_uscita_id' => $movimenti[0]->id,
                'movimento_ingresso_id' => $movimenti[1]->id,
                'articolo_id' => $request->articolo_id,
                'quantita' => $request->quantita,
                'da_deposito' => $request->deposito_sorgente_id,
                'a_deposito' => $request->deposito_destinazione_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trasferimento registrato con successo',
                'movimento_uscita_id' => $movimenti[0]->id,
                'movimento_ingresso_id' => $movimenti[1]->id,
                'redirect' => route('magazzino.movimenti')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Errore durante trasferimento magazzino', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il trasferimento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera PDF per singolo movimento di magazzino
     */
    public function movimentoPdf($id)
    {
        try {
            $movimento = MovimentoMagazzino::with(['prodotto', 'deposito', 'causale', 'cliente', 'fornitore', 'user'])
                ->findOrFail($id);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('magazzino.pdf.movimento', compact('movimento'));
            
            $tipoMovimento = ucfirst($movimento->tipo_movimento);
            $dataMovimento = $movimento->data_movimento ? $movimento->data_movimento->format('Y-m-d') : now()->format('Y-m-d');
            $filename = "movimento_{$tipoMovimento}_{$movimento->id}_{$dataMovimento}.pdf";
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Errore generazione PDF movimento', [
                'movimento_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('magazzino.movimenti')
                ->with('error', 'Errore durante la generazione del PDF del movimento');
        }
    }

    /**
     * Restituisce i dettagli di un movimento per il modal
     */
    public function movimentoDettagli($id)
    {
        try {
            $movimento = MovimentoMagazzino::with(['prodotto', 'deposito', 'causale', 'cliente', 'fornitore', 'user', 'depositoSorgente', 'depositoDestinazione'])
                ->findOrFail($id);

            $html = view('magazzino.partials.movimento-dettagli', compact('movimento'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            Log::error('Errore caricamento dettagli movimento', [
                'movimento_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Movimento non trovato o errore nel caricamento'
            ], 404);
        }
    }
}