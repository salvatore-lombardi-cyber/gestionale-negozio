<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\ConfigurationCacheService;
use App\Services\SecurityAuditService;
use App\Http\Requests\SecureConfigurationRequest;
use App\Models\SystemPagebuilder;
use App\Models\UserFavoriteTable;

// Import modelli Sistema
use App\Models\GenericSystemTable;
use App\Models\VatNatureAssociation;
use App\Models\VatNature;
use App\Models\TaxRate;
use App\Models\GoodAppearance;
use App\Models\AspettoBeni;
use App\Models\Bank;
use App\Models\ProductCategory;
use App\Models\CustomerCategory;
use App\Models\SupplierCategory;
use App\Models\SizeColor;
use App\Models\WarehouseCause;
use App\Models\ColorVariant;
use App\Models\Condition;

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
        'tax_rates' => TaxRate::class,
        'vat_natures' => VatNature::class,
        'good_appearances' => GoodAppearance::class,
        'aspetto_beni' => AspettoBeni::class,
        'banks' => Bank::class,
        'product_categories' => ProductCategory::class,
        'customer_categories' => CustomerCategory::class,
        'supplier_categories' => SupplierCategory::class,
        'size_colors' => SizeColor::class,
        'warehouse_causes' => WarehouseCause::class,
        'color_variants' => ColorVariant::class,
        'conditions' => Condition::class,
        'fixed_price_denominations' => 'fixed_price_denominations', 
        'deposits' => 'deposits',
        'price_lists' => 'price_lists',
        'payment_methods' => 'payment_methods',
        'shipping_terms' => 'shipping_terms',
        'merchandising_sectors' => 'merchandising_sectors',
        'size_variants' => 'size_variants',
        'size_types' => 'size_types',
        'payment_types' => 'payment_types',
        'transports' => 'transports',
        'transport_carriers' => 'transport_carriers',
        'locations' => 'locations',
        'unit_of_measures' => 'unit_of_measures',
        'currencies' => 'currencies',
        'zones' => 'zones',
    ];

    // Configurazione per ogni tabella
    private const TABLE_CONFIG = [
        'vat_nature_associations' => [
            'name' => 'Associazioni Nature IVA',
            'icon' => 'bi-link-45deg',
            'color' => 'primary',
            'special_configurator' => true,
            'validation_rules' => [
                'nome_associazione' => 'required|string|max:255|regex:/^[\p{L}\p{N}\s\-\._%]+$/u|min:3',
                'descrizione' => 'nullable|string|max:500|regex:/^[\p{L}\p{N}\s\-\.,;:!?()\[\]{}"\'\/\\@#&*+=_]+$/u',
                'tax_rate_id' => 'required|integer|exists:tax_rates,id',
                'vat_nature_id' => 'required|integer|exists:vat_natures,id',
                'is_default' => 'nullable|boolean'
            ]
        ],
        'tax_rates' => [
            'name' => 'Aliquote IVA',
            'icon' => 'bi-percent',
            'color' => 'danger',
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/|unique:tax_rates,code',
                'name' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:500|min:5',
                'riferimento_normativo' => 'nullable|string|max:1000',
                'percentuale' => 'required|numeric|min:0|max:100|decimal:0,2',
                'sort_order' => 'nullable|integer|min:0',
                'active' => 'nullable|boolean'
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
        'aspetto_beni' => [
            'name' => 'Aspetto dei Beni',
            'icon' => 'bi-box-seam',
            'color' => 'warning',
            'validation_rules' => [
                'codice_aspetto' => 'required|string|max:10|regex:/^[A-Z0-9_-]+$/',
                'descrizione' => 'required|string|max:50|regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.]+$/',
                'descrizione_estesa' => 'nullable|string|max:255|regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.\,\(\)]+$/',
                'tipo_confezionamento' => 'required|in:primario,secondario,terziario',
                'utilizzabile_ddt' => 'boolean',
                'utilizzabile_fatture' => 'boolean',
                'attivo' => 'boolean'
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
        ],
        'customer_categories' => [
            'name' => 'Categorie Clienti',
            'icon' => 'bi-people-fill',
            'color' => 'info',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/|unique:customer_categories,code',
                'name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ]+$/',
                'description' => 'nullable|string|max:1000',
                'type' => 'required|in:B2B,B2C,WHOLESALE,RETAIL,VIP,STANDARD',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'credit_limit' => 'nullable|numeric|min:0|max:999999999.99',
                'payment_terms_days' => 'required|integer|min:0|max:365',
                'price_list' => 'required|in:LIST_1,LIST_2,LIST_3,WHOLESALE,RETAIL',
                'color_hex' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'icon' => 'required|string|max:50|regex:/^bi-[a-z0-9-]+$/',
                'max_orders_per_day' => 'nullable|integer|min:1|max:1000',
                'notes' => 'nullable|string|max:500'
            ]
        ],
        'supplier_categories' => [
            'name' => 'Categorie Fornitori',
            'icon' => 'bi-building',
            'color' => 'warning',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/|unique:supplier_categories,code',
                'name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ]+$/',
                'description' => 'nullable|string|max:1000',
                'category_type' => 'required|in:STRATEGIC,PREFERRED,TRANSACTIONAL,PANEL,ON_HOLD',
                'sector' => 'nullable|string|max:100',
                'reliability_rating' => 'required|integer|min:1|max:5',
                'quality_rating' => 'required|integer|min:1|max:5',
                'performance_rating' => 'required|integer|min:1|max:5',
                'payment_terms_days' => 'required|integer|min:0|max:365',
                'discount_expected' => 'required|numeric|min:0|max:100',
                'minimum_order_value' => 'nullable|numeric|min:0|max:999999999.99',
                'preferred_contact_method' => 'required|in:EMAIL,PHONE,PORTAL,EDI',
                'lead_time_days' => 'nullable|integer|min:0|max:365',
                'security_clearance_level' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
                'audit_frequency_months' => 'nullable|integer|min:1|max:60',
                'color_hex' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'icon' => 'required|string|max:50|regex:/^bi-[a-z0-9-]+$/',
                'contract_template' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:500'
            ]
        ],
        'size_colors' => [
            'name' => 'Taglie e Colori',
            'icon' => 'bi-palette2',
            'color' => 'primary',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/|unique:size_colors,code',
                'name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ\/]+$/',
                'description' => 'nullable|string|max:1000',
                'type' => 'required|in:size,color',
                'hex_value' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/|required_if:type,color',
                'rgb_value' => 'nullable|regex:/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/',
                'pantone_code' => 'nullable|string|max:20|regex:/^[A-Z0-9\s-]+$/i',
                'size_category' => 'nullable|in:NUMERIC,LETTER,EU,US,UK,IT,FR,CUSTOM|required_if:type,size',
                'size_system' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s\-_]+$/',
                'numeric_value' => 'nullable|numeric|min:0|max:999.99',
                'eu_size' => 'nullable|string|max:10|regex:/^[A-Z0-9\/\-]+$/i',
                'us_size' => 'nullable|string|max:10|regex:/^[A-Z0-9\/\-]+$/i',
                'uk_size' => 'nullable|string|max:10|regex:/^[A-Z0-9\/\-]+$/i',
                'chest_cm' => 'nullable|integer|min:1|max:300',
                'waist_cm' => 'nullable|integer|min:1|max:300',
                'hip_cm' => 'nullable|integer|min:1|max:300',
                'price_modifier' => 'nullable|numeric|min:-999999.99|max:999999.99',
                'barcode_prefix' => 'nullable|string|max:20|regex:/^[A-Z0-9_-]+$/',
                'sku_suffix' => 'nullable|string|max:10|regex:/^[A-Z0-9_-]+$/',
                'default_stock_level' => 'nullable|integer|min:0|max:99999',
                'icon' => 'nullable|string|max:50|regex:/^bi-[a-z0-9-]+$/',
                'css_class' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-_]+$/',
                'compliance_notes' => 'nullable|string|max:500',
                'sort_order' => 'nullable|integer|min:0|max:9999'
            ]
        ],
        'warehouse_causes' => [
            'name' => 'Causali di Magazzino',
            'icon' => 'bi-box-seam',
            'color' => 'warning',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:20|regex:/^[A-Z0-9_-]+$/|unique:warehouse_causes,code',
                'description' => 'required|string|max:255|min:3',
                'movement_type' => 'required|in:in,out,adjustment',
                'affects_cost' => 'boolean',
                'requires_document' => 'boolean',
                'auto_calculate_cost' => 'boolean',
                'fiscal_relevant' => 'boolean',
                'fiscal_code' => 'nullable|string|max:10|regex:/^[A-Z0-9]+$/',
                'category' => 'required|in:ORDINARY,INVENTORY,PRODUCTION,LOSS,TRANSFER,RETURN,SAMPLE',
                'priority_level' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
                'approval_required' => 'boolean',
                'notify_threshold' => 'nullable|numeric|min:0|max:999999.99',
                'color_hex' => 'required|string|size:7|regex:/^#[A-Fa-f0-9]{6}$/',
                'icon' => 'required|string|max:50|regex:/^[a-z0-9-]+$/',
                'default_location' => 'nullable|string|max:100',
                'auto_assign_lot' => 'boolean',
                'compliance_notes' => 'nullable|string|max:500'
            ]
        ],
        'color_variants' => [
            'name' => 'Colori Varianti',
            'icon' => 'bi-palette',
            'color' => 'info',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:50|regex:/^[A-Z0-9_-]+$/|unique:color_variants,code',
                'name' => 'required|string|max:255|min:2',
                'description' => 'nullable|string|max:500',
                'active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0|max:9999'
            ]
        ],
        'conditions' => [
            'name' => 'Condizioni',
            'icon' => 'bi-file-check',
            'color' => 'success',
            'hierarchical' => false,
            'validation_rules' => [
                'code' => 'required|string|max:50|regex:/^[A-Z0-9_-]+$/|unique:conditions,code',
                'name' => 'required|string|max:255|min:2',
                'description' => 'nullable|string|max:500',
                'active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0|max:9999'
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
    }

    /**
     * Dashboard SPETTACOLARE con 27 pulsanti colorati 🌈
     * La più bella vista gestionale d'Italia!
     */
    public function index()
    {
        $this->auditService->logConfigurationAccess('view_system_tables_dashboard');
        
        // Carica configurazioni tabelle con i 27 colori spettacolari
        $tables = SystemPagebuilder::getDashboardTables();
        
        // Carica statistiche per tutte le tabelle con cache
        $stats = $this->cacheService->getAllSystemTablesStats();
        
        // Carica tabelle preferite dell'utente (NEW!)
        $favoriteTablesWithDetails = [];
        if (Auth::check()) {
            $favoriteTablesWithDetails = UserFavoriteTable::getFavoritesWithDetails(Auth::id(), 6);
        }
        
        // Carica metriche performance per dashboard
        $metrics = [
            'total_tables' => $tables->count(),
            'active_tables' => $tables->where('is_active', true)->count(),
            'total_records' => $this->getTotalSystemRecords(),
            'cache_hit_rate' => $this->cacheService->getCacheHitRate()
        ];

        return view('configurations.system-tables.index', [
            'tables' => $tables,
            'stats' => $stats,
            'metrics' => $metrics,
            'user_permissions' => $this->getUserPermissions(),
            'favoriteTablesWithDetails' => $favoriteTablesWithDetails // NEW!
        ]);
    }

    /**
     * Ottieni permessi utente per tutte le tabelle (OWASP Security)
     */
    private function getUserPermissions(): array
    {
        $user = Auth::user();
        $permissions = [];
        
        SystemPagebuilder::where('is_active', true)->get()->each(function($table) use ($user, &$permissions) {
            $permissions[$table->objname] = [
                'read' => $table->canUserAccess($user, 'read'),
                'create' => $table->canUserAccess($user, 'create'),
                'update' => $table->canUserAccess($user, 'update'),
                'delete' => $table->canUserAccess($user, 'delete'),
            ];
        });

        return $permissions;
    }

    /**
     * Conta totale record in tutte le tabelle sistema
     */
    private function getTotalSystemRecords(): int
    {
        return cache()->remember('system_tables.total_records', 1800, function() {
            $total = 0;
            foreach (self::TABLE_MODELS as $table => $modelClass) {
                if (class_exists($modelClass)) {
                    try {
                        $total += $modelClass::count();
                    } catch (\Exception $e) {
                        Log::warning("Cannot count records for {$table}: " . $e->getMessage());
                    }
                }
            }
            return $total;
        });
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

        // GESTIONE SPECIFICA PER ASPETTO_BENI (campi custom)
        if ($table === 'aspetto_beni') {
            return $this->showAspettoBeni($request);
        }
        
        // GESTIONE SPECIFICA PER BANKS (vista custom)
        if ($table === 'banks') {
            return $this->showBanks($request);
        }
        
        // GESTIONE SPECIFICA PER PRODUCT_CATEGORIES (gerarchia)
        if ($table === 'product_categories') {
            return $this->showProductCategories($request);
        }

        // GESTIONE SPECIFICA PER CUSTOMER_CATEGORIES (segmentazione)
        if ($table === 'customer_categories') {
            return $this->showCustomerCategories($request);
        }

        // GESTIONE SPECIFICA PER SUPPLIER_CATEGORIES (procurement)
        if ($table === 'supplier_categories') {
            return $this->showSupplierCategories($request);
        }

        // GESTIONE SPECIFICA PER SIZE_COLORS (fashion retail)
        if ($table === 'size_colors') {
            return $this->showSizeColors($request);
        }

        // GESTIONE SPECIFICA PER WAREHOUSE_CAUSES (causali magazzino)
        if ($table === 'warehouse_causes') {
            return $this->showWarehouseCauses($request);
        }

        // GESTIONE SPECIFICA PER COLOR_VARIANTS (varianti colore)
        if ($table === 'color_variants') {
            return $this->showColorVariants($request);
        }

        // GESTIONE SPECIFICA PER CONDITIONS (condizioni)
        if ($table === 'conditions') {
            return $this->showConditions($request);
        }

        // Carica dati con paginazione e filtri
        $query = $modelClass::query();
        
        // Filtro di ricerca se presente
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Ordinamento standard
        $query->orderBy('code')->orderBy('name');

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
     * Gestione specifica per aspetto_beni (campi custom)
     */
    private function showAspettoBeni(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $codice = strtoupper(trim($request->get('check_duplicate')));
            $exists = AspettoBeni::where('codice_aspetto', $codice)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = AspettoBeni::query();
        
        // Filtro di ricerca specifico per aspetto_beni
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('codice_aspetto', 'like', "%{$search}%")
                  ->orWhere('descrizione', 'like', "%{$search}%")
                  ->orWhere('descrizione_estesa', 'like', "%{$search}%");
            });
        }
        
        // Filtri aggiuntivi
        if ($tipo = $request->get('tipo')) {
            $query->where('tipo_confezionamento', $tipo);
        }
        
        if ($status = $request->get('status')) {
            $query->where('attivo', $status === '1');
        }
        
        // Ordinamento specifico
        $query->orderBy('codice_aspetto')->orderBy('descrizione');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // TODO: Ripristinare audit log quando SecurityAuditService è corretto
        // $this->auditService->logConfigurationAccess("view_aspetto_beni", [
        //     'search' => $search,
        //     'tipo' => $tipo,
        //     'status' => $status,
        //     'page' => $request->get('page', 1)
        // ]);

        return view('configurations.system-tables.aspetto-beni', [
            'table' => 'aspetto_beni',
            'config' => self::TABLE_CONFIG['aspetto_beni'],
            'items' => $items,
            'search' => $search
        ]);
    }

    /**
     * Gestione specifica per banks (vista custom)
     */
    private function showBanks(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = Bank::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = Bank::query();
        
        // Filtro di ricerca specifico per banks
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('abi_code', 'like', "%{$search}%")
                  ->orWhere('bic_swift', 'like', "%{$search}%");
            });
        }
        
        // Filtri aggiuntivi
        if ($type = $request->get('type')) {
            if ($type === 'italian') {
                $query->where('is_italian', true);
            } elseif ($type === 'foreign') {
                $query->where('is_italian', false);
            }
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        // Ordinamento specifico
        $query->orderBy('is_italian', 'desc')->orderBy('name');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();

        return view('configurations.system-tables.banks', [
            'table' => 'banks',
            'config' => self::TABLE_CONFIG['banks'],
            'items' => $items,
            'search' => $search
        ]);
    }

    /**
     * Gestione specifica per product_categories (gerarchia)
     */
    private function showProductCategories(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = ProductCategory::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = ProductCategory::query();
        
        // Filtro di ricerca specifico per categorie
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('path', 'like', "%{$search}%");
            });
        }
        
        // Filtri aggiuntivi
        if ($level = $request->get('level')) {
            if ($level === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('level', (int)$level);
            }
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        // Ordinamento gerarchico
        $query->orderBy('level')
              ->orderBy('sort_order')
              ->orderBy('name');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Carica categorie padre per il form
        $parentCategories = ProductCategory::rootLevel()
                                          ->active()
                                          ->ordered()
                                          ->get();

        return view('configurations.system-tables.product-categories', [
            'table' => 'product_categories',
            'config' => self::TABLE_CONFIG['product_categories'],
            'items' => $items,
            'search' => $search,
            'parentCategories' => $parentCategories
        ]);
    }

    /**
     * Gestione specifica per customer_categories (segmentazione clientela)
     */
    private function showCustomerCategories(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = CustomerCategory::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = CustomerCategory::query();
        
        // Filtro di ricerca specifico per categorie clienti
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filtri segmentazione
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        
        if ($priority = $request->get('priority_level')) {
            $query->where('priority_level', $priority);
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        if ($discount = $request->get('with_discount')) {
            if ($discount === '1') {
                $query->where('discount_percentage', '>', 0);
            }
        }
        
        // Ordinamento per priorità e tipo
        $query->orderByRaw("FIELD(priority_level, 'PREMIUM', 'HIGH', 'MEDIUM', 'LOW')")
              ->orderByRaw("FIELD(type, 'VIP', 'B2B', 'WHOLESALE', 'RETAIL', 'B2C', 'STANDARD')")
              ->orderBy('name');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche per dashboard
        $stats = [
            'total' => CustomerCategory::count(),
            'active' => CustomerCategory::where('active', true)->count(),
            'vip' => CustomerCategory::where('type', 'VIP')->count(),
            'b2b' => CustomerCategory::where('type', 'B2B')->count(),
            'with_discount' => CustomerCategory::where('discount_percentage', '>', 0)->count()
        ];

        return view('configurations.system-tables.categorie-clienti', [
            'table' => 'customer_categories',
            'config' => self::TABLE_CONFIG['customer_categories'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Gestione specifica per supplier_categories (procurement e vendor management)
     */
    private function showSupplierCategories(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = SupplierCategory::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = SupplierCategory::query();
        
        // Filtro di ricerca specifico per categorie fornitori
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category_type', 'like', "%{$search}%")
                  ->orWhere('sector', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filtri procurement avanzati
        if ($categoryType = $request->get('category_type')) {
            $query->where('category_type', $categoryType);
        }
        
        if ($sector = $request->get('sector')) {
            $query->where('sector', 'like', "%{$sector}%");
        }
        
        if ($securityLevel = $request->get('security_level')) {
            $query->where('security_clearance_level', $securityLevel);
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        if ($minRating = $request->get('min_rating')) {
            $query->where('reliability_rating', '>=', (int)$minRating);
        }
        
        if ($needsAudit = $request->get('needs_audit')) {
            if ($needsAudit === '1') {
                $query->needsAudit();
            }
        }
        
        // Ordinamento strategico
        $query->orderByRaw("FIELD(category_type, 'STRATEGIC', 'PREFERRED', 'TRANSACTIONAL', 'PANEL', 'ON_HOLD')")
              ->orderBy('reliability_rating', 'desc')
              ->orderBy('name');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche per dashboard procurement
        $stats = [
            'total' => SupplierCategory::count(),
            'active' => SupplierCategory::where('active', true)->count(),
            'strategic' => SupplierCategory::where('category_type', 'STRATEGIC')->count(),
            'preferred' => SupplierCategory::where('category_type', 'PREFERRED')->count(),
            'high_security' => SupplierCategory::whereIn('security_clearance_level', ['HIGH', 'CRITICAL'])->count(),
            'high_performance' => SupplierCategory::where('reliability_rating', '>=', 4)->count(),
            'needs_audit' => SupplierCategory::query()->needsAudit()->count()
        ];

        return view('configurations.system-tables.categorie-fornitori', [
            'table' => 'supplier_categories',
            'config' => self::TABLE_CONFIG['supplier_categories'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Gestione specifica per size_colors (fashion retail e gestione varianti)
     */
    private function showSizeColors(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = SizeColor::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = SizeColor::query();
        
        // Filtro di ricerca specifico per taglie e colori
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('hex_value', 'like', "%{$search}%")
                  ->orWhere('pantone_code', 'like', "%{$search}%")
                  ->orWhere('eu_size', 'like', "%{$search}%")
                  ->orWhere('us_size', 'like', "%{$search}%")
                  ->orWhere('uk_size', 'like', "%{$search}%");
            });
        }
        
        // Filtri fashion avanzati
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        
        if ($sizeCategory = $request->get('size_category')) {
            $query->where('size_category', $sizeCategory);
        }
        
        if ($seasonal = $request->get('seasonal')) {
            $query->where('seasonal', $seasonal === '1');
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        if ($requiresApproval = $request->get('requires_approval')) {
            if ($requiresApproval === '1') {
                $query->where('requires_approval', true);
            }
        }
        
        if ($priceModifier = $request->get('with_price_modifier')) {
            if ($priceModifier === '1') {
                $query->where('price_modifier', '!=', 0);
            }
        }
        
        // Ordinamento fashion-specific
        $query->orderByRaw("FIELD(type, 'color', 'size')")
              ->orderBy('sort_order')
              ->orderBy('name');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche per dashboard varianti colore
        $stats = [
            'total' => SizeColor::count(),
            'active' => SizeColor::where('active', true)->count(),
            'colors' => SizeColor::where('type', 'color')->count(),
            'sizes' => SizeColor::where('type', 'size')->count(),
            'seasonal' => SizeColor::where('seasonal', true)->count(),
            'with_price_modifier' => SizeColor::where('price_modifier', '!=', 0)->count(),
            'requires_approval' => SizeColor::where('requires_approval', true)->count(),
            'popular' => SizeColor::where('usage_count', '>', 10)->count()
        ];

        return view('configurations.system-tables.taglie-colori', [
            'table' => 'size_colors',
            'config' => self::TABLE_CONFIG['size_colors'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Gestione specifica per warehouse_causes (causali di magazzino)
     */
    private function showWarehouseCauses(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = WarehouseCause::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = WarehouseCause::query();
        
        // Filtro di ricerca specifico per causali di magazzino
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('fiscal_code', 'like', "%{$search}%")
                  ->orWhere('default_location', 'like', "%{$search}%")
                  ->orWhere('compliance_notes', 'like', "%{$search}%");
            });
        }
        
        // Filtri specifici per magazzino
        if ($movementType = $request->get('movement_type')) {
            $query->where('movement_type', $movementType);
        }
        
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        
        if ($priority = $request->get('priority_level')) {
            $query->where('priority_level', $priority);
        }
        
        if ($fiscalRelevant = $request->get('fiscal_relevant')) {
            $query->where('fiscal_relevant', $fiscalRelevant === '1');
        }
        
        if ($requiresApproval = $request->get('requires_approval')) {
            $query->where('approval_required', $requiresApproval === '1');
        }
        
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        // Ordinamento specifico per warehouse management
        $query->orderByRaw("FIELD(movement_type, 'in', 'out', 'adjustment')")
              ->orderByRaw("FIELD(priority_level, 'CRITICAL', 'HIGH', 'MEDIUM', 'LOW')")
              ->orderByRaw("FIELD(category, 'ORDINARY', 'INVENTORY', 'PRODUCTION', 'LOSS', 'TRANSFER', 'RETURN', 'SAMPLE')")
              ->orderBy('code');
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche per dashboard magazzino
        $stats = [
            'total' => WarehouseCause::count(),
            'active' => WarehouseCause::where('active', true)->count(),
            'incoming' => WarehouseCause::where('movement_type', 'in')->count(),
            'outgoing' => WarehouseCause::where('movement_type', 'out')->count(),
            'adjustments' => WarehouseCause::where('movement_type', 'adjustment')->count(),
            'fiscal_relevant' => WarehouseCause::where('fiscal_relevant', true)->count(),
            'requires_approval' => WarehouseCause::where('approval_required', true)->count(),
            'critical_priority' => WarehouseCause::where('priority_level', 'CRITICAL')->count(),
            'most_used' => WarehouseCause::orderBy('usage_count', 'desc')->limit(5)->get(),
            'recently_used' => WarehouseCause::whereNotNull('last_used_at')
                                ->orderBy('last_used_at', 'desc')
                                ->limit(5)->get()
        ];

        return view('configurations.system-tables.causali-magazzino', [
            'table' => 'warehouse_causes',
            'config' => self::TABLE_CONFIG['warehouse_causes'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Gestione specifica per color_variants (colori varianti)
     */
    private function showColorVariants(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = ColorVariant::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = ColorVariant::query();
        
        // Filtro di ricerca semplice
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro stato
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        // Ordinamento semplice
        $query->ordered();
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche semplici
        $stats = [
            'total' => ColorVariant::count(),
            'active' => ColorVariant::where('active', true)->count(),
            'inactive' => ColorVariant::where('active', false)->count()
        ];

        return view('configurations.system-tables.colori-varianti', [
            'table' => 'color_variants',
            'config' => self::TABLE_CONFIG['color_variants'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Gestione specifica per conditions (condizioni)
     */
    private function showConditions(Request $request)
    {
        // Controllo duplicati per AJAX
        if ($request->has('check_duplicate')) {
            $code = strtoupper(trim($request->get('check_duplicate')));
            $exists = Condition::where('code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }
        
        $query = Condition::query();
        
        // Filtro di ricerca semplice
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro stato
        if ($status = $request->get('status')) {
            $query->where('active', $status === '1');
        }
        
        // Ordinamento semplice
        $query->ordered();
        
        // Paginazione
        $items = $query->paginate(20)->withQueryString();
        
        // Statistiche semplici
        $stats = [
            'total' => Condition::count(),
            'active' => Condition::where('active', true)->count(),
            'inactive' => Condition::where('active', false)->count()
        ];

        return view('configurations.system-tables.condizioni', [
            'table' => 'conditions',
            'config' => self::TABLE_CONFIG['conditions'],
            'items' => $items,
            'search' => $search,
            'stats' => $stats
        ]);
    }

    /**
     * Restituisce dati di un singolo record per modifica
     */
    public function edit(string $table, $id, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        
        // Trova il record
        $record = $modelClass::find($id);
        
        if (!$record) {
            return response()->json(['error' => 'Record non trovato'], 404);
        }
        
        // Restituisci i dati del record in JSON
        return response()->json($record);
    }

    /**
     * Crea nuovo record per una tabella
     */
    public function store(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $config = self::TABLE_CONFIG[$table];
        
        // Rate limiting per creazione (OWASP Security)
        $rateLimiterKey = "create_{$table}:" . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 20)) {
            return back()->withErrors(['error' => 'Troppi inserimenti. Attendi prima di aggiungerne altri.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        // Validazione specifica per aspetto_beni
        if ($table === 'aspetto_beni') {
            $validated = $request->validate(AspettoBeni::validationRules(), [
                'codice_aspetto.required' => 'Il codice aspetto è obbligatorio.',
                'codice_aspetto.unique' => 'Il codice aspetto esiste già.',
                'codice_aspetto.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'descrizione.required' => 'La descrizione è obbligatoria.',
                'descrizione.regex' => 'La descrizione contiene caratteri non permessi.',
                'tipo_confezionamento.required' => 'Il tipo di confezionamento è obbligatorio.',
                'tipo_confezionamento.in' => 'Il tipo di confezionamento deve essere primario, secondario o terziario.'
            ]);
        } elseif ($table === 'banks') {
            // Validazione specifica per banks
            $validated = $request->validate(Bank::validationRules(), [
                'code.required' => 'Il codice banca è obbligatorio.',
                'code.unique' => 'Il codice banca esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della banca è obbligatorio.',
                'abi_code.regex' => 'Il codice ABI deve essere di 5 cifre.',
                'bic_swift.regex' => 'Il codice BIC/SWIFT non è valido.',
                'email.email' => 'L\'indirizzo email non è valido.',
                'website.url' => 'L\'URL del sito web non è valido.'
            ]);
        } elseif ($table === 'product_categories') {
            // Validazione specifica per product_categories
            $validated = $request->validate(ProductCategory::validationRules(), [
                'code.required' => 'Il codice categoria è obbligatorio.',
                'code.unique' => 'Il codice categoria esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'parent_id.exists' => 'La categoria padre selezionata non è valida.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona può contenere solo lettere minuscole, numeri e -.'
            ]);
        } elseif ($table === 'customer_categories') {
            // Validazione specifica per customer_categories
            $validated = $request->validate(CustomerCategory::validationRules(), [
                'code.required' => 'Il codice categoria cliente è obbligatorio.',
                'code.unique' => 'Il codice categoria cliente esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria cliente è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'type.required' => 'Il tipo di categoria è obbligatorio.',
                'type.in' => 'Il tipo di categoria deve essere valido (B2B, B2C, VIP, ecc.).',
                'discount_percentage.required' => 'La percentuale di sconto è obbligatoria.',
                'discount_percentage.numeric' => 'La percentuale di sconto deve essere un numero.',
                'discount_percentage.min' => 'La percentuale di sconto non può essere negativa.',
                'discount_percentage.max' => 'La percentuale di sconto non può superare il 100%.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.'
            ]);
        } elseif ($table === 'supplier_categories') {
            // Validazione specifica per supplier_categories
            $validated = $request->validate(SupplierCategory::validationRules(), [
                'code.required' => 'Il codice categoria fornitore è obbligatorio.',
                'code.unique' => 'Il codice categoria fornitore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria fornitore è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'category_type.required' => 'Il tipo di categoria è obbligatorio.',
                'category_type.in' => 'Il tipo deve essere valido (STRATEGIC, PREFERRED, ecc.).',
                'reliability_rating.required' => 'Il rating di affidabilità è obbligatorio.',
                'reliability_rating.integer' => 'Il rating deve essere un numero intero.',
                'reliability_rating.min' => 'Il rating deve essere almeno 1.',
                'reliability_rating.max' => 'Il rating non può superare 5.',
                'security_clearance_level.required' => 'Il livello di sicurezza è obbligatorio.',
                'security_clearance_level.in' => 'Il livello di sicurezza deve essere valido.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.'
            ]);
            
            // Controllo riferimento circolare
            if ($request->parent_id) {
                $parent = ProductCategory::find($request->parent_id);
                if ($parent && $parent->hasCircularReference($request->parent_id)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['parent_id' => ['Riferimento circolare non permesso.']]
                    ], 422);
                }
            }
        } elseif ($table === 'size_colors') {
            // Validazione specifica per size_colors
            $validated = $request->validate(SizeColor::validationRules(), [
                'code.required' => 'Il codice taglia/colore è obbligatorio.',
                'code.unique' => 'Il codice taglia/colore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della taglia/colore è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'type.required' => 'Il tipo (taglia/colore) è obbligatorio.',
                'type.in' => 'Il tipo deve essere "size" o "color".',
                'hex_value.required_if' => 'Il valore esadecimale è obbligatorio per i colori.',
                'hex_value.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'size_category.required_if' => 'La categoria di taglia è obbligatoria per le taglie.',
                'size_category.in' => 'La categoria di taglia deve essere valida.',
                'numeric_value.numeric' => 'Il valore numerico deve essere un numero.',
                'chest_cm.integer' => 'La circonferenza petto deve essere un numero intero.',
                'waist_cm.integer' => 'La circonferenza vita deve essere un numero intero.',
                'hip_cm.integer' => 'La circonferenza fianchi deve essere un numero intero.',
                'price_modifier.numeric' => 'Il modificatore prezzo deve essere un numero.',
                'default_stock_level.integer' => 'Il livello stock deve essere un numero intero.',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.'
            ]);
        } elseif ($table === 'warehouse_causes') {
            // Validazione specifica per warehouse_causes
            $validated = $request->validate(WarehouseCause::validationRules(), [
                'code.required' => 'Il codice causale è obbligatorio.',
                'code.unique' => 'Il codice causale esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'description.required' => 'La descrizione è obbligatoria.',
                'description.min' => 'La descrizione deve essere di almeno 3 caratteri.',
                'movement_type.required' => 'Il tipo di movimento è obbligatorio.',
                'movement_type.in' => 'Il tipo di movimento deve essere valido (in, out, adjustment).',
                'category.required' => 'La categoria è obbligatoria.',
                'category.in' => 'La categoria deve essere valida.',
                'priority_level.required' => 'Il livello di priorità è obbligatorio.',
                'priority_level.in' => 'Il livello di priorità deve essere valido.',
                'fiscal_code.regex' => 'Il codice fiscale può contenere solo lettere maiuscole e numeri.',
                'notify_threshold.numeric' => 'La soglia di notifica deve essere un numero.',
                'notify_threshold.min' => 'La soglia di notifica non può essere negativa.',
                'color_hex.required' => 'Il colore identificativo è obbligatorio.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.required' => 'L\'icona è obbligatoria.',
                'icon.regex' => 'L\'icona può contenere solo lettere minuscole, numeri e -.',
                'default_location.max' => 'L\'ubicazione predefinita non può superare i 100 caratteri.',
                'compliance_notes.max' => 'Le note di conformità non possono superare i 500 caratteri.'
            ]);
        } elseif ($table === 'color_variants') {
            // Validazione semplice per color_variants
            $validated = $request->validate(ColorVariant::validationRules(), [
                'code.required' => 'Il codice colore è obbligatorio.',
                'code.unique' => 'Il codice colore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome del colore è obbligatorio.',
                'name.min' => 'Il nome deve essere di almeno 2 caratteri.',
                'description.max' => 'La descrizione non può superare i 500 caratteri.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.',
                'sort_order.min' => 'L\'ordine di ordinamento non può essere negativo.',
                'sort_order.max' => 'L\'ordine di ordinamento non può superare 9999.'
            ]);
        } elseif ($table === 'conditions') {
            // Validazione semplice per conditions
            $validated = $request->validate(Condition::validationRules(), [
                'code.required' => 'Il codice condizione è obbligatorio.',
                'code.unique' => 'Il codice condizione esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della condizione è obbligatorio.',
                'name.min' => 'Il nome deve essere di almeno 2 caratteri.',
                'description.max' => 'La descrizione non può superare i 500 caratteri.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.',
                'sort_order.min' => 'L\'ordine di ordinamento non può essere negativo.',
                'sort_order.max' => 'L\'ordine di ordinamento non può superare 9999.'
            ]);
        } else {
            // Validazione standard per altre tabelle
            $validated = $request->validate($config['validation_rules'], [
                'nome_associazione.required' => 'Il nome associazione è obbligatorio.',
                'nome_associazione.min' => 'Il nome deve essere di almeno 3 caratteri.',
                'nome_associazione.regex' => 'Il nome contiene caratteri non validi.',
                'tax_rate_id.required' => 'L\'aliquota IVA è obbligatoria.',
                'tax_rate_id.exists' => 'L\'aliquota IVA selezionata non è valida.',
                'vat_nature_id.required' => 'La natura IVA è obbligatoria.',
                'vat_nature_id.exists' => 'La natura IVA selezionata non è valida.',
                'descrizione.regex' => 'La descrizione contiene caratteri non validi.'
            ]);
        }

        // Validazione business logic per VAT associations
        if ($table === 'vat_nature_associations') {
            // Verifica che non esista già questa associazione
            $existingAssociation = $modelClass::where('tax_rate_id', $validated['tax_rate_id'])
                ->where('vat_nature_id', $validated['vat_nature_id'])
                ->first();
            
            if ($existingAssociation) {
                return back()->withErrors([
                    'tax_rate_id' => 'Questa associazione esiste già nel sistema.'
                ]);
            }
            
            // Se è impostata come default, rimuovi default da altre associazioni con stessa aliquota
            if ($validated['is_default'] ?? false) {
                $modelClass::where('tax_rate_id', $validated['tax_rate_id'])
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        }

        // Verifica univocità codice se presente (per altre tabelle)
        if (isset($validated['code']) && method_exists($modelClass, 'isCodeUnique')) {
            if (!$modelClass::isCodeUnique($validated['code'])) {
                return back()->withErrors(['code' => 'Codice già esistente per questa tabella.']);
            }
        }

        // Sanitizzazione input (OWASP)
        if (isset($validated['nome_associazione'])) {
            $validated['nome_associazione'] = trim(strip_tags($validated['nome_associazione']));
            // Popola anche il campo 'name' per compatibilità tabella
            $validated['name'] = $validated['nome_associazione'];
        }
        if (isset($validated['descrizione'])) {
            $validated['descrizione'] = trim(strip_tags($validated['descrizione']));
            // Popola anche il campo 'description' per compatibilità tabella
            $validated['description'] = $validated['descrizione'];
        }
        
        // Aggiungi metadati di sicurezza (UUID e audit trail gestiti automaticamente dal model)
        $validated['active'] = true;

        // Creazione record con protezione Mass Assignment
        $item = $modelClass::create($validated);

        $this->auditService->logSensitiveDataChange($table, 'create', [
            'item_id' => $item->id,
            'uuid' => $item->uuid ?? null,
            'nome_associazione' => $validated['nome_associazione'] ?? null,
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'] ?? $validated['descrizione'] ?? null,
            'created_by' => $item->created_by ?? null,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Invalida cache
        $this->cacheService->invalidateSystemTablesCache($table);

        // Gestione risposta AJAX per aspetto_beni, banks, size_colors, warehouse_causes, color_variants e conditions
        if (in_array($table, ['aspetto_beni', 'banks', 'size_colors', 'warehouse_causes', 'color_variants', 'conditions']) && $request->expectsJson()) {
            $messages = [
                'banks' => 'Banca creata con successo!',
                'aspetto_beni' => 'Aspetto dei beni creato con successo!',
                'size_colors' => 'Taglia/Colore creato con successo!',
                'warehouse_causes' => 'Causale di Magazzino creata con successo!',
                'color_variants' => 'Colore Variante creato con successo!',
                'conditions' => 'Condizione creata con successo!'
            ];
            return response()->json([
                'success' => true,
                'message' => $messages[$table],
                'item' => $item
            ]);
        }

        return back()->with('success', "{$config['name']}: associazione creata con successo!");
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

        // Validazione specifica per aspetto_beni
        if ($table === 'aspetto_beni') {
            $validated = $request->validate(AspettoBeni::validationRules($id), [
                'codice_aspetto.required' => 'Il codice aspetto è obbligatorio.',
                'codice_aspetto.unique' => 'Il codice aspetto esiste già.',
                'codice_aspetto.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'descrizione.required' => 'La descrizione è obbligatoria.',
                'descrizione.regex' => 'La descrizione contiene caratteri non permessi.',
                'tipo_confezionamento.required' => 'Il tipo di confezionamento è obbligatorio.',
                'tipo_confezionamento.in' => 'Il tipo di confezionamento deve essere primario, secondario o terziario.'
            ]);
        } elseif ($table === 'banks') {
            // Validazione specifica per banks
            $validated = $request->validate(Bank::validationRules($id), [
                'code.required' => 'Il codice banca è obbligatorio.',
                'code.unique' => 'Il codice banca esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della banca è obbligatorio.',
                'abi_code.regex' => 'Il codice ABI deve essere di 5 cifre.',
                'bic_swift.regex' => 'Il codice BIC/SWIFT non è valido.',
                'email.email' => 'L\'indirizzo email non è valido.',
                'website.url' => 'L\'URL del sito web non è valido.'
            ]);
        } elseif ($table === 'product_categories') {
            // Validazione specifica per product_categories
            $validated = $request->validate(ProductCategory::validationRules($id), [
                'code.required' => 'Il codice categoria è obbligatorio.',
                'code.unique' => 'Il codice categoria esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'parent_id.exists' => 'La categoria padre selezionata non è valida.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona può contenere solo lettere minuscole, numeri e -.'
            ]);
        } elseif ($table === 'customer_categories') {
            // Validazione specifica per customer_categories (update)
            $validated = $request->validate(CustomerCategory::validationRules($id), [
                'code.required' => 'Il codice categoria cliente è obbligatorio.',
                'code.unique' => 'Il codice categoria cliente esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria cliente è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'type.required' => 'Il tipo di categoria è obbligatorio.',
                'type.in' => 'Il tipo di categoria deve essere valido (B2B, B2C, VIP, ecc.).',
                'discount_percentage.required' => 'La percentuale di sconto è obbligatoria.',
                'discount_percentage.numeric' => 'La percentuale di sconto deve essere un numero.',
                'discount_percentage.min' => 'La percentuale di sconto non può essere negativa.',
                'discount_percentage.max' => 'La percentuale di sconto non può superare il 100%.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.'
            ]);
        } elseif ($table === 'supplier_categories') {
            // Validazione specifica per supplier_categories (update)
            $validated = $request->validate(SupplierCategory::validationRules($id), [
                'code.required' => 'Il codice categoria fornitore è obbligatorio.',
                'code.unique' => 'Il codice categoria fornitore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della categoria fornitore è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'category_type.required' => 'Il tipo di categoria è obbligatorio.',
                'category_type.in' => 'Il tipo deve essere valido (STRATEGIC, PREFERRED, ecc.).',
                'reliability_rating.required' => 'Il rating di affidabilità è obbligatorio.',
                'reliability_rating.integer' => 'Il rating deve essere un numero intero.',
                'reliability_rating.min' => 'Il rating deve essere almeno 1.',
                'reliability_rating.max' => 'Il rating non può superare 5.',
                'security_clearance_level.required' => 'Il livello di sicurezza è obbligatorio.',
                'security_clearance_level.in' => 'Il livello di sicurezza deve essere valido.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.'
            ]);
            
            // Controllo riferimento circolare per update
            if ($request->parent_id && $request->parent_id != $item->parent_id) {
                $category = new ProductCategory();
                $category->id = $id; // Simula categoria esistente per controllo circolare
                if ($category->hasCircularReference($request->parent_id)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['parent_id' => ['Riferimento circolare non permesso.']]
                    ], 422);
                }
            }
        } elseif ($table === 'size_colors') {
            // Validazione specifica per size_colors (update)
            $validated = $request->validate(SizeColor::validationRules($id), [
                'code.required' => 'Il codice taglia/colore è obbligatorio.',
                'code.unique' => 'Il codice taglia/colore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della taglia/colore è obbligatorio.',
                'name.regex' => 'Il nome contiene caratteri non permessi.',
                'type.required' => 'Il tipo (taglia/colore) è obbligatorio.',
                'type.in' => 'Il tipo deve essere "size" o "color".',
                'hex_value.required_if' => 'Il valore esadecimale è obbligatorio per i colori.',
                'hex_value.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'size_category.required_if' => 'La categoria di taglia è obbligatoria per le taglie.',
                'size_category.in' => 'La categoria di taglia deve essere valida.',
                'numeric_value.numeric' => 'Il valore numerico deve essere un numero.',
                'chest_cm.integer' => 'La circonferenza petto deve essere un numero intero.',
                'waist_cm.integer' => 'La circonferenza vita deve essere un numero intero.',
                'hip_cm.integer' => 'La circonferenza fianchi deve essere un numero intero.',
                'price_modifier.numeric' => 'Il modificatore prezzo deve essere un numero.',
                'default_stock_level.integer' => 'Il livello stock deve essere un numero intero.',
                'icon.regex' => 'L\'icona deve iniziare con "bi-" seguito da caratteri validi.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.'
            ]);
        } elseif ($table === 'warehouse_causes') {
            // Validazione specifica per warehouse_causes (update)
            $validated = $request->validate(WarehouseCause::validationRulesForUpdate($id), [
                'code.required' => 'Il codice causale è obbligatorio.',
                'code.unique' => 'Il codice causale esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'description.required' => 'La descrizione è obbligatoria.',
                'description.min' => 'La descrizione deve essere di almeno 3 caratteri.',
                'movement_type.required' => 'Il tipo di movimento è obbligatorio.',
                'movement_type.in' => 'Il tipo di movimento deve essere valido (in, out, adjustment).',
                'category.required' => 'La categoria è obbligatoria.',
                'category.in' => 'La categoria deve essere valida.',
                'priority_level.required' => 'Il livello di priorità è obbligatorio.',
                'priority_level.in' => 'Il livello di priorità deve essere valido.',
                'fiscal_code.regex' => 'Il codice fiscale può contenere solo lettere maiuscole e numeri.',
                'notify_threshold.numeric' => 'La soglia di notifica deve essere un numero.',
                'notify_threshold.min' => 'La soglia di notifica non può essere negativa.',
                'color_hex.required' => 'Il colore identificativo è obbligatorio.',
                'color_hex.regex' => 'Il colore deve essere in formato esadecimale (es: #FF5733).',
                'icon.required' => 'L\'icona è obbligatoria.',
                'icon.regex' => 'L\'icona può contenere solo lettere minuscole, numeri e -.',
                'default_location.max' => 'L\'ubicazione predefinita non può superare i 100 caratteri.',
                'compliance_notes.max' => 'Le note di conformità non possono superare i 500 caratteri.'
            ]);
        } elseif ($table === 'color_variants') {
            // Validazione semplice per color_variants (update)
            $validated = $request->validate(ColorVariant::validationRulesForUpdate($id), [
                'code.required' => 'Il codice colore è obbligatorio.',
                'code.unique' => 'Il codice colore esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome del colore è obbligatorio.',
                'name.min' => 'Il nome deve essere di almeno 2 caratteri.',
                'description.max' => 'La descrizione non può superare i 500 caratteri.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.',
                'sort_order.min' => 'L\'ordine di ordinamento non può essere negativo.',
                'sort_order.max' => 'L\'ordine di ordinamento non può superare 9999.'
            ]);
        } elseif ($table === 'conditions') {
            // Validazione semplice per conditions (update)
            $validated = $request->validate(Condition::validationRulesForUpdate($id), [
                'code.required' => 'Il codice condizione è obbligatorio.',
                'code.unique' => 'Il codice condizione esiste già.',
                'code.regex' => 'Il codice può contenere solo lettere maiuscole, numeri, _ e -.',
                'name.required' => 'Il nome della condizione è obbligatorio.',
                'name.min' => 'Il nome deve essere di almeno 2 caratteri.',
                'description.max' => 'La descrizione non può superare i 500 caratteri.',
                'sort_order.integer' => 'L\'ordine di ordinamento deve essere un numero intero.',
                'sort_order.min' => 'L\'ordine di ordinamento non può essere negativo.',
                'sort_order.max' => 'L\'ordine di ordinamento non può superare 9999.'
            ]);
        } else {
            // Validazione standard per altre tabelle
            $rules = $config['validation_rules'];
            
            // Per l'aggiornamento, modifica regola univocità codice se presente
            if (isset($rules['code']) && str_contains($rules['code'], 'unique:')) {
                $rules['code'] = str_replace('unique:', "unique:,code,{$id},id,", $rules['code']);
            }
            
            $validated = $request->validate($rules);
        }

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

        // Gestione risposta AJAX per aspetto_beni, banks, size_colors, warehouse_causes, color_variants e conditions
        if (in_array($table, ['aspetto_beni', 'banks', 'size_colors', 'warehouse_causes', 'color_variants', 'conditions']) && $request->expectsJson()) {
            $messages = [
                'banks' => 'Banca aggiornata con successo!',
                'aspetto_beni' => 'Aspetto dei beni aggiornato con successo!',
                'size_colors' => 'Taglia/Colore aggiornato con successo!',
                'warehouse_causes' => 'Causale di Magazzino aggiornata con successo!',
                'color_variants' => 'Colore Variante aggiornato con successo!',
                'conditions' => 'Condizione aggiornata con successo!'
            ];
            return response()->json([
                'success' => true,
                'message' => $messages[$table],
                'item' => $item
            ]);
        }

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

        // Gestione risposta AJAX per aspetto_beni, banks, size_colors, warehouse_causes, color_variants e conditions
        if (in_array($table, ['aspetto_beni', 'banks', 'size_colors', 'warehouse_causes', 'color_variants', 'conditions']) && request()->expectsJson()) {
            $messages = [
                'banks' => 'Banca eliminata con successo!',
                'aspetto_beni' => 'Aspetto dei beni eliminato con successo!',
                'size_colors' => 'Taglia/Colore eliminato con successo!',
                'warehouse_causes' => 'Causale di Magazzino eliminata con successo!',
                'color_variants' => 'Colore Variante eliminato con successo!',
                'conditions' => 'Condizione eliminata con successo!'
            ];
            return response()->json([
                'success' => true,
                'message' => $messages[$table]
            ]);
        }

        return back()->with('success', "{$config['name']}: record rimosso con successo!");
    }

    /**
     * API endpoint per AJAX requests
     */
    public function apiData(string $table, Request $request)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        
        $items = $modelClass::select(['id', 'code', 'name', 'description'])
            ->orderBy('code')
            ->orderBy('name')
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
     * Ottieni singolo item per AJAX (per modifica/visualizzazione)
     */
    public function getItem(string $table, int $id)
    {
        $this->validateTableName($table);
        
        $modelClass = self::TABLE_MODELS[$table];
        $item = $modelClass::findOrFail($id);
        
        // Gestione specifica per aspetto_beni
        if ($table === 'aspetto_beni') {
            return response()->json($item);
        }
        
        return response()->json($item);
    }

    /**
     * Configuratore speciale per Associazioni Nature IVA
     */
    public function vatNatureConfigurator()
    {
        $this->auditService->logConfigurationAccess('view_vat_nature_configurator');
        
        $taxRates = TaxRate::where('active', true)->get();
        $vatNatures = VatNature::where('active', true)->get();
        $associations = VatNatureAssociation::with(['taxRate', 'vatNature'])->where('active', true)->get();
        
        return view('configurations.system-tables.vat-nature-configurator', [
            'taxRates' => $taxRates,
            'vatNatures' => $vatNatures,
            'associations' => $associations
        ]);
    }

    /**
     * Configuratore Premium per Aliquote IVA (Card #2)
     * Design enterprise superiore ad Aruba
     */
    public function taxRatesConfigurator()
    {
        $this->auditService->logConfigurationAccess('view_tax_rates_configurator');
        
        // Carica aliquote con statistiche avanzate
        $taxRates = TaxRate::with(['creator', 'updater'])
            ->orderByDefault()
            ->get();
        
        // Statistiche business per dashboard
        $stats = [
            'total_rates' => $taxRates->count(),
            'active_rates' => $taxRates->where('active', true)->count(),
            'standard_rates' => $taxRates->whereIn('percentuale', [22.00, 10.00, 4.00, 0.00])->count(),
            'custom_rates' => $taxRates->whereNotIn('percentuale', [22.00, 10.00, 4.00, 0.00])->count(),
        ];
        
        // Aliquote standard italiane per quick setup
        $standardRates = [
            ['code' => 'IVA22', 'name' => 'Aliquota Ordinaria', 'percentuale' => 22.00, 'color' => '#d63031'],
            ['code' => 'IVA10', 'name' => 'Aliquota Ridotta', 'percentuale' => 10.00, 'color' => '#00b894'],
            ['code' => 'IVA4', 'name' => 'Aliquota Super-Ridotta', 'percentuale' => 4.00, 'color' => '#0984e3'],
            ['code' => 'IVA0', 'name' => 'Aliquota Zero', 'percentuale' => 0.00, 'color' => '#636e72'],
        ];
        
        return view('configurations.system-tables.tax-rates-configurator', [
            'taxRates' => $taxRates,
            'stats' => $stats,
            'standardRates' => $standardRates
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
        
        $items = $modelClass::orderBy('code')->orderBy('name')->get();
        
        // Implementazione export (da completare con libreria export)
        return response()->json([
            'message' => "Export {$config['name']} completato",
            'count' => $items->count()
        ]);
    }

    /**
     * Ottieni model class per una tabella
     */
    private function getModelForTable(string $table)
    {
        $modelReference = self::TABLE_MODELS[$table];
        
        // Se è una stringa (nome tabella), usa GenericSystemTable
        if (is_string($modelReference) && !class_exists($modelReference)) {
            return (new GenericSystemTable())->setTable($modelReference);
        }
        
        // Se è una classe, usa quella
        return new $modelReference();
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
    
    // ==========================================
    // GESTIONE TABELLE PREFERITE (NEW!)
    // ==========================================
    
    /**
     * Aggiungi tabella ai preferiti
     */
    public function addToFavorites(Request $request)
    {
        $request->validate([
            'table_objname' => 'required|string|max:100'
        ]);
        
        $tableObjname = $request->input('table_objname');
        
        // Verifica che la tabella esista
        $systemTable = SystemPagebuilder::where('objname', $tableObjname)->first();
        if (!$systemTable) {
            return response()->json(['error' => 'Tabella non trovata'], 404);
        }
        
        // Controlla se già nei preferiti
        $existing = UserFavoriteTable::forUser(Auth::id())
            ->where('table_objname', $tableObjname)
            ->first();
            
        if ($existing) {
            return response()->json(['message' => 'Tabella già nei preferiti'], 200);
        }
        
        // Aggiungi ai preferiti
        UserFavoriteTable::create([
            'user_id' => Auth::id(),
            'table_objname' => $tableObjname,
            'sort_order' => UserFavoriteTable::forUser(Auth::id())->count()
        ]);
        
        $this->auditService->logConfigurationAccess("add_favorite_table_{$tableObjname}");
        
        return response()->json(['message' => 'Tabella aggiunta ai preferiti!']);
    }
    
    /**
     * Rimuovi tabella dai preferiti
     */
    public function removeFromFavorites(Request $request)
    {
        $request->validate([
            'table_objname' => 'required|string|max:100'
        ]);
        
        $tableObjname = $request->input('table_objname');
        
        $favorite = UserFavoriteTable::forUser(Auth::id())
            ->where('table_objname', $tableObjname)
            ->first();
            
        if (!$favorite) {
            return response()->json(['error' => 'Tabella non nei preferiti'], 404);
        }
        
        $favorite->delete();
        
        $this->auditService->logConfigurationAccess("remove_favorite_table_{$tableObjname}");
        
        return response()->json(['message' => 'Tabella rimossa dai preferiti!']);
    }
    
    /**
     * Traccia utilizzo di una tabella
     */
    public function trackTableUsage(Request $request)
    {
        $request->validate([
            'table_objname' => 'required|string|max:100'
        ]);
        
        $tableObjname = $request->input('table_objname');
        
        // Se la tabella è nei preferiti, aggiorna i contatori
        $favorite = UserFavoriteTable::forUser(Auth::id())
            ->where('table_objname', $tableObjname)
            ->first();
            
        if ($favorite) {
            $favorite->incrementUsage();
        }
        
        return response()->json(['message' => 'Utilizzo registrato']);
    }
}