<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clienti = Cliente::orderBy('cognome')->get();
        return view('clienti.index', compact('clienti'));
    }

    public function create()
    {
        return view('clienti.create');
    }

    public function store(Request $request)
    {

    $request->validate([
        'nome' => 'required|string|max:255',
        'cognome' => 'required|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ]);

    Cliente::create($request->all());

return redirect()->route('clienti.index')
    ->with('success', 'Cliente creato con successo!');
}

    public function show(Cliente $cliente)
    {
        return view('clienti.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clienti.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
$request->validate([
        'nome' => 'required|string|max:255',
        'cognome' => 'required|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ]);

    $cliente->update($request->all());

    return redirect()->route('clienti.index')
        ->with('success', 'Cliente aggiornato con successo!');    }

    public function destroy(Cliente $cliente)
    {
$cliente->delete();

    return redirect()->route('clienti.index')
        ->with('success', 'Cliente eliminato con successo!');    }
}