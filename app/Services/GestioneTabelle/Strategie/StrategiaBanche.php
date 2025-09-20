<?php

namespace App\Services\GestioneTabelle\Strategie;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Strategia per gestione tabella Banche
 * Implementa validazione IBAN, ABI, CAB secondo standard bancari italiani
 */
class StrategiaBanche extends StrategiaTabellaBase
{
    protected string $modelClass = Bank::class;
    protected string $nomeTabella = 'banche';

    /**
     * Configurazione campi specifici per Banche
     */
    public function getCampiConfigurazione(): array
    {
        return [
            'nome_banca' => [
                'tipo' => 'string',
                'obbligatorio' => true,
                'lunghezza_max' => 255,
                'label' => 'Nome Banca',
                'placeholder' => 'Es: Intesa Sanpaolo S.p.A.'
            ],
            'abi' => [
                'tipo' => 'string',
                'obbligatorio' => true,
                'lunghezza_esatta' => 5,
                'pattern' => '^[0-9]{5}$',
                'label' => 'Codice ABI',
                'placeholder' => 'Es: 03069'
            ],
            'cab' => [
                'tipo' => 'string',
                'obbligatorio' => true,
                'lunghezza_esatta' => 5,
                'pattern' => '^[0-9]{5}$',
                'label' => 'Codice CAB',
                'placeholder' => 'Es: 01234'
            ],
            'cc' => [
                'tipo' => 'string',
                'obbligatorio' => true,
                'lunghezza_max' => 20,
                'label' => 'Numero Conto Corrente',
                'placeholder' => 'Es: 000000123456'
            ],
            'swift' => [
                'tipo' => 'string',
                'obbligatorio' => false,
                'lunghezza_min' => 8,
                'lunghezza_max' => 11,
                'pattern' => '^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$',
                'label' => 'Codice SWIFT/BIC',
                'placeholder' => 'Es: BCITITMM'
            ],
            'sia' => [
                'tipo' => 'string',
                'obbligatorio' => false,
                'lunghezza_esatta' => 5,
                'pattern' => '^[A-Z0-9]{5}$',
                'label' => 'Codice SIA',
                'placeholder' => 'Es: 01234'
            ],
            'iban' => [
                'tipo' => 'string',
                'obbligatorio' => true,
                'lunghezza_min' => 15,
                'lunghezza_max' => 34,
                'pattern' => '^[A-Z]{2}[0-9]{2}[A-Z0-9]+$',
                'label' => 'Codice IBAN',
                'placeholder' => 'Es: IT60X0542811101000000123456'
            ]
        ];
    }

    /**
     * Ottieni dati con filtri e paginazione
     */
    public function ottieniDati(Request $request): LengthAwarePaginator
    {
        $query = $this->getModel()::query();

        // Filtro ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome_banca', 'LIKE', "%{$search}%")
                  ->orWhere('iban', 'LIKE', "%{$search}%")
                  ->orWhere('abi', 'LIKE', "%{$search}%")
                  ->orWhere('cab', 'LIKE', "%{$search}%");
            });
        }

        // Filtro per banche attive
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        // Ordinamento
        $sortBy = $request->get('sort_by', 'nome_banca');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate(
            $request->get('per_page', 20)
        )->withQueryString();
    }

    /**
     * Validazione specifica per banche
     */
    public function validaDati(array $dati): array
    {
        $regole = [
            'nome_banca' => 'required|string|max:255',
            'abi' => 'required|regex:/^[0-9]{5}$/|unique:banks,abi,' . ($dati['id'] ?? 'NULL'),
            'cab' => 'required|regex:/^[0-9]{5}$/',
            'cc' => 'required|string|max:20',
            'swift' => 'nullable|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
            'sia' => 'nullable|regex:/^[A-Z0-9]{5}$/',
            'iban' => 'required|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/|unique:banks,iban,' . ($dati['id'] ?? 'NULL'),
            'active' => 'boolean'
        ];

        $messaggi = [
            'nome_banca.required' => 'Il nome della banca è obbligatorio',
            'abi.required' => 'Il codice ABI è obbligatorio',
            'abi.regex' => 'Il codice ABI deve essere di 5 cifre',
            'abi.unique' => 'Questo codice ABI è già in uso',
            'cab.required' => 'Il codice CAB è obbligatorio',
            'cab.regex' => 'Il codice CAB deve essere di 5 cifre',
            'cc.required' => 'Il numero di conto corrente è obbligatorio',
            'swift.regex' => 'Il codice SWIFT non è valido',
            'sia.regex' => 'Il codice SIA deve essere di 5 caratteri alfanumerici',
            'iban.required' => 'Il codice IBAN è obbligatorio',
            'iban.regex' => 'Il formato IBAN non è valido',
            'iban.unique' => 'Questo IBAN è già in uso'
        ];

        return [$regole, $messaggi];
    }

    /**
     * Validazione IBAN con algoritmo checksum
     */
    public function validaIban(string $iban): bool
    {
        $iban = strtoupper(str_replace(' ', '', $iban));
        
        if (strlen($iban) < 15 || strlen($iban) > 34) {
            return false;
        }

        // Sposta i primi 4 caratteri alla fine
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        
        // Sostituisci lettere con numeri (A=10, B=11, ..., Z=35)
        $numeric = '';
        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            if (is_numeric($char)) {
                $numeric .= $char;
            } else {
                $numeric .= (ord($char) - ord('A') + 10);
            }
        }

        // Calcola modulo 97
        return bcmod($numeric, '97') === '1';
    }

    /**
     * Prepara dati per salvataggio
     */
    public function preparaDatiSalvataggio(array $dati): array
    {
        // Normalizza IBAN (maiuscolo, senza spazi)
        if (isset($dati['iban'])) {
            $dati['iban'] = strtoupper(str_replace(' ', '', $dati['iban']));
        }

        // Normalizza SWIFT (maiuscolo)
        if (isset($dati['swift'])) {
            $dati['swift'] = strtoupper($dati['swift']);
        }

        // Normalizza SIA (maiuscolo)
        if (isset($dati['sia'])) {
            $dati['sia'] = strtoupper($dati['sia']);
        }

        // Aggiungi UUID se non presente
        if (empty($dati['uuid'])) {
            $dati['uuid'] = \Illuminate\Support\Str::uuid()->toString();
        }

        // Aggiungi utente creatore
        if (empty($dati['id_utente'])) {
            $dati['id_utente'] = auth()->id();
        }

        return $dati;
    }

    /**
     * Configurazione UI specifica per banche
     */
    public function getConfigurazioneUI(): array
    {
        return [
            'titolo' => 'Gestione Banche',
            'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
            'icona' => 'bi-bank',
            'colori' => [
                'from' => '#48cae4',
                'to' => '#023e8a'
            ],
            'colonne_lista' => [
                'nome_banca' => 'Nome Banca',
                'abi' => 'ABI',
                'cab' => 'CAB', 
                'iban' => 'IBAN',
                'active' => 'Attivo'
            ],
            'campi_ricerca' => ['nome_banca', 'iban', 'abi', 'cab'],
            'ordinamento_default' => ['nome_banca', 'asc'],
            'azioni_bulk' => ['attiva', 'disattiva', 'elimina'],
            'esportazione' => ['excel', 'csv', 'pdf']
        ];
    }
}