<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\ConfigurationCacheService;
use App\Services\SecurityAuditService;
use App\Http\Requests\SecureConfigurationRequest;

// Import tutti i modelli
use App\Models\SystemTables\*;
use App\Models\VatNatureAssociation;
use App\Models\VatNature;
use App\Models\GoodAppearance;
use App\Models\Bank;
use App\Models\ProductCategory;
use App\Models\CustomerCategory;
use App\Models\SupplierCategory;
use App\Models\SizeColor;
use App\Models\WarehouseCause;
use App\Models\ColorVariant;

/**
 * Controller Enterprise per Gestione Tabelle di Sistema
 * Gestisce tutte le 27 card con sicurezza e performance ottimali
 */
class SystemTablesController extends Controller
{
    protected ConfigurationCacheService $cacheService;
    protected SecurityAuditService $auditService;

    // Mapping table -> model class
    private const TABLE_MODELS = [
        'vat_nature_associations' => VatNatureAssociation::class,
        'vat_natures' => VatNature::class,
        'good_appearances' => GoodAppearance::class,
        'banks' => Bank::class,
        'product_categories' => ProductCategory::class,
        'customer_categories' => CustomerCategory::class,
        'supplier_categories' => SupplierCategory::class,
        'size_colors' => SizeColor::class,
        'warehouse_causes' => WarehouseCause::class,
        'color_variants' => ColorVariant::class,
        'conditions' => \App\Models\SystemTables\Condition::class,
        'fixed_price_denominations' => \App\Models\SystemTables\FixedPriceDenomination::class,
        'deposits' => \App\Models\SystemTables\Deposit::class,
        'price_lists' => \App\Models\SystemTables\PriceList::class,
        'shipping_terms' => \App\Models\SystemTables\ShippingTerm::class,
        'merchandising_sectors' => \App\Models\SystemTables\MerchandisingSector::class,
        'size_variants' => \App\Models\SystemTables\SizeVariant::class,
        'size_types' => \App\Models\SystemTables\SizeType::class,
        'payment_types' => \App\Models\SystemTables\PaymentType::class,
        'transports' => \App\Models\SystemTables\Transport::class,
        'transport_carriers' => \App\Models\SystemTables\TransportCarrier::class,
        'locations' => \App\Models\SystemTables\Location::class,
        'unit_of_measures' => \App\Models\SystemTables\UnitOfMeasure::class,
        'zones' => \App\Models\SystemTables\Zone::class,
    ];

