<?php

namespace App\Http\Controllers\Configurazioni;

use App\Http\Controllers\Controller;
// use App\Services\GestioneTabelle\GestioneTabelleService; // Temporaneamente disabilitato
use App\Models\VatNatureAssociation;
use App\Models\TaxRate;
use App\Models\VatNature;
use App\Models\SystemTable;
use App\Models\SizeColor;
use App\Models\WarehouseCause;
use App\Models\FixedPriceDenomination;
use App\Models\Deposit;
use App\Models\PriceList;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Controller enterprise per gestione tabelle di configurazione
 * Architettura pulita con separazione delle responsabilità
 */
class GestioneTabelleController extends Controller
{
    /**
     * Ottieni le configurazioni per tutte le tabelle
     */
    public function getConfigurazioni(): array
    {
        return [
            'associazioni-nature-iva' => [
                'modello' => \App\Models\VatNatureAssociation::class,
                'nome' => 'Associazioni Nature IVA',
                'nome_singolare' => 'Associazione Nature IVA',
                'icona' => 'bi-link-45deg',
                'colore' => 'primary',
                'color_from' => '#4ecdc4',
                'color_to' => '#44a08d',
                'descrizione' => 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali',
                'campi_visibili' => [
                    'nome_associazione' => 'Nome Associazione',
                    'descrizione' => 'Descrizione',
                    'aliquota_iva' => 'Aliquota IVA',
                    'natura_iva' => 'Natura IVA'
                ]
            ],
            'aliquote-iva' => [
                'modello' => \App\Models\TaxRate::class,
                'nome' => 'Aliquote IVA',
                'nome_singolare' => 'Aliquota IVA',
                'icona' => 'bi-percent',
                'colore' => 'danger',
                'color_from' => '#dc3545',
                'color_to' => '#c82333',
                'descrizione' => 'Gestione semplificata delle aliquote IVA del sistema',
                'campi_visibili' => [
                    'percentuale' => 'Percentuale',
                    'description' => 'Descrizione'
                ]
            ],
            'aspetto-beni' => [
                'modello' => \App\Models\AspettoBeni::class,
                'nome' => 'Aspetto dei Beni',
                'nome_singolare' => 'Aspetto dei Beni',
                'icona' => 'bi-box-seam',
                'colore' => 'warning',
                'color_from' => '#ffb500',
                'color_to' => '#ffd700',
                'descrizione' => 'Gestione aspetto e caratteristiche dei beni',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ]
            ],
            'banche' => [
                'modello' => \App\Models\Bank::class,
                'nome' => 'Banche',
                'nome_singolare' => 'Banca',
                'icona' => 'bi-bank',
                'colore' => 'info',
                'color_from' => '#48cae4',
                'color_to' => '#023e8a',
                'descrizione' => 'Gestione semplificata banche con codici ABI, CAB e BIC',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'abi_code' => 'ABI',
                    'cab_code' => 'CAB',
                    'bic_swift' => 'BIC/SWIFT'
                ]
            ],
            'categorie-articoli' => [
                'modello' => \App\Models\ProductCategory::class,
                'nome' => 'Categorie Articoli',
                'nome_singolare' => 'Categoria Articoli',
                'icona' => 'bi-grid-3x3-gap',
                'colore' => 'success',
                'color_from' => '#38b000',
                'color_to' => '#70e000',
                'descrizione' => 'Sistema gerarchico per classificazione e organizzazione prodotti',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'categorie-clienti' => [
                'modello' => \App\Models\CustomerCategory::class,
                'nome' => 'Categorie Clienti',
                'nome_singolare' => 'Categoria Clienti',
                'icona' => 'bi-people',
                'colore' => 'warning',
                'color_from' => '#f093fb',
                'color_to' => '#f5576c',
                'descrizione' => 'Segmentazione e classificazione clienti per gestione commerciale',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'categorie-fornitori' => [
                'modello' => \App\Models\SupplierCategory::class,
                'nome' => 'Categorie Fornitori',
                'nome_singolare' => 'Categoria Fornitori',
                'icona' => 'bi-person-badge',
                'colore' => 'purple',
                'color_from' => '#9c27b0',
                'color_to' => '#7b1fa2',
                'descrizione' => 'Classificazione e gestione categorie fornitori per procurement',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'taglie-colori' => [
                'modello' => \App\Models\SizeColor::class,
                'nome' => 'Taglie e Colori',
                'nome_singolare' => 'Taglia/Colore',
                'icona' => 'bi-palette',
                'colore' => 'warning',
                'color_from' => '#ffecd2',
                'color_to' => '#fcb69f',
                'descrizione' => 'Gestione semplificata taglie e colori con solo descrizione',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'causali-magazzino' => [
                'modello' => \App\Models\WarehouseCause::class,
                'nome' => 'Causali di Magazzino',
                'nome_singolare' => 'Causale Magazzino',
                'icona' => 'bi-building',
                'colore' => 'danger',
                'color_from' => '#ee0979',
                'color_to' => '#ff6a00',
                'descrizione' => 'Gestione semplificata causali di magazzino con codice e descrizione',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'colori-varianti' => [
                'modello' => \App\Models\ColorVariant::class,
                'nome' => 'Colori Varianti',
                'nome_singolare' => 'Colore Variante',
                'icona' => 'bi-droplet-fill',
                'colore' => 'info',
                'color_from' => '#a8edea',
                'color_to' => '#fed6e3',
                'descrizione' => 'Gestione semplice colori per varianti prodotti',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'condizioni' => [
                'modello' => \App\Models\Condition::class,
                'nome' => 'Condizioni',
                'nome_singolare' => 'Condizione',
                'icona' => 'bi-list-ul',
                'colore' => 'info',
                'color_from' => '#00f2fe',
                'color_to' => '#4facfe',
                'descrizione' => 'Gestione semplice condizioni di pagamento e vendita',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'denominazioni-prezzi-fissi' => [
                'modello' => \App\Models\FixedPriceDenomination::class,
                'nome' => 'Denominazione Prezzi Fissi',
                'nome_singolare' => 'Denominazione',
                'icona' => 'bi-currency-euro',
                'colore' => 'brown',
                'color_from' => '#8b4513',
                'color_to' => '#d2691e',
                'descrizione' => 'Gestione denominazioni con descrizione e commento per prezzi fissi',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ]
            ],
            'depositi' => [
                'modello' => \App\Models\Deposit::class,
                'nome' => 'Depositi',
                'nome_singolare' => 'Deposito',
                'icona' => 'bi-archive',
                'colore' => 'orange',
                'color_from' => '#ff8c00',
                'color_to' => '#ff4500',
                'descrizione' => 'Gestione depositi e ubicazioni magazzino',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'indirizzo' => 'Indirizzo',
                    'localita' => 'Località',
                    'stato' => 'Stato',
                    'provincia' => 'Provincia',
                    'cap' => 'CAP',
                    'telefono' => 'Telefono',
                    'fax' => 'Fax'
                ]
            ],
            'listini' => [
                'modello' => \App\Models\PriceList::class,
                'nome' => 'Listini',
                'nome_singolare' => 'Listino',
                'icona' => 'bi-list-columns',
                'colore' => 'teal',
                'color_from' => '#2d6a4f',
                'color_to' => '#1b4332',
                'descrizione' => 'Gestione listini con descrizione e percentuale',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'percentuale' => 'Percentuale'
                ]
            ],
            'modalita-pagamento' => [
                'modello' => \App\Models\PaymentMethod::class,
                'nome' => 'Modalità di Pagamento',
                'nome_singolare' => 'Modalità di Pagamento',
                'icona' => 'bi-wallet2',
                'colore' => 'dark',
                'color_from' => '#2c2c2c',
                'color_to' => '#1a1a1a',
                'descrizione' => 'Gestione modalità di pagamento con codice, descrizione e checkbox banca',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'banca' => 'Banca'
                ],
                'campi_con_tipo' => [
                    'banca' => 'checkbox'
                ]
            ],
            'natura-iva' => [
                'modello' => \App\Models\VatNature::class,
                'nome' => 'Natura IVA',
                'nome_singolare' => 'Natura IVA',
                'icona' => 'bi-receipt',
                'colore' => 'pink',
                'color_from' => '#fadadd',
                'color_to' => '#f8bbd9',
                'descrizione' => 'Gestione nature IVA con codice, percentuale e riferimenti normativi',
                'campi_visibili' => [
                    'vat_code' => 'Cod.IVA',
                    'percentage' => '%',
                    'nature' => 'Natura',
                    'legal_reference' => 'Riferimento Normativo'
                ]
            ],
            'porto' => [
                'modello' => \App\Models\Porto::class,
                'nome' => 'Porto',
                'nome_singolare' => 'Porto',
                'icona' => 'bi-truck',
                'colore' => 'steel',
                'color_from' => '#6b7280',
                'color_to' => '#4b5563',
                'descrizione' => 'Gestione condizioni di trasporto e consegna merci: franco fabbrica, franco destino, porto assegnato',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ]
            ],
            'settori-merceologici' => [
                'modello' => \App\Models\MerchandiseSector::class,
                'nome' => 'Settori Merceologici',
                'nome_singolare' => 'Settore Merceologico',
                'icona' => 'bi-diagram-3',
                'colore' => 'purple',
                'color_from' => '#9c27b0',
                'color_to' => '#673ab7',
                'descrizione' => 'Classificazione settori merceologici per categorizzazione prodotti e clienti',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione'
                ]
            ],
            'taglie-varianti' => [
                'modello' => \App\Models\SizeVariant::class,
                'nome' => 'Taglie Varianti',
                'nome_singolare' => 'Taglia Variante',
                'icona' => 'bi-rulers',
                'colore' => 'info',
                'color_from' => '#17a2b8',
                'color_to' => '#138496',
                'descrizione' => 'Gestione taglie e sistemi di misurazione per varianti prodotto',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'tipo-di-taglie' => [
                'modello' => \App\Models\SizeType::class,
                'nome' => 'Tipo di Taglie',
                'nome_singolare' => 'Tipo di Taglia',
                'icona' => 'bi-tags',
                'colore' => 'purple',
                'color_from' => '#8B5CF6',
                'color_to' => '#A855F7',
                'descrizione' => 'Gestione tipologie di classificazione taglie',
                'campi_visibili' => [
                    'description' => 'Descrizione'
                ]
            ],
            'tipi-pagamento' => [
                'modello' => \App\Models\PaymentType::class,
                'nome' => 'Tipi di Pagamento',
                'nome_singolare' => 'Tipo di Pagamento',
                'icona' => 'bi-credit-card-2-front',
                'colore' => 'emerald',
                'color_from' => '#10b981',
                'color_to' => '#059669',
                'descrizione' => 'Gestione piani di pagamento rateizzati con percentuali personalizzate',
                'campi_visibili' => [
                    'code' => 'Codice',
                    'description' => 'Descrizione',
                    'total_installments' => 'Rate',
                    'end_payment' => 'Fine Lavori'
                ],
                'campi_con_tipo' => [
                    'end_payment' => 'checkbox'
                ]
            ],
            'trasporto' => [
                'modello' => \App\Models\Transport::class,
                'nome' => 'Trasporto',
                'nome_singolare' => 'Modalità di Trasporto',
                'icona' => 'bi-truck',
                'colore' => 'indigo',
                'color_from' => '#6366f1',
                'color_to' => '#4f46e5',
                'descrizione' => 'Gestione modalità di trasporto e spedizione',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ],
                'campi_con_tipo' => [
                    'comment' => 'textarea'
                ]
            ],
            'trasporto-a-mezzo' => [
                'modello' => \App\Models\TransportMeans::class,
                'nome' => 'Trasporto a Mezzo',
                'nome_singolare' => 'Trasporto a Mezzo',
                'icona' => 'bi-globe',
                'colore' => 'cyan',
                'color_from' => '#06b6d4',
                'color_to' => '#0891b2',
                'descrizione' => 'Gestione modalità di trasporto a mezzo (mare, aria, terra, ferro)',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ],
                'campi_con_tipo' => [
                    'comment' => 'textarea'
                ]
            ],
            'ubicazioni' => [
                'modello' => \App\Models\Location::class,
                'nome' => 'Ubicazioni',
                'nome_singolare' => 'Ubicazione',
                'icona' => 'bi-geo-alt',
                'colore' => 'amber',
                'color_from' => '#f59e0b',
                'color_to' => '#d97706',
                'descrizione' => 'Gestione ubicazioni magazzino con deposito e posizione specifica',
                'campi_visibili' => [
                    'dep' => 'Deposito',
                    'ubicazione' => 'Ubicazione'
                ]
            ],
            'unita-di-misura' => [
                'modello' => \App\Models\UnitOfMeasure::class,
                'nome' => 'Unità di Misura',
                'nome_singolare' => 'Unità di Misura',
                'icona' => 'bi-calculator',
                'colore' => 'purple',
                'color_from' => '#8b5cf6',
                'color_to' => '#7c3aed',
                'descrizione' => 'Gestione unità di misura per prodotti (kg, litri, metri, pezzi)',
                'campi_visibili' => [
                    'description' => 'Descrizione',
                    'comment' => 'Commento'
                ],
                'campi_con_tipo' => [
                    'comment' => 'textarea'
                ]
            ],
            'valute' => [
                'modello' => \App\Models\Currency::class,
                'nome' => 'Valute',
                'nome_singolare' => 'Valuta',
                'icona' => 'bi-currency-exchange',
                'colore' => 'emerald',
                'color_from' => '#10b981',
                'color_to' => '#059669',
                'descrizione' => 'Gestione valute e tassi di cambio per commercio internazionale',
                'campi_visibili' => [
                    'valuta' => 'Valuta',
                    'conversione' => 'Tasso di Conversione',
                    'descrizione' => 'Descrizione'
                ],
                'campi_con_tipo' => [
                    'conversione' => 'number'
                ]
            ],
            'zone' => [
                'modello' => \App\Models\Zone::class,
                'nome' => 'Zone',
                'nome_singolare' => 'Zona',
                'icona' => 'bi-map',
                'colore' => 'orange',
                'color_from' => '#f97316',
                'color_to' => '#ea580c',
                'descrizione' => 'Gestione zone geografiche e commerciali per organizzazione territoriale',
                'campi_visibili' => [
                    'codice' => 'Codice',
                    'descrizione' => 'Descrizione'
                ]
            ]
        ];
    }
    public function __construct()
    {
        // Versione semplificata senza dependency injection complessa per evitare loop
        // Disabilitato temporaneamente per test: $this->middleware('auth');
    }

    /**
     * Dashboard principale gestione tabelle
     */
    public function index(): View
    {
        try {
            // Lista tabelle con colori specifici del vecchio modulo
            $tabelleDisponibili = collect([
                [
                    'nome' => 'associazioni-nature-iva',
                    'titolo' => 'Associazioni Nature IVA',
                    'icona' => 'bi-link-45deg',
                    'colore' => 'primary',
                    'descrizione' => 'Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali',
                    'color_from' => '#4ecdc4',
                    'color_to' => '#44a08d'
                ],
                [
                    'nome' => 'aliquote-iva',
                    'titolo' => 'Aliquote IVA',
                    'icona' => 'bi-percent',
                    'colore' => 'danger',
                    'descrizione' => 'Gestione semplificata delle aliquote IVA del sistema',
                    'color_from' => '#dc3545',
                    'color_to' => '#c82333'
                ],
                [
                    'nome' => 'aspetto-beni',
                    'titolo' => 'Aspetto dei Beni',
                    'icona' => 'bi-box-seam',
                    'colore' => 'warning',
                    'descrizione' => 'Gestione aspetto e caratteristiche dei beni',
                    'color_from' => '#ffb500',
                    'color_to' => '#ffd700'
                ],
                [
                    'nome' => 'banche',
                    'titolo' => 'Banche',
                    'icona' => 'bi-bank',
                    'colore' => 'info',
                    'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
                    'color_from' => '#48cae4',
                    'color_to' => '#023e8a'
                ],
                [
                    'nome' => 'categorie-articoli',
                    'titolo' => 'Categorie Articoli',
                    'icona' => 'bi-grid-3x3-gap',
                    'colore' => 'success',
                    'descrizione' => 'Sistema gerarchico per classificazione e organizzazione prodotti',
                    'color_from' => '#38b000',
                    'color_to' => '#70e000'
                ],
                [
                    'nome' => 'categorie-clienti',
                    'titolo' => 'Categorie Clienti',
                    'icona' => 'bi-people',
                    'colore' => 'warning',
                    'descrizione' => 'Segmentazione e classificazione clienti per gestione commerciale',
                    'color_from' => '#f093fb',
                    'color_to' => '#f5576c'
                ],
                [
                    'nome' => 'categorie-fornitori',
                    'titolo' => 'Categorie Fornitori',
                    'icona' => 'bi-person-badge',
                    'colore' => 'purple',
                    'descrizione' => 'Classificazione e gestione categorie fornitori per procurement',
                    'color_from' => '#9c27b0',
                    'color_to' => '#7b1fa2'
                ],
                [
                    'nome' => 'taglie-colori',
                    'titolo' => 'Taglie e Colori',
                    'icona' => 'bi-palette',
                    'colore' => 'warning',
                    'descrizione' => 'Gestione varianti prodotto: taglie e colori per catalogazione articoli',
                    'color_from' => '#ffecd2',
                    'color_to' => '#fcb69f'
                ],
                [
                    'nome' => 'causali-magazzino',
                    'titolo' => 'Causali di Magazzino',
                    'icona' => 'bi-building',
                    'colore' => 'danger',
                    'descrizione' => 'Classificazione movimenti di magazzino: carico, scarico, trasferimenti',
                    'color_from' => '#ee0979',
                    'color_to' => '#ff6a00'
                ],
                [
                    'nome' => 'colori-varianti',
                    'titolo' => 'Colori Varianti',
                    'icona' => 'bi-droplet-fill',
                    'colore' => 'info',
                    'descrizione' => 'Gestione semplice colori per varianti prodotti',
                    'color_from' => '#a8edea',
                    'color_to' => '#fed6e3'
                ],
                [
                    'nome' => 'condizioni',
                    'titolo' => 'Condizioni',
                    'icona' => 'bi-list-ul',
                    'colore' => 'info',
                    'descrizione' => 'Gestione semplice condizioni di pagamento e vendita',
                    'color_from' => '#00f2fe',
                    'color_to' => '#4facfe'
                ],
                [
                    'nome' => 'denominazioni-prezzi-fissi',
                    'titolo' => 'Denominazione Prezzi Fissi',
                    'icona' => 'bi-currency-euro',
                    'colore' => 'brown',
                    'descrizione' => 'Gestione denominazioni con descrizione e commento per prezzi fissi',
                    'color_from' => '#8b4513',
                    'color_to' => '#d2691e'
                ],
                [
                    'nome' => 'depositi',
                    'titolo' => 'Depositi',
                    'icona' => 'bi-archive',
                    'colore' => 'orange',
                    'descrizione' => 'Gestione depositi e ubicazioni magazzino',
                    'color_from' => '#ff8c00',
                    'color_to' => '#ff4500'
                ],
                [
                    'nome' => 'listini',
                    'titolo' => 'Listini',
                    'icona' => 'bi-list-columns',
                    'colore' => 'teal',
                    'descrizione' => 'Gestione listini con descrizione e percentuale',
                    'color_from' => '#2d6a4f',
                    'color_to' => '#1b4332'
                ],
                [
                    'nome' => 'modalita-pagamento',
                    'titolo' => 'Modalità di Pagamento',
                    'icona' => 'bi-wallet2',
                    'colore' => 'dark',
                    'descrizione' => 'Gestione modalità di pagamento con codice e descrizione',
                    'color_from' => '#2c2c2c',
                    'color_to' => '#1a1a1a'
                ],
                [
                    'nome' => 'natura-iva',
                    'titolo' => 'Natura IVA',
                    'icona' => 'bi-receipt',
                    'colore' => 'pink',
                    'descrizione' => 'Gestione nature IVA con codice, percentuale e riferimenti normativi',
                    'color_from' => '#fadadd',
                    'color_to' => '#f8bbd9'
                ],
                [
                    'nome' => 'porto',
                    'titolo' => 'Porto',
                    'icona' => 'bi-truck',
                    'colore' => 'steel',
                    'descrizione' => 'Gestione condizioni di trasporto e consegna merci: franco fabbrica, franco destino, porto assegnato',
                    'color_from' => '#6b7280',
                    'color_to' => '#4b5563'
                ],
                [
                    'nome' => 'settori-merceologici',
                    'titolo' => 'Settori Merceologici',
                    'icona' => 'bi-diagram-3',
                    'colore' => 'purple',
                    'descrizione' => 'Classificazione settori merceologici per categorizzazione prodotti e clienti',
                    'color_from' => '#9c27b0',
                    'color_to' => '#673ab7'
                ],
                [
                    'nome' => 'taglie-varianti',
                    'titolo' => 'Taglie Varianti',
                    'icona' => 'bi-rulers',
                    'colore' => 'info',
                    'descrizione' => 'Gestione taglie e sistemi di misurazione per varianti prodotto',
                    'color_from' => '#17a2b8',
                    'color_to' => '#138496'
                ],
                [
                    'nome' => 'tipo-di-taglie',
                    'titolo' => 'Tipo di Taglie',
                    'icona' => 'bi-tags',
                    'colore' => 'purple',
                    'descrizione' => 'Gestione tipologie di classificazione taglie',
                    'color_from' => '#8B5CF6',
                    'color_to' => '#A855F7'
                ],
                [
                    'nome' => 'tipi-pagamento',
                    'titolo' => 'Tipi di Pagamento',
                    'icona' => 'bi-credit-card-2-front',
                    'colore' => 'emerald',
                    'descrizione' => 'Gestione piani di pagamento rateizzati con percentuali personalizzate',
                    'color_from' => '#10b981',
                    'color_to' => '#059669'
                ],
                [
                    'nome' => 'trasporto',
                    'titolo' => 'Trasporto',
                    'icona' => 'bi-truck',
                    'colore' => 'indigo',
                    'descrizione' => 'Gestione modalità di trasporto e spedizione',
                    'color_from' => '#6366f1',
                    'color_to' => '#4f46e5'
                ],
                [
                    'nome' => 'trasporto-a-mezzo',
                    'titolo' => 'Trasporto a Mezzo',
                    'icona' => 'bi-globe',
                    'colore' => 'cyan',
                    'descrizione' => 'Gestione modalità di trasporto a mezzo (mare, aria, terra, ferro)',
                    'color_from' => '#06b6d4',
                    'color_to' => '#0891b2'
                ],
                [
                    'nome' => 'ubicazioni',
                    'titolo' => 'Ubicazioni',
                    'icona' => 'bi-geo-alt',
                    'colore' => 'amber',
                    'descrizione' => 'Gestione ubicazioni magazzino con deposito e posizione specifica',
                    'color_from' => '#f59e0b',
                    'color_to' => '#d97706'
                ],
                [
                    'nome' => 'unita-di-misura',
                    'titolo' => 'Unità di Misura',
                    'icona' => 'bi-calculator',
                    'colore' => 'purple',
                    'descrizione' => 'Gestione unità di misura per prodotti (kg, litri, metri, pezzi)',
                    'color_from' => '#8b5cf6',
                    'color_to' => '#7c3aed'
                ],
                [
                    'nome' => 'valute',
                    'titolo' => 'Valute',
                    'icona' => 'bi-currency-exchange',
                    'colore' => 'emerald',
                    'descrizione' => 'Gestione valute e tassi di cambio per commercio internazionale',
                    'color_from' => '#10b981',
                    'color_to' => '#059669'
                ],
                [
                    'nome' => 'zone',
                    'titolo' => 'Zone',
                    'icona' => 'bi-map',
                    'colore' => 'orange',
                    'descrizione' => 'Gestione zone geografiche e commerciali per organizzazione territoriale',
                    'color_from' => '#f97316',
                    'color_to' => '#ea580c'
                ]
            ]);
            
            // ========================================
            // CARICAMENTO TABELLE FREQUENTI V2 - SENZA LIMITI
            // ========================================
            $favoriteTablesV2 = [];
            try {
                if (auth()->check()) {
                    $favoriteTablesV2 = \App\Models\UserFavoriteTable::getFavoritesWithDetailsV2(auth()->id());
                    
                    // Arricchimento con configurazioni tabelle per UI
                    $configurazioni = $this->getConfigurazioni();
                    $favoriteTablesV2 = $favoriteTablesV2->map(function ($favorite) use ($configurazioni) {
                        $tableConfig = $configurazioni[$favorite['table_objname']] ?? null;
                        if ($tableConfig) {
                            $favorite['table_details'] = [
                                'nome' => $tableConfig['nome'],
                                'nome_singolare' => $tableConfig['nome_singolare'],
                                'icona' => $tableConfig['icona'],
                                'colore' => $tableConfig['colore'],
                                'color_from' => $tableConfig['color_from'],
                                'color_to' => $tableConfig['color_to'],
                                'descrizione' => $tableConfig['descrizione']
                            ];
                        }
                        return $favorite;
                    });
                }
            } catch (\Exception $e) {
                Log::warning('Errore caricamento tabelle frequenti V2:', [
                    'errore' => $e->getMessage(),
                    'user_id' => auth()->id()
                ]);
                $favoriteTablesV2 = collect(); // Fallback vuoto
            }

            return view('configurazioni.gestione-tabelle.index', [
                'tabelle' => $tabelleDisponibili,
                'favoriteTablesV2' => $favoriteTablesV2, // NUOVO: tabelle frequenti senza limiti
                'title' => 'Gestione Tabelle di Sistema V2',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Configurazioni', 'url' => route('configurations.index')],
                    ['title' => 'Gestione Tabelle V2', 'active' => true]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Errore dashboard gestione tabelle', [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id() // Commentato per test senza auth
            ]);
            
            throw $e;
        }
    }

    /**
     * Visualizza elenco elementi di una tabella specifica
     */
    public function mostraTabella(string $nomeTabella, Request $request): View|RedirectResponse
    {
        try {
            // Supporto per le tabelle implementate
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'aspetto-beni', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini', 'modalita-pagamento', 'natura-iva', 'porto', 'settori-merceologici', 'taglie-varianti', 'tipo-di-taglie', 'tipi-pagamento', 'trasporto', 'trasporto-a-mezzo', 'ubicazioni', 'unita-di-misura', 'valute', 'zone'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // ========================================
            // TRACKING AUTOMATICO UTILIZZO V2
            // ========================================
            if (auth()->check()) {
                try {
                    // Tracking asincrono in background per non rallentare la risposta
                    $favorite = \App\Models\UserFavoriteTable::firstOrCreate(
                        [
                            'user_id' => auth()->id(),
                            'table_objname' => $nomeTabella
                        ],
                        [
                            'uuid' => \Illuminate\Support\Str::uuid(),
                            'sort_order' => 0,
                            'click_count' => 0
                        ]
                    );

                    // Incrementa utilizzo
                    $favorite->incrementUsage();

                    // Log solo per debug se necessario
                    if ($favorite->click_count % 10 == 0) { // Log ogni 10 utilizzi
                        Log::info('Tabella V2 molto utilizzata:', [
                            'user_id' => auth()->id(),
                            'table' => $nomeTabella,
                            'clicks' => $favorite->click_count
                        ]);
                    }

                } catch (\Exception $e) {
                    // Non bloccare l'accesso alla tabella per errori di tracking
                    Log::warning('Errore tracking automatico V2:', [
                        'errore' => $e->getMessage(),
                        'table' => $nomeTabella,
                        'user_id' => auth()->id()
                    ]);
                }
            }

            // Gestione unificata per TUTTE le tabelle v2
            return $this->gestisciTabellaV2($nomeTabella, $request);
            
        } catch (\Exception $e) {
            Log::error("Errore visualizzazione tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id(), // Commentato per test senza auth
                'request' => $request->all()
            ]);
            
            return back()->with('error', 'Errore caricamento dati tabella');
        }
    }

    /**
     * Form creazione nuovo elemento
     */
    public function create(string $nomeTabella): View|RedirectResponse
    {
        try {
            // Supporto per le tabelle v2
            if (!in_array($nomeTabella, ['associazioni-nature-iva', 'aliquote-iva', 'aspetto-beni', 'banche', 'categorie-articoli', 'categorie-clienti', 'categorie-fornitori', 'taglie-colori', 'causali-magazzino', 'colori-varianti', 'condizioni', 'denominazioni-prezzi-fissi', 'depositi', 'listini', 'modalita-pagamento', 'natura-iva', 'porto', 'settori-merceologici', 'taglie-varianti', 'tipo-di-taglie', 'tipi-pagamento', 'trasporto', 'trasporto-a-mezzo', 'ubicazioni', 'unita-di-misura', 'valute', 'zone'])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }

            // Usa la configurazione centrale
            $configurazioni = $this->getConfigurazioni();

            $configurazione = $configurazioni[$nomeTabella];
            
            // Logica corretta per il genere
            $nomeSingolare = $configurazione['nome_singolare'];
            $paroleFemminili = ['Associazione', 'Natura', 'Aliquota', 'Banca', 'Categoria', 'Taglia', 'Causale', 'Condizione', 'Denominazione', 'Modalità'];
            $isFemminile = str_ends_with($nomeSingolare, 'a') || in_array(explode(' ', $nomeSingolare)[0], $paroleFemminili);
            $articolo = $isFemminile ? 'Nuova' : 'Nuovo';
            
            // Dati extra per dropdown specifici
            $extraData = [];
            if ($nomeTabella === 'associazioni-nature-iva') {
                $extraData['aliquote_iva'] = \App\Models\TaxRate::where('active', true)->get();
                $extraData['nature_iva'] = \App\Models\VatNature::all();
            }
            
            return view('configurazioni.gestione-tabelle.create', [
                'nomeTabella' => $nomeTabella,
                'configurazione' => $configurazione,
                'extraData' => $extraData,
                'title' => $articolo . ' ' . $configurazione['nome_singolare'],
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Gestione Tabelle', 'url' => route('configurations.gestione-tabelle.index')],
                    ['title' => $configurazione['nome'], 'url' => route('configurations.gestione-tabelle.tabella', $nomeTabella)],
                    ['title' => $articolo . ' ' . $configurazione['nome_singolare'], 'active' => true]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore form creazione {$nomeTabella}", [
                'errore' => $e->getMessage(),
                // 'utente_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Errore caricamento form');
        }
    }

    /**
     * Salva nuovo elemento
     */
    public function store(string $nomeTabella, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // ========================================
            // SISTEMA V2 UNIFICATO - GESTIONE DINAMICA
            // ========================================
            
            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$nomeTabella])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }
            
            $configurazione = $configurazioni[$nomeTabella];
            $modelClass = $configurazione['modello'];
            
            // Gestione caso speciale: Associazioni Nature IVA (logica complessa)
            if ($nomeTabella === 'associazioni-nature-iva') {
                return $this->storeAssociazioniNatureIva($request);
            }
            
            // ========================================
            // LOGICA UNIFICATA PER TUTTE LE ALTRE TABELLE
            // ========================================
            
            try {
                // Validazione dinamica tramite modello
                $validated = $request->validate($modelClass::validationRules());
                
                // Gestione dati extra per dropdown specifici
                $extraData = [];
                if ($nomeTabella === 'associazioni-nature-iva') {
                    $extraData['aliquote_iva'] = \App\Models\TaxRate::where('active', true)->get();
                    $extraData['nature_iva'] = \App\Models\VatNature::all();
                }
                
                // Creazione elemento tramite modello dinamico
                $elemento = $modelClass::create($validated);
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "{$configurazione['nome_singolare']} creato con successo",
                        'data' => $elemento
                    ]);
                }
                
                // Redirect con messaggio di successo
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('success', "{$configurazione['nome_singolare']} creato con successo");
                    
            } catch (\Exception $e) {
                Log::error("Errore creazione {$nomeTabella}", [
                    'errore' => $e->getMessage(),
                    'dati' => $validated ?? []
                ]);
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errore durante la creazione',
                        'errors' => ['general' => $e->getMessage()]
                    ], 500);
                }
                
                // Redirect con errore
                return back()
                    ->withErrors(['general' => 'Errore durante la creazione: ' . $e->getMessage()])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Errore store gestione tabelle', [
                'errore' => $e->getMessage(),
                'tabella' => $nomeTabella
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Gestione specifica per Associazioni Nature IVA (logica complessa)
     */
    private function storeAssociazioniNatureIva(Request $request)
    {
        // Validazione specifica per Associazioni Nature IVA
        $validated = $request->validate([
            'nome_associazione' => 'required|string|min:3|max:255',
            'descrizione' => 'nullable|string|max:500',
            'tax_rate_id' => 'required|integer|exists:tax_rates,id',
            'vat_nature_id' => 'required|integer|exists:vat_natures,id',
            'is_default' => 'nullable|boolean'
        ]);

        // Verifica duplicati
        $exists = VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                     ->where('vat_nature_id', $validated['vat_nature_id'])
                                     ->where('active', true)
                                     ->exists();

        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.',
                    'errors' => ['duplicate' => 'Associazione già esistente']
                ], 422);
            }
            
            return back()->withErrors([
                'duplicate' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.'
            ])->withInput();
        }

        // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
        if (!empty($validated['is_default'])) {
            VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                 ->update(['is_default' => false]);
        }

        try {
            $elemento = VatNatureAssociation::create([
                'nome_associazione' => $validated['nome_associazione'],
                'name' => $validated['nome_associazione'],
                'descrizione' => $validated['descrizione'] ?? null,
                'tax_rate_id' => $validated['tax_rate_id'],
                'vat_nature_id' => $validated['vat_nature_id'],
                'is_default' => !empty($validated['is_default']),
                    'active' => true
            ]);
            
            $messaggio = 'Associazione creata con successo';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $messaggio,
                    'data' => $elemento
                ]);
            }
            
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'associazioni-nature-iva')
                ->with('success', $messaggio);
                
        } catch (\Exception $e) {
            Log::error("Errore creazione associazione natura IVA", [
                'errore' => $e->getMessage(),
                'dati' => $validated
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione',
                    'errors' => ['general' => $e->getMessage()]
                ], 500);
            }
            
            return back()
                ->withErrors(['general' => 'Errore durante la creazione: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Visualizza dettagli elemento
     */
    public function show(string $nomeTabella, int $id, Request $request): JsonResponse|RedirectResponse|View
    {
        try {
            // ========================================
            // SISTEMA V2 UNIFICATO - SHOW DINAMICO
            // ========================================
            
            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$nomeTabella])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tabella {$nomeTabella} non supportata"
                    ], 404);
                }
                
                return redirect()
                    ->route('configurations.gestione-tabelle.index')
                    ->with('error', "Tabella {$nomeTabella} non supportata");
            }
            
            $configurazione = $configurazioni[$nomeTabella];
            $modelClass = $configurazione['modello'];
            
            // Carica elemento con relazioni se necessario
            if ($nomeTabella === 'associazioni-nature-iva') {
                $element = $modelClass::with(['taxRate', 'vatNature'])->findOrFail($id);
            } else {
                $element = $modelClass::findOrFail($id);
            }

            // Se richiede JSON, restituisci JSON
            if ($request->expectsJson()) {
                return response()->json($element);
            }
            
            // Per richieste normali, mostra la pagina di visualizzazione
            return view('configurazioni.gestione-tabelle.show', [
                'nomeTabella' => $nomeTabella,
                'configurazione' => $configurazione,
                'elemento' => $element,
                'title' => 'Visualizza ' . $configurazione['nome_singolare'],
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Gestione Tabelle', 'url' => route('configurations.gestione-tabelle.index')],
                    ['title' => $configurazione['nome'], 'url' => route('configurations.gestione-tabelle.tabella', $nomeTabella)],
                    ['title' => 'Visualizza ' . $configurazione['nome_singolare'], 'active' => true]
                ]
            ]);
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Elemento non trovato'], 404);
            }
            
            return redirect()->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ->with('error', 'Elemento non trovato');
        }
    }

    /**
     * Form modifica elemento
     */
    public function edit(string $nomeTabella, int $id): View|RedirectResponse
    {
        try {
            // ========================================
            // SISTEMA V2 UNIFICATO - EDIT DINAMICO
            // ========================================
            
            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$nomeTabella])) {
                return redirect()
                    ->route('configurations.gestione-tabelle.index')
                    ->with('error', "Tabella {$nomeTabella} non supportata");
            }
            
            $configurazione = $configurazioni[$nomeTabella];
            $modelClass = $configurazione['modello'];
            
            // Carica elemento per modifica
            if ($nomeTabella === 'associazioni-nature-iva') {
                $element = $modelClass::with(['taxRate', 'vatNature'])->findOrFail($id);
            } else {
                $element = $modelClass::findOrFail($id);
            }
            
            // Dati extra per dropdown specifici  
            $extraData = [];
            if ($nomeTabella === 'associazioni-nature-iva') {
                $extraData['aliquote_iva'] = \App\Models\TaxRate::where('active', true)->get();
                $extraData['nature_iva'] = \App\Models\VatNature::all();
            }
            
            return view('configurazioni.gestione-tabelle.edit', array_merge([
                'nomeTabella' => $nomeTabella,
                'configurazione' => $configurazione,
                'elemento' => $element,
                'title' => 'Modifica ' . $configurazione['nome_singolare'],
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Gestione Tabelle', 'url' => route('configurations.gestione-tabelle.index')],
                    ['title' => $configurazione['nome'], 'url' => route('configurations.gestione-tabelle.tabella', $nomeTabella)],
                    ['title' => 'Modifica ' . $configurazione['nome_singolare'], 'active' => true]
                ]
            ], $extraData));
                
        } catch (\Exception $e) {
            return redirect()->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                ->with('error', 'Elemento non trovato');
        }
    }

    /**
     * Aggiorna elemento esistente
     */
    public function update(string $nomeTabella, int $id, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // ========================================
            // SISTEMA V2 UNIFICATO - UPDATE DINAMICO
            // ========================================
            
            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$nomeTabella])) {
                abort(404, "Tabella {$nomeTabella} non ancora implementata");
            }
            
            $configurazione = $configurazioni[$nomeTabella];
            $modelClass = $configurazione['modello'];
            
            // Gestione caso speciale: Associazioni Nature IVA (logica complessa)
            if ($nomeTabella === 'associazioni-nature-iva') {
                return $this->updateAssociazioneNatureIva($request, $id);
            }
            
            // ========================================
            // LOGICA UNIFICATA PER TUTTE LE ALTRE TABELLE
            // ========================================
            
            try {
                // Trova l'elemento esistente
                $elemento = $modelClass::findOrFail($id);
                
                // Validazione dinamica tramite modello
                $validated = $request->validate($modelClass::validationRulesForUpdate($id));
                
                // Aggiornamento elemento
                $elemento->update($validated);
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "{$configurazione['nome_singolare']} aggiornato con successo",
                        'data' => $elemento->fresh()
                    ]);
                }
                
                // Redirect con messaggio di successo
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('success', "{$configurazione['nome_singolare']} aggiornato con successo");
                    
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Elemento non trovato'
                    ], 404);
                }
                
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('error', 'Elemento non trovato');
                    
            } catch (\Exception $e) {
                Log::error("Errore aggiornamento {$nomeTabella}:{$id}", [
                    'errore' => $e->getMessage(),
                    'dati' => $validated ?? []
                ]);
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errore durante l\'aggiornamento',
                        'errors' => ['general' => $e->getMessage()]
                    ], 500);
                }
                
                // Redirect con errore
                return back()
                    ->withErrors(['general' => 'Errore durante l\'aggiornamento: ' . $e->getMessage()])
                    ->withInput();
            }
            
        } catch (\Exception $e) {
            Log::error("Errore aggiornamento elemento {$nomeTabella}:{$id}", [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'aggiornamento')->withInput();
        }
    }

    /**
     * Elimina elemento
     */
    public function destroy(string $nomeTabella, int $id, Request $request): RedirectResponse|JsonResponse
    {
        try {
            // ========================================
            // SISTEMA V2 UNIFICATO - DELETE DINAMICO
            // ========================================
            
            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$nomeTabella])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tabella {$nomeTabella} non supportata"
                    ], 404);
                }
                
                return back()->with('error', "Tabella {$nomeTabella} non supportata");
            }
            
            $configurazione = $configurazioni[$nomeTabella];
            $modelClass = $configurazione['modello'];
            
            // ========================================
            // LOGICA UNIFICATA PER TUTTE LE TABELLE
            // ========================================
            
            try {
                // Trova e elimina l'elemento
                $elemento = $modelClass::findOrFail($id);
                $elemento->delete();
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "{$configurazione['nome_singolare']} eliminato con successo"
                    ]);
                }
                
                // Redirect con messaggio di successo
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('success', "{$configurazione['nome_singolare']} eliminato con successo");
                    
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Elemento non trovato'
                    ], 404);
                }
                
                return redirect()
                    ->route('configurations.gestione-tabelle.tabella', $nomeTabella)
                    ->with('error', 'Elemento non trovato');
                    
            } catch (\Exception $e) {
                Log::error("Errore eliminazione {$nomeTabella}:{$id}", [
                    'errore' => $e->getMessage()
                ]);
                
                // Risposta JSON per richieste AJAX
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errore durante l\'eliminazione',
                        'errors' => ['general' => $e->getMessage()]
                    ], 500);
                }
                
                // Redirect con errore
                return back()->with('error', 'Errore durante l\'eliminazione: ' . $e->getMessage());
            }
            
        } catch (\Exception $e) {
            Log::error("Errore eliminazione elemento {$nomeTabella}:{$id}", [
                'errore' => $e->getMessage(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'eliminazione'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'eliminazione');
        }
    }

    /**
     * Esporta dati tabella
     */
    public function export(string $nomeTabella, Request $request)
    {
        try {
            $formato = $request->get('formato', 'excel');
            $percorsoFile = $this->gestioneService->esportaTabella($nomeTabella, $formato);
            
            $nomeFile = basename($percorsoFile);
            
            return response()->download($percorsoFile, $nomeFile)->deleteFileAfterSend();
            
        } catch (\Exception $e) {
            Log::error("Errore export tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'formato' => $request->get('formato'),
                'utente_id' => auth()->id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'esportazione'
                ], 500);
            }
            
            return back()->with('error', 'Errore durante l\'esportazione');
        }
    }

    /**
     * API: Ottieni statistiche tabella
     */
    public function statistiche(string $nomeTabella): JsonResponse
    {
        try {
            $statistiche = $this->gestioneService->ottieniStatistiche($nomeTabella);
            
            return response()->json([
                'success' => true,
                'data' => $statistiche
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore statistiche tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'utente_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore caricamento statistiche'
            ], 500);
        }
    }

    /**
     * API: Ricerca elementi
     */
    public function ricerca(string $nomeTabella, Request $request): JsonResponse
    {
        try {
            $dati = $this->gestioneService->ottieniDatiTabella($nomeTabella, $request);
            
            return response()->json([
                'success' => true,
                'data' => $dati->items(),
                'pagination' => [
                    'current_page' => $dati->currentPage(),
                    'last_page' => $dati->lastPage(),
                    'per_page' => $dati->perPage(),
                    'total' => $dati->total()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Errore ricerca tabella {$nomeTabella}", [
                'errore' => $e->getMessage(),
                'parametri' => $request->all(),
                'utente_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la ricerca'
            ], 500);
        }
    }
    /**
     * Gestisce la tabella Banche con dati e statistiche
     */
    private function gestisciBanche(Request $request): View
    {
        // Ottieni dati banche con paginazione e filtri
        $query = \App\Models\Bank::query();

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

        // Filtro attive
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        // Ordinamento
        $sortBy = $request->get('sort_by', 'nome_banca');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $dati = $query->paginate(20);

        // Configurazione tabella
        $configurazione = [
            'nome' => 'Banche',
            'icona' => 'bi-bank',
            'colore' => 'info',
            'descrizione' => 'Configurazione profili bancari aziendali per documenti e pagamenti',
            'color_from' => '#48cae4',
            'color_to' => '#023e8a',
            'campi_visibili' => [
                'nome_banca' => 'Nome Banca',
                'abi' => 'ABI',
                'cab' => 'CAB',
                'iban' => 'IBAN',
                'swift' => 'SWIFT',
                'active' => 'Attivo'
            ]
        ];

        return view('configurazioni.gestione-tabelle.tabella', [
            'dati' => $dati,
            'configurazione' => $configurazione,
            'nomeTabella' => 'banche'
        ]);
    }

    /**
     * Gestisce tutte le tabelle v2 in modo unificato e semplice
     */
    private function gestisciTabellaV2(string $nomeTabella, Request $request): View
    {
        // Usa configurazione centralizzata
        $configurazioni = $this->getConfigurazioni();

        $config = $configurazioni[$nomeTabella];
        $modelClass = $config['modello'];

        // Query con relazioni specifiche per ogni tabella
        if ($nomeTabella === 'associazioni-nature-iva') {
            $query = $modelClass::with(['taxRate', 'vatNature'])->where('active', true);
        } elseif ($nomeTabella === 'categorie-articoli') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'categorie-clienti') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'categorie-fornitori') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'taglie-colori') {
            $query = $modelClass::query();
        } elseif ($nomeTabella === 'causali-magazzino') {
            $query = $modelClass::query();
        } else {
            $query = $modelClass::query();
        }

        // Filtro ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            if ($nomeTabella === 'associazioni-nature-iva') {
                $query->where(function($q) use ($search) {
                    $q->where('nome_associazione', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'aliquote-iva') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'banche') {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('bic_swift', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-articoli') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-clienti') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'categorie-fornitori') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'taglie-colori') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('type', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'causali-magazzino') {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'colori-varianti') {
                $query->where(function($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%");
                });
            } elseif ($nomeTabella === 'condizioni') {
                $query->where(function($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%");
                });
            }
        }

        // Ordinamento unificato e intelligente
        $primiCampi = array_keys($config['campi_visibili']);
        $campoOrdinamento = $primiCampi[0] ?? 'id';
        $query->orderBy($campoOrdinamento, 'asc');

        $dati = $query->paginate(20);

        // Dati extra per Associazioni Nature IVA
        $extraData = [];
        if ($nomeTabella === 'associazioni-nature-iva') {
            $extraData['associations'] = $dati;
            $extraData['taxRates'] = \App\Models\TaxRate::where('active', true)->orderBy('percentuale')->get();
            $extraData['vatNatures'] = \App\Models\VatNature::orderBy('vat_code')->get();
        }

        return view('configurazioni.gestione-tabelle.tabella', array_merge([
            'dati' => $dati,
            'configurazione' => $config,
            'nomeTabella' => $nomeTabella
        ], $extraData));
    }
    /**
     * Update per Associazioni Nature IVA
     */
    public function updateAssociazioneNatureIva(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $associazione = VatNatureAssociation::findOrFail($id);
            
            // Validazione specifica per Associazioni Nature IVA
            $validated = $request->validate([
                'nome_associazione' => 'required|string|min:3|max:255',
                'descrizione' => 'nullable|string|max:500',
                'tax_rate_id' => 'required|integer|exists:tax_rates,id',
                'vat_nature_id' => 'required|integer|exists:vat_natures,id',
                'is_default' => 'nullable|boolean'
            ]);

            // Verifica duplicati (escluso l'ID corrente)
            $exists = VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                         ->where('vat_nature_id', $validated['vat_nature_id'])
                                         ->where('active', true)
                                         ->where('id', '!=', $id)
                                         ->exists();

            if ($exists) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.',
                        'errors' => ['duplicate' => 'Associazione già esistente']
                    ], 422);
                }
                
                return back()->withErrors([
                    'duplicate' => 'Esiste già un\'associazione tra questa aliquota IVA e natura IVA.'
                ])->withInput();
            }

            // Se viene impostata come predefinita, rimuovi il flag dalle altre della stessa aliquota
            if (!empty($validated['is_default'])) {
                VatNatureAssociation::where('tax_rate_id', $validated['tax_rate_id'])
                                     ->where('id', '!=', $id)
                                     ->update(['is_default' => false]);
            }

            $associazione->update([
                'nome_associazione' => $validated['nome_associazione'],
                'name' => $validated['nome_associazione'],
                'descrizione' => $validated['descrizione'] ?? null,
                'tax_rate_id' => $validated['tax_rate_id'],
                'vat_nature_id' => $validated['vat_nature_id'],
                'is_default' => !empty($validated['is_default'])
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Associazione aggiornata con successo',
                    'data' => $associazione
                ]);
            }

            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'associazioni-nature-iva')
                ->with('success', 'Associazione aggiornata con successo');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento associazione nature IVA', [
                'id' => $id,
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'aggiornamento'
                ], 500);
            }

            return back()
                ->withErrors(['generale' => 'Errore durante l\'aggiornamento dell\'associazione'])
                ->withInput();
        }
    }
    // =====================================================
    // METODI SPECIFICI PER DEPOSITI
    // =====================================================

    public function storeDepositi(Request $request): JsonResponse|RedirectResponse
    {
        try {
            // Validazione semplificata per Depositi
            $validated = $request->validate(\App\Models\Deposit::validationRules());

            $elemento = \App\Models\Deposit::create([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'],
                'indirizzo' => $validated['indirizzo'] ?? null,
                'localita' => $validated['localita'] ?? null,
                'stato' => $validated['stato'] ?? null,
                'provincia' => $validated['provincia'] ?? null,
                'cap' => $validated['cap'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'fax' => $validated['fax'] ?? null
            ]);
            
            \Log::info('Elemento creato:', $elemento->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposito creato con successo',
                    'data' => $elemento
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('success', 'Deposito creato con successo');
        } catch (ValidationException $e) {
            \Log::error('Errore validazione Depositi:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore creazione Deposito:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante creazione: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('error', 'Errore durante creazione deposito: ' . $e->getMessage());
        }
    }

    public function updateDepositi(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $deposito = \App\Models\Deposit::findOrFail($id);
            
            // Validazione semplificata per Depositi
            $validated = $request->validate(\App\Models\Deposit::validationRulesForUpdate($id));

            $deposito->update([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'],
                'indirizzo' => $validated['indirizzo'] ?? null,
                'localita' => $validated['localita'] ?? null,
                'stato' => $validated['stato'] ?? null,
                'provincia' => $validated['provincia'] ?? null,
                'cap' => $validated['cap'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'fax' => $validated['fax'] ?? null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposito aggiornato con successo',
                    'data' => $deposito->fresh()
                ]);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('success', 'Deposito aggiornato con successo');
                
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errori di validazione',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Errore aggiornamento Deposito:', [
                'errore' => $e->getMessage(),
                'id' => $id,
                'dati' => $request->all()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante aggiornamento: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
                ->route('configurations.gestione-tabelle.tabella', 'depositi')
                ->with('error', 'Errore durante aggiornamento deposito: ' . $e->getMessage());
        }
    }

    public function destroyDepositi(int $id): JsonResponse
    {
        try {
            $deposito = \App\Models\Deposit::findOrFail($id);
            
            $deposito->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Deposito eliminato con successo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione Deposito:', [
                'errore' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }
    // =====================================================
    // SISTEMA TABELLE FREQUENTI V2 - SENZA LIMITI
    // =====================================================

    /**
     * Carica le tabelle frequenti dell'utente senza limite fisso
     */
    public function getFavoriteTablesV2(): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utente non autenticato',
                    'data' => []
                ], 401);
            }

            // Carica TUTTE le tabelle frequenti senza limite
            $favoriteTablesWithDetails = \App\Models\UserFavoriteTable::getFavoritesWithDetailsV2(auth()->id());
            
            return response()->json([
                'success' => true,
                'data' => $favoriteTablesWithDetails,
                'count' => $favoriteTablesWithDetails->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Errore caricamento tabelle frequenti V2:', [
                'errore' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore caricamento tabelle frequenti',
                'data' => []
            ], 500);
        }
    }

    /**
     * Aggiunge una tabella ai preferiti
     */
    public function addToFavoritesV2(Request $request): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utente non autenticato'
                ], 401);
            }

            $validated = $request->validate([
                'table_objname' => 'required|string|max:100'
            ]);

            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$validated['table_objname']])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tabella non supportata'
                ], 400);
            }

            // Crea o aggiorna il preferito
            $favorite = \App\Models\UserFavoriteTable::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'table_objname' => $validated['table_objname']
                ],
                [
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'sort_order' => 0,
                    'click_count' => 0
                ]
            );

            Log::info('Tabella aggiunta ai preferiti V2:', [
                'user_id' => auth()->id(),
                'table_objname' => $validated['table_objname'],
                'favorite_id' => $favorite->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tabella aggiunta ai preferiti',
                'data' => $favorite
            ], 201);

        } catch (\Exception $e) {
            Log::error('Errore aggiunta preferito V2:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante aggiunta ai preferiti'
            ], 500);
        }
    }

    /**
     * Rimuove una tabella dai preferiti
     */
    public function removeFromFavoritesV2(Request $request): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utente non autenticato'
                ], 401);
            }

            $validated = $request->validate([
                'table_objname' => 'required|string|max:100'
            ]);

            Log::info('Tentativo rimozione preferito V2:', [
                'user_id' => auth()->id(),
                'table_objname' => $validated['table_objname'],
                'request_data' => $request->all()
            ]);

            $deleted = \App\Models\UserFavoriteTable::where('user_id', auth()->id())
                ->where('table_objname', $validated['table_objname'])
                ->delete();

            Log::info('Risultato rimozione preferito V2:', [
                'user_id' => auth()->id(),
                'table_objname' => $validated['table_objname'],
                'deleted_count' => $deleted
            ]);

            // Anche se non trova nulla, consideriamolo un successo
            // (l'obiettivo è che la tabella non sia più nei preferiti)
            return response()->json([
                'success' => true,
                'message' => $deleted > 0 ? 'Tabella rimossa dai preferiti' : 'Tabella già non presente nei preferiti',
                'deleted_count' => $deleted
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validazione fallita rimozione preferito V2:', [
                'errori' => $e->errors(),
                'dati' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Dati non validi',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Errore rimozione preferito V2:', [
                'errore' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dati' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante rimozione dai preferiti: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traccia l'utilizzo di una tabella (con auto-promozione migliorata)
     */
    public function trackTableUsageV2(Request $request): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utente non autenticato'
                ], 401);
            }

            $validated = $request->validate([
                'table_objname' => 'required|string|max:100'
            ]);

            // Verifica che la tabella sia supportata
            $configurazioni = $this->getConfigurazioni();
            if (!isset($configurazioni[$validated['table_objname']])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tabella non supportata'
                ], 400);
            }

            // Trova o crea il record di tracking
            $favorite = \App\Models\UserFavoriteTable::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'table_objname' => $validated['table_objname']
                ],
                [
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'sort_order' => 0,
                    'click_count' => 0
                ]
            );

            // Incrementa il contatore e aggiorna ultimo accesso
            $favorite->incrementUsage();

            // SISTEMA AUTO-PROMOZIONE MIGLIORATO
            // Soglie intelligenti: promozione automatica dopo 5 utilizzi
            if ($favorite->click_count >= 5 && $favorite->click_count % 5 == 0) {
                Log::info('Auto-promozione tabella frequente V2:', [
                    'user_id' => auth()->id(),
                    'table_objname' => $validated['table_objname'],
                    'click_count' => $favorite->click_count,
                    'action' => 'promoted_to_frequent'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Utilizzo tracciato',
                'data' => [
                    'click_count' => $favorite->click_count,
                    'last_accessed_at' => $favorite->last_accessed_at,
                    'is_frequent' => $favorite->click_count >= 5
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Errore tracking utilizzo V2:', [
                'errore' => $e->getMessage(),
                'dati' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante tracking utilizzo'
            ], 500);
        }
    }

    /**
     * Ottieni statistiche utilizzo tabelle
     */
    public function getTableStatsV2(): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utente non autenticato'
                ], 401);
            }

            $stats = \App\Models\UserFavoriteTable::where('user_id', auth()->id())
                ->selectRaw('
                    COUNT(*) as total_favorites,
                    SUM(click_count) as total_clicks,
                    AVG(click_count) as avg_clicks_per_table,
                    COUNT(CASE WHEN click_count >= 5 THEN 1 END) as frequent_tables,
                    MAX(click_count) as max_clicks,
                    MAX(last_accessed_at) as last_activity
                ')
                ->first();

            // Top 5 tabelle più utilizzate
            $topTables = \App\Models\UserFavoriteTable::where('user_id', auth()->id())
                ->orderByDesc('click_count')
                ->orderByDesc('last_accessed_at')
                ->limit(5)
                ->get(['table_objname', 'click_count', 'last_accessed_at']);

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $stats,
                    'top_tables' => $topTables,
                    'recommendations' => $this->getTableRecommendations()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Errore caricamento statistiche V2:', [
                'errore' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore caricamento statistiche'
            ], 500);
        }
    }

    /**
     * Sistema intelligente di raccomandazioni
     */
    private function getTableRecommendations(): array
    {
        try {
            $configurazioni = $this->getConfigurazioni();
            $userFavorites = \App\Models\UserFavoriteTable::where('user_id', auth()->id())
                ->pluck('table_objname')
                ->toArray();

            $recommendations = [];

            // Logica di raccomandazione basata sui pattern di utilizzo
            if (in_array('categorie-articoli', $userFavorites)) {
                $recommendations[] = [
                    'table' => 'settori-merceologici',
                    'reason' => 'Correlata a categorie articoli',
                    'confidence' => 0.8
                ];
            }

            if (in_array('aliquote-iva', $userFavorites)) {
                $recommendations[] = [
                    'table' => 'natura-iva',
                    'reason' => 'Completa la gestione IVA',
                    'confidence' => 0.9
                ];
            }

            return array_slice($recommendations, 0, 3); // Max 3 suggerimenti

        } catch (\Exception $e) {
            Log::error('Errore generazione raccomandazioni:', [
                'errore' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return [];
        }
    }
}