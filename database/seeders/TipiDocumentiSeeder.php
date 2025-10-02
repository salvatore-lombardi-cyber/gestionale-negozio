<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipiDocumentiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipiDocumenti = [
            [
                'codice' => 'DDT',
                'tipo_documento' => 'Documento di Trasporto',
                'descrizione' => 'Documento di trasporto per spedizioni merce',
                'active' => true,
                'sort_order' => 1
            ],
            [
                'codice' => 'FAT',
                'tipo_documento' => 'Fattura',
                'descrizione' => 'Fattura di vendita',
                'active' => true,
                'sort_order' => 2
            ],
            [
                'codice' => 'PREV',
                'tipo_documento' => 'Preventivo',
                'descrizione' => 'Preventivo di vendita',
                'active' => true,
                'sort_order' => 3
            ],
            [
                'codice' => 'ORD',
                'tipo_documento' => 'Ordine',
                'descrizione' => 'Ordine cliente',
                'active' => true,
                'sort_order' => 4
            ],
            [
                'codice' => 'NC',
                'tipo_documento' => 'Nota di Credito',
                'descrizione' => 'Nota di credito',
                'active' => true,
                'sort_order' => 5
            ],
            [
                'codice' => 'ND',
                'tipo_documento' => 'Nota di Debito',
                'descrizione' => 'Nota di debito',
                'active' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($tipiDocumenti as $tipo) {
            \App\Models\DocumentType::updateOrCreate(
                ['codice' => $tipo['codice']],
                $tipo
            );
        }
    }
}
