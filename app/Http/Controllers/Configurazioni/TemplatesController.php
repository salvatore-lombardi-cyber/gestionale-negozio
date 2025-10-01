<?php

namespace App\Http\Controllers\Configurazioni;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * Controller per gestione Templates di Stampa
 * Replica funzionalità del vecchio gestionale con design moderno
 */
class TemplatesController extends Controller
{
    /**
     * Display del dashboard templates
     */
    public function index(): View
    {
        $templates = Template::orderBy('name')->get();
        
        return view('configurations.templates.index', compact('templates'));
    }

    /**
     * Mostra form creazione nuovo template
     */
    public function create(): View
    {
        return view('configurations.templates.create');
    }

    /**
     * Salva nuovo template
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:templates,name',
        ]);

        Template::create([
            'uuid' => (string) Str::uuid(),
            'name' => $validated['name'],
            'created_by' => Auth::id(),
            'modified_at' => now(),
        ]);

        return redirect()->route('configurations.templates.index')
            ->with('success', 'Template creato con successo!');
    }

    /**
     * Visualizza dettagli template
     */
    public function show($id): View
    {
        $template = Template::findOrFail($id);
        
        return view('configurations.templates.show', compact('template'));
    }

    /**
     * Mostra form modifica template
     */
    public function edit($id): View
    {
        $template = Template::findOrFail($id);
        
        return view('configurations.templates.edit', compact('template'));
    }

    /**
     * Aggiorna template esistente
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $template = Template::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:templates,name,' . $template->id,
        ]);

        $template->update([
            'name' => $validated['name'],
            'modified_at' => now(),
        ]);

        return redirect()->route('configurations.templates.index')
            ->with('success', 'Template aggiornato con successo!');
    }

    /**
     * Elimina template
     */
    public function destroy($id): RedirectResponse
    {
        $template = Template::findOrFail($id);
        
        // Log eliminazione per audit
        Log::warning('Template eliminato definitivamente', [
            'user_id' => Auth::id(),
            'template_id' => $id,
            'template_name' => $template->name,
            'ip' => request()->ip()
        ]);

        $template->delete();

        return redirect()->route('configurations.templates.index')
            ->with('success', 'Template eliminato definitivamente!');
    }
}