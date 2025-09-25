<?php

namespace App\Exports;

use App\Models\Anagrafica;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AnagraficheExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $tipo;
    protected $filtri;
    
    public function __construct($tipo, $filtri = [])
    {
        $this->tipo = $tipo;
        $this->filtri = $filtri;
    }

    /**
     * Query per recuperare i dati
     */
    public function query()
    {
        $query = Anagrafica::where('tipo', $this->tipo)->attivi();
        
        // Applica eventuali filtri
        if (!empty($this->filtri['search'])) {
            $search = $this->filtri['search'];
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('cognome', 'LIKE', "%{$search}%")
                  ->orWhere('codice_interno', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('codice_fiscale', 'LIKE', "%{$search}%")
                  ->orWhere('partita_iva', 'LIKE', "%{$search}%");
            });
        }
        
        return $query->orderBy('nome');
    }

    /**
     * Intestazioni del foglio Excel
     */
    public function headings(): array
    {
        $baseHeadings = [
            'Codice Interno',
            'Nome',
            'Cognome',
            'Email',
            'Telefono 1',
            'Telefono 2',
            'Indirizzo',
            'CAP',
            'Comune',
            'Provincia',
            'Nazione',
            'Codice Fiscale',
            'Partita IVA',
            'Stato',
            'Data Creazione',
        ];
        
        // Aggiungi intestazioni specifiche per tipo
        $specificHeadings = $this->getSpecificHeadings();
        
        return array_merge($baseHeadings, $specificHeadings);
    }

    /**
     * Mappatura dei dati per ogni riga
     */
    public function map($anagrafica): array
    {
        $baseData = [
            $anagrafica->codice_interno,
            $anagrafica->nome,
            $anagrafica->cognome,
            $anagrafica->email,
            $anagrafica->telefono_1,
            $anagrafica->telefono_2,
            $anagrafica->indirizzo,
            $anagrafica->cap,
            $anagrafica->comune,
            $anagrafica->provincia,
            $anagrafica->nazione,
            $anagrafica->codice_fiscale,
            $anagrafica->partita_iva,
            $anagrafica->attivo ? 'Attivo' : 'Inattivo',
            $anagrafica->created_at ? $anagrafica->created_at->format('d/m/Y') : '',
        ];
        
        // Aggiungi dati specifici per tipo
        $specificData = $this->getSpecificData($anagrafica);
        
        return array_merge($baseData, $specificData);
    }

    /**
     * Stili per il foglio Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style dell'intestazione
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '029D7E'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Intestazioni specifiche per tipo
     */
    private function getSpecificHeadings(): array
    {
        return match($this->tipo) {
            'fornitore' => [
                'Categoria Merceologica',
                'Lead Time (giorni)',
                'Condizioni Pagamento',
                'Responsabile Acquisti',
            ],
            'vettore' => [
                'Tipo Trasporto',
                'Tempi Standard (ore)',
                'Assicurazione',
            ],
            'agente' => [
                'Percentuale Provvigione',
                'Tipo Contratto',
                'Data Inizio Contratto',
                'Obiettivi Vendita',
            ],
            'articolo' => [
                'Categoria Articolo',
                'Unità di Misura',
                'Prezzo Acquisto',
                'Prezzo Vendita',
                'Scorta Minima',
                'Codice Barre',
            ],
            'servizio' => [
                'Categoria Servizio',
                'Tariffa Oraria',
                'Durata Standard (min)',
            ],
            default => [],
        };
    }

    /**
     * Dati specifici per tipo
     */
    private function getSpecificData($anagrafica): array
    {
        return match($this->tipo) {
            'fornitore' => [
                $anagrafica->categoria_merceologica,
                $anagrafica->lead_time_giorni,
                $anagrafica->condizioni_pagamento,
                $anagrafica->responsabile_acquisti,
            ],
            'vettore' => [
                $anagrafica->tipo_trasporto ? ucfirst($anagrafica->tipo_trasporto) : '',
                $anagrafica->tempi_standard_ore,
                $anagrafica->assicurazione_disponibile ? 'Sì' : 'No',
            ],
            'agente' => [
                $anagrafica->percentuale_provvigione ? $anagrafica->percentuale_provvigione . '%' : '',
                $anagrafica->tipo_contratto ? ucfirst($anagrafica->tipo_contratto) : '',
                $anagrafica->data_inizio_contratto ? $anagrafica->data_inizio_contratto->format('d/m/Y') : '',
                $anagrafica->obiettivi_vendita_annuali ? '€ ' . number_format($anagrafica->obiettivi_vendita_annuali, 2, ',', '.') : '',
            ],
            'articolo' => [
                $anagrafica->categoria_articolo,
                $anagrafica->unita_misura,
                $anagrafica->prezzo_acquisto ? '€ ' . number_format($anagrafica->prezzo_acquisto, 4, ',', '.') : '',
                $anagrafica->prezzo_vendita ? '€ ' . number_format($anagrafica->prezzo_vendita, 4, ',', '.') : '',
                $anagrafica->scorta_minima,
                $anagrafica->codice_barre,
            ],
            'servizio' => [
                $anagrafica->categoria_servizio,
                $anagrafica->tariffa_oraria ? '€ ' . number_format($anagrafica->tariffa_oraria, 2, ',', '.') : '',
                $anagrafica->durata_standard_minuti,
            ],
            default => [],
        };
    }
}