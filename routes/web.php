<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenditaController;
use App\Http\Controllers\MagazzinoController;
use App\Http\Controllers\DdtController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AnagraficaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route per il cambio lingua
Route::get('/lang/{locale}', [App\Http\Controllers\LanguageController::class, 'changeLanguage'])->name('language.change');

// Route protette da autenticazione
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    
    // Magazzino Overview
    Route::get('/magazzino-overview', [App\Http\Controllers\MagazzinoOverviewController::class, 'index'])->name('magazzino-overview.index');
    
    // Fatturazione
    Route::get('/fatturazione', [App\Http\Controllers\FatturazioneController::class, 'index'])->name('fatturazione.index');
    Route::get('/fatturazione/crea', [App\Http\Controllers\FatturazioneController::class, 'create'])->name('fatturazione.create');
    Route::post('/fatturazione', [App\Http\Controllers\FatturazioneController::class, 'store'])->name('fatturazione.store');
    Route::get('/fatturazione/riepilogo', [App\Http\Controllers\FatturazioneController::class, 'riepilogo'])->name('fatturazione.riepilogo');
    Route::get('/fatturazione/fatture-ricevute', [App\Http\Controllers\FatturazioneController::class, 'fatture_ricevute'])->name('fatturazione.fatture_ricevute');
    Route::get('/fatturazione/analytics', [App\Http\Controllers\FatturazioneController::class, 'analytics'])->name('fatturazione.analytics');
    Route::get('/fatturazione/{vendita}/pdf', [App\Http\Controllers\FatturazioneController::class, 'downloadPdf'])->name('fatturazione.pdf');
    Route::get('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'show'])->name('fatturazione.show');
    Route::get('/fatturazione/{vendita}/modifica', [App\Http\Controllers\FatturazioneController::class, 'edit'])->name('fatturazione.edit');
    Route::put('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'update'])->name('fatturazione.update');
    Route::delete('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'destroy'])->name('fatturazione.destroy');
    
    // Profile routes (da Breeze)
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');   
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Route del gestionale
    Route::resource('vendite', VenditaController::class)->parameter('vendite', 'vendita');
    
    Route::get('/magazzino/caricamento-multiplo', [MagazzinoController::class, 'caricamentoMultiplo'])->name('magazzino.caricamento-multiplo');
    Route::post('/magazzino/salva-multiplo', [MagazzinoController::class, 'salvaMultiplo'])->name('magazzino.salva-multiplo');
    Route::get('/magazzino/prodotto/{prodotto}', [MagazzinoController::class, 'dettaglioProdotto'])->name('magazzino.dettaglio-prodotto');
    Route::resource('magazzino', MagazzinoController::class)->parameter('magazzino', 'magazzino');
    
    Route::resource('ddts', DdtController::class)->parameter('ddts', 'ddt');
    Route::get('/ddts/{ddt}/pdf', [DdtController::class, 'downloadPdf'])->name('ddts.pdf');
    Route::post('/ddts/{ddt}/email', [DdtController::class, 'sendEmail'])->name('ddts.email');
    
    
    // Route Gestione Etichette
    Route::prefix('labels')->name('labels.')->group(function () {
        Route::get('/', [LabelController::class, 'index'])->name('index');
        Route::post('/generate/{id}', [LabelController::class, 'generateSingle'])->name('generate-single');
        Route::post('/generate-variants/{id}', [LabelController::class, 'generateVariants'])->name('generate-variants');
        Route::post('/generate-all/{id}', [LabelController::class, 'generateAll'])->name('generate-all');
        Route::get('/preview/{id}', [LabelController::class, 'preview'])->name('preview');
        Route::post('/print/{id}', [LabelController::class, 'print'])->name('print');
        Route::get('/scanner', [LabelController::class, 'scanner'])->name('scanner');
        Route::post('/decode', [LabelController::class, 'decode'])->name('decode');
    });
    

    // Route Configurazioni
    Route::prefix('configurations')->name('configurations.')->group(function () {
        Route::get('/', [ConfigurationController::class, 'index'])->name('index');
        
        // Profilo Azienda
        Route::get('/company-profile', [ConfigurationController::class, 'companyProfile'])->name('company-profile');
        Route::post('/company-profile', [ConfigurationController::class, 'updateCompanyProfile'])->name('company-profile.update');
        
        // Coordinate Bancarie
        Route::get('/bank-accounts', [ConfigurationController::class, 'bankAccounts'])->name('bank-accounts');
        Route::post('/bank-accounts', [ConfigurationController::class, 'storeBankAccount'])->name('bank-accounts.store');
        Route::put('/bank-accounts/{uuid}', [ConfigurationController::class, 'updateBankAccount'])->name('bank-accounts.update');
        Route::delete('/bank-accounts/{uuid}', [ConfigurationController::class, 'deleteBankAccount'])->name('bank-accounts.delete');
        
        // Tabelle di Sistema - Dashboard 
        Route::middleware(['throttle:100,1'])->group(function () {
            Route::get('/system-tables', [App\Http\Controllers\SystemTablesController::class, 'index'])->name('system-tables.index');
            Route::get('/system-tables/{table}', [App\Http\Controllers\SystemTablesController::class, 'show'])->name('system-tables.show');
            Route::get('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'edit'])->name('system-tables.edit');
            Route::post('/system-tables/{table}', [App\Http\Controllers\SystemTablesController::class, 'store'])->name('system-tables.store');
            Route::put('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'update'])->name('system-tables.update');
            Route::delete('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'destroy'])->name('system-tables.destroy');
            Route::get('/system-tables/{table}/export', [App\Http\Controllers\SystemTablesController::class, 'export'])->name('system-tables.export');
            Route::get('/system-tables/{table}/api', [App\Http\Controllers\SystemTablesController::class, 'apiData'])->name('system-tables.api');
            Route::get('/associazioni-nature-iva', [App\Http\Controllers\SystemTablesController::class, 'associazioniNatureIva'])->name('associazioni-nature-iva');
            Route::get('/tax-rates-configurator', [App\Http\Controllers\SystemTablesController::class, 'taxRatesConfigurator'])->name('tax-rates-configurator');
            
            // Route per gestione preferiti (NEW!)
            Route::post('/favorites/add', [App\Http\Controllers\SystemTablesController::class, 'addToFavorites'])->name('system-tables.favorites.add');
            Route::delete('/favorites/remove', [App\Http\Controllers\SystemTablesController::class, 'removeFromFavorites'])->name('system-tables.favorites.remove');
            Route::post('/track-usage', [App\Http\Controllers\SystemTablesController::class, 'trackTableUsage'])->name('system-tables.track-usage');
        });
        
        // Route legacy per compatibilità
        Route::post('/tax-rates', [ConfigurationController::class, 'storeTaxRate'])->name('tax-rates.store');
        Route::post('/payment-methods', [ConfigurationController::class, 'storePaymentMethod'])->name('payment-methods.store');
        Route::post('/currencies', [ConfigurationController::class, 'storeCurrency'])->name('currencies.store');
        
        // Impostazioni
        Route::get('/settings', [ConfigurationController::class, 'settings'])->name('settings');
        Route::post('/settings', [ConfigurationController::class, 'updateSettings'])->name('settings.update');
        
        // Sistema Gestione Tabelle Enterprise (V2)
        Route::prefix('gestione-tabelle')->name('gestione-tabelle.')->group(function () {
            // Dashboard principale (throttle rimosso per sviluppo)
            Route::get('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'index'])->name('index');
            
            // ========================================
            // SISTEMA TABELLE FREQUENTI V2 - SENZA LIMITI
            // IMPORTANTE: Prima delle route generiche per evitare conflitti
            // ========================================
            Route::prefix('favorites-v2')->name('favorites-v2.')->group(function () {
                // Gestione preferiti
                Route::get('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'getFavoriteTablesV2'])->name('list');
                Route::post('/add', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'addToFavoritesV2'])->name('add');
                Route::delete('/remove', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'removeFromFavoritesV2'])->name('remove');
                
                // Tracking e analytics
                Route::post('/track', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'trackTableUsageV2'])->name('track');
                Route::get('/stats', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'getTableStatsV2'])->name('stats');
            });
                
                // Gestione tabella specifica
                Route::get('/{nomeTabella}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'mostraTabella'])->name('tabella');
                Route::get('/{nomeTabella}/create', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'create'])->name('create');
                Route::post('/{nomeTabella}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'store'])->name('store');
                Route::get('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'show'])->name('show');
                Route::get('/{nomeTabella}/{id}/edit', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'edit'])->name('edit');
                Route::put('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'update'])->name('update');
                Route::delete('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroy'])->name('destroy');
                
                // API per AJAX
                Route::get('/{nomeTabella}/api/data', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'apiData'])->name('api.data');
                
                // Route specifiche per Aliquote IVA
                Route::prefix('aliquote-iva')->name('aliquote-iva.')->group(function () {
                    Route::post('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'storeAliquotaIva'])->name('store');
                    Route::get('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'showAliquotaIva'])->name('show');
                    Route::put('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'updateAliquotaIva'])->name('update');
                    Route::delete('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroyAliquotaIva'])->name('destroy');
                });
                
                // Route specifiche per Associazioni Nature IVA
                Route::prefix('associazioni-nature-iva')->name('associazioni-nature-iva.')->group(function () {
                    Route::post('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'storeAssociazioneNaturaIva'])->name('store');
                    Route::get('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'showAssociazioneNaturaIva'])->name('show');
                    Route::put('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'updateAssociazioneNaturaIva'])->name('update');
                    Route::delete('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroyAssociazioneNaturaIva'])->name('destroy');
                });
                
                // Route specifiche per Banche
                Route::prefix('banche')->name('banche.')->group(function () {
                    Route::post('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'storeBanca'])->name('store');
                    Route::get('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'showBanca'])->name('show');
                    Route::put('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'updateBanca'])->name('update');
                    Route::delete('/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroyBanca'])->name('destroy');
                });
        });
    });
    
    // Route Anagrafiche - Sistema Unificato
    Route::prefix('anagrafiche')->name('anagrafiche.')->group(function () {
        // Dashboard principale con 6 card
        Route::get('/', [AnagraficaController::class, 'index'])->name('index');
        
        // Liste per tipologia
        Route::get('/{tipo}/lista', [AnagraficaController::class, 'lista'])->name('lista');
        
        // Creazione
        Route::get('/{tipo}/create', [AnagraficaController::class, 'create'])->name('create');
        Route::post('/', [AnagraficaController::class, 'store'])->name('store');
        
        // Export (PRIMA delle route parametriche!)
        Route::get('/{tipo}/export/{format}', [AnagraficaController::class, 'export'])->name('export');
        Route::get('/{tipo}/excel-export', [AnagraficaController::class, 'exportExcel'])->name('excelExport');
        
        // API per ricerche e filtri
        Route::get('/{tipo}/api/search', [AnagraficaController::class, 'apiSearch'])->name('api.search');
        Route::get('/fornitori-list', [AnagraficaController::class, 'fornitorsList'])->name('fornitori.list');
        
        // Visualizzazione, modifica, eliminazione
        Route::get('/{tipo}/{anagrafica}', [AnagraficaController::class, 'show'])->name('show');
        Route::get('/{tipo}/{anagrafica}/edit', [AnagraficaController::class, 'edit'])->name('edit');
        Route::put('/{tipo}/{anagrafica}', [AnagraficaController::class, 'update'])->name('update');
        Route::delete('/{tipo}/{anagrafica}', [AnagraficaController::class, 'destroy'])->name('destroy');
        
        // Duplicazione
        Route::get('/{tipo}/{anagrafica}/duplicate', [AnagraficaController::class, 'duplicate'])->name('duplicate');
        Route::post('/{tipo}/{anagrafica}/duplicate', [AnagraficaController::class, 'storeDuplicate'])->name('store.duplicate');
    });

    // Route Enterprise Features (Funzionalità Avanzate)
    Route::prefix('enterprise')->name('enterprise.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [App\Http\Controllers\EnterpriseController::class, 'dashboard'])->name('dashboard');
        Route::get('/business-intelligence', [App\Http\Controllers\EnterpriseController::class, 'businessIntelligence'])->name('business-intelligence');
        Route::get('/smart-inventory', [App\Http\Controllers\EnterpriseController::class, 'smartInventory'])->name('smart-inventory');
        Route::get('/security-center', [App\Http\Controllers\EnterpriseController::class, 'securityCenter'])->name('security-center');
        Route::get('/document-integrity', [App\Http\Controllers\EnterpriseController::class, 'documentIntegrity'])->name('document-integrity');
        Route::get('/performance-analytics', [App\Http\Controllers\EnterpriseController::class, 'performanceAnalytics'])->name('performance-analytics');
        
        // API per dashboard real-time
        Route::get('/api/metrics', [App\Http\Controllers\EnterpriseController::class, 'apiMetrics'])->name('api.metrics');
        Route::get('/api/security-alerts', [App\Http\Controllers\EnterpriseController::class, 'apiSecurityAlerts'])->name('api.security-alerts');
        
        // Document integrity verification
        Route::post('/verify-document/{documentId}', [App\Http\Controllers\EnterpriseController::class, 'verifyDocumentIntegrity'])->name('verify-document');
        
        // Data export
        Route::get('/export/business-data', [App\Http\Controllers\EnterpriseController::class, 'exportBusinessData'])->name('export.business-data');
    });
});

require __DIR__.'/auth.php';

// Route per cambio lingua
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'it'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.change');

// Routes AI Assistant
Route::prefix('ai-assistant')->name('ai.')->group(function () {
    Route::get('/', [App\Http\Controllers\AIAssistantController::class, 'index'])->name('index');
    Route::post('/ask', [App\Http\Controllers\AIAssistantController::class, 'ask'])->name('ask');
    Route::post('/analyze', [App\Http\Controllers\AIAssistantController::class, 'analyze'])->name('analyze');
    Route::post('/ask-products', [App\Http\Controllers\AIAssistantController::class, 'askProducts'])->name('ask-products');
    Route::post('/ask-customers', [App\Http\Controllers\AIAssistantController::class, 'askCustomers'])->name('ask-customers');
    Route::post('/ask-sales', [App\Http\Controllers\AIAssistantController::class, 'askSales'])->name('ask-sales');
    Route::get('/status', [App\Http\Controllers\AIAssistantController::class, 'status'])->name('status');
    Route::get('/suggestions', [App\Http\Controllers\AIAssistantController::class, 'suggestions'])->name('suggestions');
});

// Route API Calculator Chat
Route::post('/api/calculator-chat', [App\Http\Controllers\CalculatorChatController::class, 'calculate'])->middleware(['auth']);

// Route API Weather (no auth required for public weather data)
Route::get('/api/weather', [WeatherController::class, 'getCurrentWeather'])->name('api.weather');