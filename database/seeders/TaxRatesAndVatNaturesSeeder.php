<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxRate;
use App\Models\VatNature;

class TaxRatesAndVatNaturesSeeder extends Seeder
{
    public function run(): void
    {
        // Crea aliquote IVA standard italiane
        $taxRates = [
            [
                'code' => '22%',
                'name' => 'Aliquota ordinaria',
                'description' => 'Aliquota IVA ordinaria',
                'percentuale' => 22.00,
                'active' => true
            ],
            [
                'code' => '10%',
                'name' => 'Aliquota ridotta',
                'description' => 'Aliquota IVA ridotta',
                'percentuale' => 10.00,
                'active' => true
            ],
            [
                'code' => '4%',
                'name' => 'Aliquota super ridotta',
                'description' => 'Aliquota IVA super ridotta',
                'percentuale' => 4.00,
                'active' => true
            ],
            [
                'code' => '0%',
                'name' => 'Aliquota azzerata',
                'description' => 'Aliquota IVA azzerata',
                'percentuale' => 0.00,
                'active' => true
            ]
        ];

        foreach ($taxRates as $taxRate) {
            TaxRate::create($taxRate);
        }

        // Crea nature IVA secondo normativa italiana
        $vatNatures = [
            [
                'code' => 'N1',
                'name' => 'Escluse ex art. 15',
                'description' => 'Operazioni escluse ex articolo 15 del DPR 633/72',
                'active' => true,
                'sort_order' => 1
            ],
            [
                'code' => 'N2.1',
                'name' => 'Non soggette - artt. 7-7septies',
                'description' => 'Non soggette ad IVA ai sensi degli artt. da 7 a 7-septies del DPR 633/72',
                'active' => true,
                'sort_order' => 2
            ],
            [
                'code' => 'N2.2',
                'name' => 'Non soggette - altri casi',
                'description' => 'Non soggette ad IVA - altri casi previsti dalla normativa',
                'active' => true,
                'sort_order' => 3
            ],
            [
                'code' => 'N3.1',
                'name' => 'Non imponibili - esportazioni',
                'description' => 'Non imponibili - esportazioni art.8 e 8-bis DPR 633/72',
                'active' => true,
                'sort_order' => 4
            ],
            [
                'code' => 'N3.2',
                'name' => 'Non imponibili - cessioni intracomunitarie',
                'description' => 'Non imponibili - cessioni intracomunitarie art.41 DL 331/93',
                'active' => true,
                'sort_order' => 5
            ],
            [
                'code' => 'N4',
                'name' => 'Esenti',
                'description' => 'Operazioni esenti ex art.10 DPR 633/72',
                'active' => true,
                'sort_order' => 6
            ],
            [
                'code' => 'N5',
                'name' => 'Regime del margine',
                'description' => 'Regime del margine/IVA non esposta in fattura',
                'active' => true,
                'sort_order' => 7
            ],
            [
                'code' => 'N6.1',
                'name' => 'Inversione contabile - rottami',
                'description' => 'Inversione contabile - cessione di rottami e altri materiali',
                'active' => true,
                'sort_order' => 8
            ],
            [
                'code' => 'N6.2',
                'name' => 'Inversione contabile - oro e argento',
                'description' => 'Inversione contabile - cessione oro e argento puro',
                'active' => true,
                'sort_order' => 9
            ],
            [
                'code' => 'N7',
                'name' => 'IVA assolta in altro Stato UE',
                'description' => 'IVA assolta in altro Stato UE (vendite a distanza)',
                'active' => true,
                'sort_order' => 10
            ]
        ];

        foreach ($vatNatures as $vatNature) {
            VatNature::create($vatNature);
        }

        $this->command->info('Creati ' . count($taxRates) . ' aliquote IVA e ' . count($vatNatures) . ' nature IVA');
    }
}