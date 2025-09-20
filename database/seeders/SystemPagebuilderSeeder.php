<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemPagebuilder;

class SystemPagebuilderSeeder extends Seeder
{
    /**
     * 🌈 SEEDER SPETTACOLARE - I 27 COLORI PIÙ BELLI D'ITALIA!
     * Ogni tabella ha un gradiente unico basato su ricerca 2025
     */
    public function run(): void
    {
        $tables = [
            // 1. VAT NATURE ASSOCIATIONS - Verde Smeraldo (speciale con loadContentInDiv)
            [
                'objname' => 'vat_nature_associations',
                'tablename' => 'vat_nature_associations',
                'display_name' => 'Associazioni Nature IVA',
                'icon_svg' => '<i class="bi bi-link-45deg"></i>',
                'color_from' => '#029D7E',
                'color_to' => '#4DC9A5',
                'sort_order' => 1
            ],
            
            // 2. TAX RATES - Rosso Fiscale (Aliquote IVA)
            [
                'objname' => 'tax_rates',
                'tablename' => 'tax_rates',
                'display_name' => 'Aliquote IVA',
                'icon_svg' => '<i class="bi bi-percent"></i>',
                'color_from' => '#d63031',
                'color_to' => '#e17055',
                'sort_order' => 2
            ],
            
            // 3. GOOD APPEARANCES - Arancione Vibrante
            [
                'objname' => 'good_appearances',
                'tablename' => 'good_appearances',
                'display_name' => 'Aspetto dei Beni',
                'icon_svg' => '<i class="bi bi-box-seam"></i>',
                'color_from' => '#ff6b6b',
                'color_to' => '#feca57',
                'sort_order' => 3
            ],
            
            // 3.1 ASPETTO BENI - Arancione Enterprise (Nuova implementazione)
            [
                'objname' => 'aspetto_beni',
                'tablename' => 'aspetto_beni',
                'display_name' => 'Aspetto dei Beni',
                'icon_svg' => '<i class="bi bi-box-seam"></i>',
                'color_from' => '#f39c12',
                'color_to' => '#e67e22',
                'sort_order' => 31
            ],
            
            // 4. BANKS - Blu Corporate
            [
                'objname' => 'banks',
                'tablename' => 'banks',
                'display_name' => 'Banche',
                'icon_svg' => '<i class="bi bi-bank"></i>',
                'color_from' => '#48cae4',
                'color_to' => '#023e8a',
                'sort_order' => 4
            ],
            
            // 5. PRODUCT CATEGORIES - Verde Natura (Categorie articoli)
            [
                'objname' => 'product_categories',
                'tablename' => 'product_categories',
                'display_name' => 'Categorie Articoli',
                'icon_svg' => '<i class="bi bi-grid-3x3-gap"></i>',
                'color_from' => '#38b000',
                'color_to' => '#70e000',
                'sort_order' => 5
            ],
            
            // 6. CUSTOMER CATEGORIES - Rosa Moderno
            [
                'objname' => 'customer_categories',
                'tablename' => 'customer_categories',
                'display_name' => 'Categorie Clienti',
                'icon_svg' => '<i class="bi bi-people"></i>',
                'color_from' => '#f093fb',
                'color_to' => '#f5576c',
                'sort_order' => 6
            ],
            
            // 7. SUPPLIER CATEGORIES - Viola Enterprise
            [
                'objname' => 'supplier_categories',
                'tablename' => 'supplier_categories',
                'display_name' => 'Categorie Fornitori',
                'icon_svg' => '<i class="bi bi-truck"></i>',
                'color_from' => '#4c63b6',
                'color_to' => '#9c27b0',
                'sort_order' => 7
            ],
            
            // 8. SIZE COLORS - Giallo Solare (Taglie e colori)
            [
                'objname' => 'size_colors',
                'tablename' => 'size_colors',
                'display_name' => 'Taglie e Colori',
                'icon_svg' => '<i class="bi bi-palette"></i>',
                'color_from' => '#ffecd2',
                'color_to' => '#fcb69f',
                'sort_order' => 8
            ],
            
            // 9. WAREHOUSE CAUSES - Rosso Energia (Causali di magazzino)
            [
                'objname' => 'warehouse_causes',
                'tablename' => 'warehouse_causes',
                'display_name' => 'Causali di Magazzino',
                'icon_svg' => '<i class="bi bi-building"></i>',
                'color_from' => '#ee0979',
                'color_to' => '#ff6a00',
                'sort_order' => 9
            ],
            
            // 10. COLOR VARIANTS - Multicolore (Colori varianti)
            [
                'objname' => 'color_variants',
                'tablename' => 'color_variants',
                'display_name' => 'Colori Varianti',
                'icon_svg' => '<i class="bi bi-droplet-fill"></i>',
                'color_from' => '#a8edea',
                'color_to' => '#fed6e3',
                'sort_order' => 10
            ],
            
            // 11. CONDITIONS - Ciano Fresco
            [
                'objname' => 'conditions',
                'tablename' => 'conditions',
                'display_name' => 'Condizioni',
                'icon_svg' => '<i class="bi bi-list-ul"></i>',
                'color_from' => '#00f2fe',
                'color_to' => '#4facfe',
                'sort_order' => 11
            ],
            
            // 12. FIXED PRICE DENOMINATIONS - Oro Luxury (Denominazione prezzi fissi)
            [
                'objname' => 'fixed_price_denominations',
                'tablename' => 'fixed_price_denominations',
                'display_name' => 'Denominazione Prezzi Fissi',
                'icon_svg' => '<i class="bi bi-currency-euro"></i>',
                'color_from' => '#ffeaa7',
                'color_to' => '#fab1a0',
                'sort_order' => 12
            ],
            
            // 13. DEPOSITS - Verde Aqua
            [
                'objname' => 'deposits',
                'tablename' => 'deposits',
                'display_name' => 'Depositi',
                'icon_svg' => '<i class="bi bi-archive"></i>',
                'color_from' => '#81ecec',
                'color_to' => '#00b894',
                'sort_order' => 13
            ],
            
            // 14. PRICE LISTS - Magenta Dinamico
            [
                'objname' => 'price_lists',
                'tablename' => 'price_lists',
                'display_name' => 'Listini',
                'icon_svg' => '<i class="bi bi-list-columns"></i>',
                'color_from' => '#fd79a8',
                'color_to' => '#e84393',
                'sort_order' => 14
            ],
            
            // 15. PAYMENT METHODS - Oro Premium (Modalità di pagamento)
            [
                'objname' => 'payment_methods',
                'tablename' => 'payment_methods',
                'display_name' => 'Modalità di Pagamento',
                'icon_svg' => '<i class="bi bi-wallet2"></i>',
                'color_from' => '#fdcb6e',
                'color_to' => '#e84393',
                'sort_order' => 15
            ],
            
            // 16. VAT NATURES - Blu Oceano (Natura IVA)
            [
                'objname' => 'vat_natures',
                'tablename' => 'vat_natures', 
                'display_name' => 'Natura IVA',
                'icon_svg' => '<i class="bi bi-receipt"></i>',
                'color_from' => '#667eea',
                'color_to' => '#764ba2',
                'sort_order' => 16
            ],
            
            // 17. SHIPPING TERMS - Blu Shipping (Porto)
            [
                'objname' => 'shipping_terms',
                'tablename' => 'shipping_terms',
                'display_name' => 'Porto',
                'icon_svg' => '<i class="bi bi-truck-flatbed"></i>',
                'color_from' => '#74b9ff',
                'color_to' => '#0984e3',
                'sort_order' => 17
            ],
            
            // 18. MERCHANDISING SECTORS - Viola Fashion (Settori merceologici)
            [
                'objname' => 'merchandising_sectors',
                'tablename' => 'merchandising_sectors',
                'display_name' => 'Settori Merceologici',
                'icon_svg' => '<i class="bi bi-diagram-3"></i>',
                'color_from' => '#a29bfe',
                'color_to' => '#6c5ce7',
                'sort_order' => 18
            ],
            
            // 19. SIZE VARIANTS - Turchese Vivace (Taglie varianti)
            [
                'objname' => 'size_variants',
                'tablename' => 'size_variants',
                'display_name' => 'Taglie Varianti',
                'icon_svg' => '<i class="bi bi-rulers"></i>',
                'color_from' => '#00cec9',
                'color_to' => '#55a3ff',
                'sort_order' => 19
            ],
            
            // 20. SIZE TYPES - Verde Lime (Tipo di taglie)
            [
                'objname' => 'size_types',
                'tablename' => 'size_types',
                'display_name' => 'Tipo di Taglie',
                'icon_svg' => '<i class="bi bi-bar-chart"></i>',
                'color_from' => '#a7f070',
                'color_to' => '#00d2d3',
                'sort_order' => 20
            ],
            
            // 21. PAYMENT TYPES - Giallo Bancario (Tipo di pagamento)
            [
                'objname' => 'payment_types',
                'tablename' => 'payment_types',
                'display_name' => 'Tipo di Pagamento',
                'icon_svg' => '<i class="bi bi-credit-card"></i>',
                'color_from' => '#fdcb6e',
                'color_to' => '#e17055',
                'sort_order' => 21
            ],
            
            // 22. TRANSPORTS - Grigio Moderno (Trasporto)
            [
                'objname' => 'transports',
                'tablename' => 'transports',
                'display_name' => 'Trasporto',
                'icon_svg' => '<i class="bi bi-truck"></i>',
                'color_from' => '#636e72',
                'color_to' => '#2d3436',
                'sort_order' => 22
            ],
            
            // 23. TRANSPORT CARRIERS - Blu Logistica (Trasporto a mezzo)
            [
                'objname' => 'transport_carriers',
                'tablename' => 'transport_carriers',
                'display_name' => 'Trasporto a Mezzo',
                'icon_svg' => '<i class="bi bi-person-badge"></i>',
                'color_from' => '#3742fa',
                'color_to' => '#2f3542',
                'sort_order' => 23
            ],
            
            // 24. LOCATIONS - Verde Geografia
            [
                'objname' => 'locations',
                'tablename' => 'locations',
                'display_name' => 'Ubicazioni',
                'icon_svg' => '<i class="bi bi-geo-alt"></i>',
                'color_from' => '#00b894',
                'color_to' => '#55a3ff',
                'sort_order' => 24
            ],
            
            // 25. UNIT OF MEASURES - Blu Scientifico (Unità di misura)
            [
                'objname' => 'unit_of_measures',
                'tablename' => 'unit_of_measures',
                'display_name' => 'Unità di Misura',
                'icon_svg' => '<i class="bi bi-calculator"></i>',
                'color_from' => '#2998ff',
                'color_to' => '#5643fa',
                'sort_order' => 25
            ],
            
            // 26. CURRENCIES - Verde Monetario
            [
                'objname' => 'currencies',
                'tablename' => 'currencies',
                'display_name' => 'Valute',
                'icon_svg' => '<i class="bi bi-currency-exchange"></i>',
                'color_from' => '#00cec9',
                'color_to' => '#55efc4',
                'sort_order' => 26
            ],
            
            // 27. ZONES - Arancione Geografia
            [
                'objname' => 'zones',
                'tablename' => 'zones',
                'display_name' => 'Zone',
                'icon_svg' => '<i class="bi bi-globe"></i>',
                'color_from' => '#ff7675',
                'color_to' => '#fd79a8',
                'sort_order' => 27
            ]
        ];
        
        foreach ($tables as $index => $tableData) {
            SystemPagebuilder::updateOrCreate(
                ['objname' => $tableData['objname']],
                array_merge($tableData, [
                    'fields_config' => [
                        'primary_fields' => ['id', 'code', 'name', 'description'],
                        'searchable_fields' => ['code', 'name', 'description'],
                        'validation_rules' => [
                            'code' => 'required|string|max:20|unique:' . $tableData['tablename'] . ',code',
                            'name' => 'required|string|max:255',
                            'description' => 'nullable|string|max:500'
                        ]
                    ],
                    'ui_config' => [
                        'list_columns' => ['code', 'name', 'description', 'created_at'],
                        'form_fields' => ['code', 'name', 'description'],
                        'enable_bulk_actions' => true,
                        'items_per_page' => 20
                    ],
                    'permissions' => [
                        'read' => ['admin', 'manager', 'user'],
                        'create' => ['admin', 'manager'],
                        'update' => ['admin', 'manager'], 
                        'delete' => ['admin']
                    ],
                    'is_active' => true,
                    'enable_search' => true,
                    'enable_export' => true,
                    'audit_level' => 'full'
                ])
            );
        }
        
        $this->command->info('✅ Seeder completato: 27 tabelle di sistema configurate');
        $this->command->info('🎨 Dashboard con icone Bootstrap Icons pronto');
        $this->command->info('🔧 Sistema tabelle configurabile operativo');
    }
}