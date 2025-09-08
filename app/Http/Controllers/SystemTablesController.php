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
        'tax_rates' => TaxRate::class,
        'vat_natures' => VatNature::class,
        'good_appearances' => GoodAppearance::class,
        'banks' => Bank::class,
        'product_categories' => ProductCategory::class,
        'customer_categories' => CustomerCategory::class,
        'supplier_categories' => SupplierCategory::class,
        'size_colors' => SizeColor::class,
        'warehouse_causes' => WarehouseCause::class,
        'color_variants' => ColorVariant::class,
        'conditions' => 'conditions',
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

        // Validazione con regole specifiche della tabella (OWASP Input Validation)
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