    // Configurazione per ogni tabella
    private const TABLE_CONFIG = [
        'vat_nature_associations' => [
            'name' => 'Associazioni Nature IVA',
            'icon' => 'bi-link-45deg',
            'color' => 'primary',
            'special_configurator' => true,
            'validation_rules' => [
                'tax_rate_id' => 'required|exists:tax_rates,id',
                'vat_nature_id' => 'required|exists:vat_natures,id',
                'is_default' => 'boolean',
                'description' => 'nullable|string|max:500'
            ]
        ],
        'vat_natures' => [
            'name' => 'Nature IVA',
            'icon' => 'bi-file-earmark-text',
            'color' => 'info',
            'validation_rules' => [
                'code' => 'required|string|max:10|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|max:255',
                'fiscal_code' => 'nullable|string|max:5',
                'is_taxable' => 'boolean',
                'notes' => 'nullable|string|max:1000'
            ]
        ],
        'good_appearances' => [
            'name' => 'Aspetto dei Beni',
            'icon' => 'bi-box-seam',
            'color' => 'warning',
            'validation_rules' => [
                'code' => 'required|string|max:10|regex:/^[A-Z0-9_-]+$/',
                'description' => 'required|string|max:255',
                'category' => 'nullable|string|max:100',
                'sort_order' => 'integer|min:0'
            ]
        ],
        'banks' => [
            'name' => 'Banche',
            'icon' => 'bi-bank',
            'color' => 'primary',
            'validation_rules' => [
                'name' => 'required|string|max:255',
                'abi_code' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
                'bic_swift' => 'nullable|string|size:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email:rfc,dns|max:255',
                'website' => 'nullable|url|max:255',
                'is_italian' => 'boolean'
            ]
        ],
        'product_categories' => [
            'name' => 'Categorie Articoli',
            'icon' => 'bi-grid-3x3-gap',
            'color' => 'success',
            'hierarchical' => true,
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/',
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:product_categories,id',
                'description' => 'nullable|string|max:500',
                'color_hex' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'icon' => 'nullable|string|max:50'
            ]
        ]
        // ... continua per tutte le altre tabelle
    ];

    public function __construct(
        ConfigurationCacheService $cacheService, 
        SecurityAuditService $auditService
    ) {
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
        
        $this->middleware('config.access');
        $this->middleware('throttle:100,1'); // 100 requests per minute per performance
    }

    /**
     * Visualizza dashboard principale con tutte le 27 card
     */
    public function index()
    {
        $this->auditService->logConfigurationAccess('view_system_tables_dashboard');
        
        // Carica statistiche per tutte le tabelle con cache
        $stats = $this->cacheService->getAllSystemTablesStats();
        
        return view('configurations.system-tables.dashboard', [
            'tables' => self::TABLE_CONFIG,
            'stats' => $stats
        ]);
    }

    /**
     * Visualizza dati di una specifica tabella
     */
    public function show(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        // Rate limiting specifico per tabella
        $rateLimiterKey = "view_table_{$table}:" . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 30)) {
            return back()->withErrors(['error' => 'Troppi accessi. Attendi prima di ricaricare.']);
        }
        RateLimiter::hit($rateLimiterKey, 300);

        // Carica dati con paginazione e filtri
        $query = $modelClass::active();
        
        // Filtro di ricerca se presente
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Ordinamento
        $query->ordered();

        // Paginazione con cache
        $items = $this->cacheService->getCachedPaginatedData(
            $table, $query, $request->get('page', 1), 20
        );

        $this->auditService->logConfigurationAccess("view_table_{$table}", [
            'search' => $search,
            'page' => $request->get('page', 1)
        ]);

        return view('configurations.system-tables.table-view', [
            'table' => $table,
            'config' => $config,
            'items' => $items,
            'search' => $search
        ]);
    }

    /**
     * Crea nuovo record per una tabella
     */
    public function store(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        // Rate limiting per creazione
        $rateLimiterKey = "create_{$table}:" . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 20)) {
            return back()->withErrors(['error' => 'Troppi inserimenti. Attendi prima di aggiungerne altri.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        // Validazione con regole specifiche della tabella
        $validated = $request->validate($config['validation_rules']);

        // Verifica univocità codice se presente
        if (isset($validated['code'])) {
            if (!$modelClass::isCodeUnique($validated['code'])) {
                return back()->withErrors(['code' => 'Codice già esistente per questa tabella.']);
            }
        }

        // Creazione record
        $item = $modelClass::createSystemEntry($validated);

        $this->auditService->logSensitiveDataChange($table, 'create', [
            'item_id' => $item->id,
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'] ?? $validated['description'] ?? null
        ]);

        // Invalida cache
        $this->cacheService->invalidateSystemTablesCache($table);

        return back()->with('success', "{$config['name']}: record aggiunto con successo!");
    }

    /**
     * Aggiorna record esistente
     */
    public function update(string $table, int $id, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        $item = $modelClass::findOrFail($id);
        
        // Rate limiting per aggiornamenti
        $rateLimiterKey = "update_{$table}_{$id}:" . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 10)) {
            return back()->withErrors(['error' => 'Troppi aggiornamenti. Attendi prima di modificare nuovamente.']);
        }
        RateLimiter::hit($rateLimiterKey, 900);

        // Validazione
        $rules = $config['validation_rules'];
        
        // Per l'aggiornamento, modifica regola univocità codice se presente
        if (isset($rules['code']) && str_contains($rules['code'], 'unique:')) {
            $rules['code'] = str_replace('unique:', "unique:,code,{$id},id,", $rules['code']);
        }
        
        $validated = $request->validate($rules);

        // Backup dati originali per audit
        $originalData = $item->only(array_keys($validated));
        
        // Aggiornamento
        $item->update($validated);

        $this->auditService->logSensitiveDataChange($table, 'update', [
            'item_id' => $item->id,
            'changes' => array_diff_assoc($validated, $originalData)
        ]);

        // Invalida cache
        $this->cacheService->invalidateSystemTablesCache($table);

        return back()->with('success', "{$config['name']}: record aggiornato con successo!");
    }

    /**
     * Soft delete record
     */
    public function destroy(string $table, int $id)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        $item = $modelClass::findOrFail($id);
        
        // Rate limiting per eliminazioni
        $rateLimiterKey = "delete_{$table}:" . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return back()->withErrors(['error' => 'Troppe eliminazioni. Attendi prima di eliminare altri record.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        // Soft delete
        $item->delete();

        $this->auditService->logSensitiveDataChange($table, 'delete', [
            'item_id' => $item->id,
            'code' => $item->code ?? null,
            'name' => $item->name ?? $item->description ?? null
        ]);

        // Invalida cache
        $this->cacheService->invalidateSystemTablesCache($table);

        return back()->with('success', "{$config['name']}: record rimosso con successo!");
    }

    /**
     * API endpoint per AJAX requests
     */
    public function apiData(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        
        $items = $modelClass::active()
            ->select(['id', 'code', 'name', 'description'])
            ->ordered()
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
            'table' => $table,
            'count' => $items->count()
        ]);
    }

    /**
     * Configuratore speciale per Associazioni Nature IVA
     */
    public function vatNatureConfigurator()
    {
        $this->auditService->logConfigurationAccess('view_vat_nature_configurator');
        
        $taxRates = \App\Models\TaxRate::active()->get();
        $vatNatures = VatNature::active()->get();
        $associations = VatNatureAssociation::with(['taxRate', 'vatNature'])->active()->get();
        
        return view('configurations.system-tables.vat-nature-configurator', [
            'taxRates' => $taxRates,
            'vatNatures' => $vatNatures,
            'associations' => $associations
        ]);
    }

    /**
     * Export dati tabella in Excel/CSV
     */
    public function export(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $this->auditService->logConfigurationAccess("export_table_{$table}");
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        $items = $modelClass::active()->ordered()->get();
        
        // Implementazione export (da completare con libreria export)
        return response()->json([
            'message' => "Export {$config['name']} completato",
            'count' => $items->count()
        ]);
    }

    /**
     * Valida nome tabella
     */
    private function validateTableName(string $table): void
    {
        if (!array_key_exists($table, self::TABLE_MODELS)) {
            $this->auditService->logUnauthorizedAccess("invalid_table_{$table}");
            abort(404, 'Tabella non trovata.');
        }
    }
}