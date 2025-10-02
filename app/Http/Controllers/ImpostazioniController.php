<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentNumbering;
use App\Models\SystemSetting;
use App\Models\PrintAssociation;
use App\Models\DocumentType;
use App\Models\Template;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Controller per la gestione delle Impostazioni del sistema
 * Gestisce numeratori, stampe, importazioni e utenze
 */
class ImpostazioniController extends Controller
{
    /**
     * Dashboard principale Impostazioni
     */
    public function index()
    {
        return view('impostazioni.index');
    }

    // === CONFIGURAZIONE STAMPE ===

    /**
     * Mostra la gestione associazioni template-documenti
     */
    public function configurazioneStampe()
    {
        // Carica associazioni esistenti con relazioni
        $associations = PrintAssociation::with(['documentType', 'template'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Carica tipi documento disponibili
        $documentTypes = DocumentType::where('active', true)
            ->orderBy('codice')
            ->get();

        // Carica template disponibili
        $templates = Template::orderBy('name')
            ->get();

        return view('impostazioni.configurazione-stampe', compact(
            'associations', 
            'documentTypes', 
            'templates'
        ));
    }

    /**
     * Crea nuova associazione template-documento
     */
    public function storeConfigurazioneStampe(Request $request)
    {
        // Rate limiting
        $rateLimiterKey = 'create-print-association:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 10)) {
            return back()->withErrors(['error' => 'Troppe associazioni create. Attendi prima di aggiungere altre.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        $validated = $request->validate([
            'tipo_documento' => 'required|exists:tipidocumenti,id',
            'template_stampa' => 'required|exists:templates,id'
        ]);

        // Verifica che l'associazione non esista già
        $existingAssociation = PrintAssociation::where('document_type_id', $validated['tipo_documento'])
            ->where('template_id', $validated['template_stampa'])
            ->first();

        if ($existingAssociation) {
            return back()->withErrors(['error' => 'Associazione già esistente per questo tipo documento e template.']);
        }

        // Crea associazione
        $association = PrintAssociation::create([
            'uuid' => (string) Str::uuid(),
            'document_type_id' => $validated['tipo_documento'],
            'template_id' => $validated['template_stampa'],
            'active' => true
        ]);

        Log::info('Nuova associazione stampa creata', [
            'user_id' => Auth::id(),
            'association_uuid' => $association->uuid,
            'document_type_id' => $validated['tipo_documento'],
            'template_id' => $validated['template_stampa'],
            'ip' => $request->ip()
        ]);

        return redirect()->route('impostazioni.configurazione-stampe')
            ->with('success', 'Associazione template-documento creata con successo!');
    }

    /**
     * Elimina associazione template-documento
     */
    public function destroyConfigurazioneStampe($uuid)
    {
        // Validazione UUID
        if (!Str::isUuid($uuid)) {
            abort(404, 'Associazione non trovata.');
        }

        $association = PrintAssociation::where('uuid', $uuid)->firstOrFail();

        // Rate limiting
        $rateLimiterKey = 'delete-print-association:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return back()->withErrors(['error' => 'Troppe eliminazioni. Attendi prima di eliminare altre associazioni.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        Log::warning('Associazione stampa eliminata', [
            'user_id' => Auth::id(),
            'association_uuid' => $uuid,
            'document_type_id' => $association->document_type_id,
            'template_id' => $association->template_id,
            'ip' => request()->ip()
        ]);

        $association->delete();

        return redirect()->route('impostazioni.configurazione-stampe')
            ->with('success', 'Associazione eliminata definitivamente!');
    }

    // === CONFIGURAZIONE NUMERATORI ===

    /**
     * Mostra la gestione numeratori documenti
     */
    public function configurazioneNumeratori()
    {
        $numbering = DocumentNumbering::all()->keyBy('document_type');

        return view('impostazioni.configurazione-numeratori', compact('numbering'));
    }

    /**
     * Aggiorna configurazioni numeratori
     */
    public function updateConfigurazioneNumeratori(Request $request)
    {
        // Rate limiting
        $rateLimiterKey = 'update-numbering:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return back()->withErrors(['error' => 'Troppe modifiche. Riprova tra qualche minuto.']);
        }
        RateLimiter::hit($rateLimiterKey, 300);

        $validated = $request->validate([
            'numbering' => 'required|array',
            'numbering.*.current_number' => 'required|integer|min:1|max:999999',
            'numbering.*.prefix' => 'nullable|string|max:10|regex:/^[A-Z0-9\-_]*$/',
            'numbering.*.separator' => 'nullable|string|max:5'
            // Rimuoviamo la validazione per use_year e use_month
            // perché i checkbox non spuntati non vengono inviati
        ]);

        // Aggiorna numeratori
        foreach ($validated['numbering'] as $docType => $data) {
            // Ottieni il record esistente per preservare i checkbox se non inviati
            $existing = DocumentNumbering::where('document_type', $docType)->first();
            
            $updateData = [
                'current_number' => $data['current_number'],
                'prefix' => $data['prefix'] ?? null,
                'suffix' => null, // Per ora non utilizzato
                'separator' => $data['separator'] ?? '/',
                'reset_frequency' => 1 // Annuale di default
            ];
            
            // Solo aggiorna i checkbox se sono presenti nel form
            // Altrimenti mantieni i valori esistenti
            if (array_key_exists('use_year', $data)) {
                $updateData['use_year'] = true; // Checkbox presente = spuntato
            } elseif (!$existing) {
                $updateData['use_year'] = false; // Nuovo record senza checkbox
            } // Altrimenti mantieni il valore esistente
            
            if (array_key_exists('use_month', $data)) {
                $updateData['use_month'] = true; // Checkbox presente = spuntato
            } elseif (!$existing) {
                $updateData['use_month'] = false; // Nuovo record senza checkbox
            } // Altrimenti mantieni il valore esistente
            
            DocumentNumbering::updateOrCreate(
                ['document_type' => $docType],
                $updateData
            );
        }

        Log::info('Configurazioni numeratori aggiornate', [
            'user_id' => Auth::id(),
            'document_types' => array_keys($validated['numbering']),
            'ip' => $request->ip()
        ]);

        return redirect()->route('impostazioni.configurazione-numeratori')
            ->with('success', 'Configurazioni numeratori aggiornate con successo!');
    }

    // === IMPORTAZIONE DATI ===

    /**
     * Mostra la sezione importazione dati (placeholder)
     */
    public function importazioneDati()
    {
        return view('impostazioni.importazione-dati');
    }

    // === UTILITY METHODS ===

    /**
     * Genera anteprima numeratore
     */
    public function previewNumbering(Request $request)
    {
        $validated = $request->validate([
            'current_number' => 'required|integer|min:1',
            'prefix' => 'nullable|string|max:10',
            'separator' => 'nullable|string|max:5',
            'use_year' => 'boolean',
            'use_month' => 'boolean'
        ]);

        $parts = [];
        if ($validated['prefix']) {
            $parts[] = $validated['prefix'];
        }
        
        $parts[] = str_pad($validated['current_number'], 4, '0', STR_PAD_LEFT);
        
        if ($validated['use_month']) {
            $parts[] = date('m');
        }
        
        if ($validated['use_year']) {
            $parts[] = date('Y');
        }

        $separator = $validated['separator'] ?? '/';
        $preview = implode($separator, $parts);

        return response()->json([
            'success' => true,
            'preview' => $preview
        ]);
    }
}