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
    
    // Route Fatturazione con controllo permessi
    Route::middleware(['permissions:fatturazione,read'])->group(function () {
        Route::get('/fatturazione', [App\Http\Controllers\FatturazioneController::class, 'index'])->name('fatturazione.index');
        Route::get('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'show'])->name('fatturazione.show');
        Route::get('/fatturazione/riepilogo', [App\Http\Controllers\FatturazioneController::class, 'riepilogo'])->name('fatturazione.riepilogo');
        Route::get('/fatturazione/fatture-ricevute', [App\Http\Controllers\FatturazioneController::class, 'fatture_ricevute'])->name('fatturazione.fatture_ricevute');
        Route::get('/fatturazione/analytics', [App\Http\Controllers\FatturazioneController::class, 'analytics'])->name('fatturazione.analytics');
        Route::get('/fatturazione/{vendita}/pdf', [App\Http\Controllers\FatturazioneController::class, 'downloadPdf'])->name('fatturazione.pdf');
    });
    
    Route::middleware(['permissions:fatturazione,write'])->group(function () {
        Route::get('/fatturazione/crea', [App\Http\Controllers\FatturazioneController::class, 'create'])->name('fatturazione.create');
        Route::post('/fatturazione', [App\Http\Controllers\FatturazioneController::class, 'store'])->name('fatturazione.store');
        Route::get('/fatturazione/{vendita}/modifica', [App\Http\Controllers\FatturazioneController::class, 'edit'])->name('fatturazione.edit');
        Route::put('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'update'])->name('fatturazione.update');
    });
    
    Route::middleware(['permissions:fatturazione,delete'])->group(function () {
        Route::delete('/fatturazione/{vendita}', [App\Http\Controllers\FatturazioneController::class, 'destroy'])->name('fatturazione.destroy');
    });
    
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
    
    // Route Vendite con controllo permessi
    Route::middleware(['permissions:vendite,read'])->group(function () {
        Route::get('/vendite', [VenditaController::class, 'index'])->name('vendite.index');
        Route::get('/vendite/{vendita}', [VenditaController::class, 'show'])->name('vendite.show');
    });
    
    Route::middleware(['permissions:vendite,write'])->group(function () {
        Route::get('/vendite/create', [VenditaController::class, 'create'])->name('vendite.create');
        Route::post('/vendite', [VenditaController::class, 'store'])->name('vendite.store');
        Route::get('/vendite/{vendita}/edit', [VenditaController::class, 'edit'])->name('vendite.edit');
        Route::put('/vendite/{vendita}', [VenditaController::class, 'update'])->name('vendite.update');
    });
    
    Route::middleware(['permissions:vendite,delete'])->group(function () {
        Route::delete('/vendite/{vendita}', [VenditaController::class, 'destroy'])->name('vendite.destroy');
    });
    
    // Route Magazzino con controllo permessi
    Route::middleware(['permissions:magazzino,read'])->group(function () {
        Route::get('/magazzino', [MagazzinoController::class, 'index'])->name('magazzino.index');
        Route::get('/magazzino/{magazzino}', [MagazzinoController::class, 'show'])->name('magazzino.show');
        Route::get('/magazzino/prodotto/{prodotto}', [MagazzinoController::class, 'dettaglioProdotto'])->name('magazzino.dettaglio-prodotto');
    });
    
    Route::middleware(['permissions:magazzino,write'])->group(function () {
        Route::get('/magazzino/create', [MagazzinoController::class, 'create'])->name('magazzino.create');
        Route::post('/magazzino', [MagazzinoController::class, 'store'])->name('magazzino.store');
        Route::get('/magazzino/{magazzino}/edit', [MagazzinoController::class, 'edit'])->name('magazzino.edit');
        Route::put('/magazzino/{magazzino}', [MagazzinoController::class, 'update'])->name('magazzino.update');
        Route::get('/magazzino/caricamento-multiplo', [MagazzinoController::class, 'caricamentoMultiplo'])->name('magazzino.caricamento-multiplo');
        Route::post('/magazzino/salva-multiplo', [MagazzinoController::class, 'salvaMultiplo'])->name('magazzino.salva-multiplo');
    });
    
    Route::middleware(['permissions:magazzino,delete'])->group(function () {
        Route::delete('/magazzino/{magazzino}', [MagazzinoController::class, 'destroy'])->name('magazzino.destroy');
    });
    
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
    

    // Route Configurazioni con controllo permessi
    Route::prefix('configurations')->name('configurations.')->group(function () {
        
        // Route READ (visualizzazione configurazioni)
        Route::middleware(['permissions:configurazioni,read'])->group(function () {
            Route::get('/', [ConfigurationController::class, 'index'])->name('index');
            Route::get('/utente', [ConfigurationController::class, 'utente'])->name('utente');
            Route::get('/bank-accounts', [ConfigurationController::class, 'bankAccounts'])->name('bank-accounts');
            Route::get('/settings', [ConfigurationController::class, 'settings'])->name('settings');
            
            // Tabelle di Sistema - Solo visualizzazione
            Route::get('/system-tables', [App\Http\Controllers\SystemTablesController::class, 'index'])->name('system-tables.index');
            Route::get('/system-tables/{table}', [App\Http\Controllers\SystemTablesController::class, 'show'])->name('system-tables.show');
            Route::get('/system-tables/{table}/export', [App\Http\Controllers\SystemTablesController::class, 'export'])->name('system-tables.export');
            Route::get('/system-tables/{table}/api', [App\Http\Controllers\SystemTablesController::class, 'apiData'])->name('system-tables.api');
            Route::get('/associazioni-nature-iva', [App\Http\Controllers\SystemTablesController::class, 'associazioniNatureIva'])->name('associazioni-nature-iva');
            Route::get('/tax-rates-configurator', [App\Http\Controllers\SystemTablesController::class, 'taxRatesConfigurator'])->name('tax-rates-configurator');
        });
        
        // Route WRITE (modifica configurazioni)
        Route::middleware(['permissions:configurazioni,write'])->group(function () {
            Route::post('/utente', [ConfigurationController::class, 'updateUtente'])->name('utente.update');
            Route::post('/bank-accounts', [ConfigurationController::class, 'storeBankAccount'])->name('bank-accounts.store');
            Route::put('/bank-accounts/{uuid}', [ConfigurationController::class, 'updateBankAccount'])->name('bank-accounts.update');
            Route::post('/settings', [ConfigurationController::class, 'updateSettings'])->name('settings.update');
            
            // Tabelle di Sistema - Modifica
            Route::get('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'edit'])->name('system-tables.edit');
            Route::post('/system-tables/{table}', [App\Http\Controllers\SystemTablesController::class, 'store'])->name('system-tables.store');
            Route::put('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'update'])->name('system-tables.update');
            Route::post('/favorites/add', [App\Http\Controllers\SystemTablesController::class, 'addToFavorites'])->name('system-tables.favorites.add');
            Route::post('/track-usage', [App\Http\Controllers\SystemTablesController::class, 'trackTableUsage'])->name('system-tables.track-usage');
            
            // Route legacy per compatibilità
            Route::post('/tax-rates', [ConfigurationController::class, 'storeTaxRate'])->name('tax-rates.store');
            Route::post('/payment-methods', [ConfigurationController::class, 'storePaymentMethod'])->name('payment-methods.store');
            Route::post('/currencies', [ConfigurationController::class, 'storeCurrency'])->name('currencies.store');
        });
        
        // Route DELETE (eliminazione)
        Route::middleware(['permissions:configurazioni,delete'])->group(function () {
            Route::delete('/bank-accounts/{uuid}', [ConfigurationController::class, 'deleteBankAccount'])->name('bank-accounts.delete');
            Route::delete('/system-tables/{table}/{id}', [App\Http\Controllers\SystemTablesController::class, 'destroy'])->name('system-tables.destroy');
            Route::delete('/favorites/remove', [App\Http\Controllers\SystemTablesController::class, 'removeFromFavorites'])->name('system-tables.favorites.remove');
        });
        
        // Gestione Utenti (Sistema Multi-Utente Enterprise)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\UserManagementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}', [App\Http\Controllers\UserManagementController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('destroy');
            
            // Gestione Permessi
            Route::get('/{user}/permissions', [App\Http\Controllers\UserManagementController::class, 'permissions'])->name('permissions');
            Route::put('/{user}/permissions', [App\Http\Controllers\UserManagementController::class, 'updatePermissions'])->name('permissions.update');
            
            // Azioni Ajax
            Route::post('/{user}/toggle-status', [App\Http\Controllers\UserManagementController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/reset-password', [App\Http\Controllers\UserManagementController::class, 'resetPassword'])->name('reset-password');
        });
        
        // Sistema Gestione Tabelle Enterprise (V2) con controllo permessi
        Route::prefix('gestione-tabelle')->name('gestione-tabelle.')->group(function () {
            
            // Route READ - Dashboard e visualizzazione (route specifiche prima di quelle generiche)
            Route::middleware(['permissions:configurazioni,read'])->group(function () {
                Route::get('/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'index'])->name('index');
                Route::get('/favorites-v2/', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'getFavoriteTablesV2'])->name('favorites-v2.list');
                Route::get('/favorites-v2/stats', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'getTableStatsV2'])->name('favorites-v2.stats');
                Route::get('/{nomeTabella}/api/data', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'apiData'])->name('api.data');
                Route::get('/{nomeTabella}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'mostraTabella'])->name('tabella');
                Route::get('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'show'])->name('show')->where('id', '[0-9]+');
            });
            
            // Route WRITE - Creazione e modifica (route specifiche prima di quelle generiche)
            Route::middleware(['permissions:configurazioni,write'])->group(function () {
                // Gestione preferiti
                Route::post('/favorites-v2/add', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'addToFavoritesV2'])->name('favorites-v2.add');
                Route::post('/favorites-v2/track', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'trackTableUsageV2'])->name('favorites-v2.track');
                
                // Associazioni Nature IVA (specifiche prima delle generiche)
                Route::post('/associazioni-nature-iva', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'storeAssociazioneNaturaIva'])->name('associazioni-nature-iva.store');
                Route::get('/associazioni-nature-iva/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'showAssociazioneNaturaIva'])->name('associazioni-nature-iva.show')->where('id', '[0-9]+');
                Route::put('/associazioni-nature-iva/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'updateAssociazioneNaturaIva'])->name('associazioni-nature-iva.update')->where('id', '[0-9]+');
                
                // Route generiche per tabelle
                Route::get('/{nomeTabella}/create', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'create'])->name('create');
                Route::post('/{nomeTabella}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'store'])->name('store');
                Route::get('/{nomeTabella}/{id}/edit', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
                Route::put('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'update'])->name('update')->where('id', '[0-9]+');
            });
            
            // Route DELETE - Eliminazione
            Route::middleware(['permissions:configurazioni,delete'])->group(function () {
                Route::delete('/favorites-v2/remove', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'removeFromFavoritesV2'])->name('favorites-v2.remove');
                Route::delete('/associazioni-nature-iva/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroyAssociazioneNaturaIva'])->name('associazioni-nature-iva.destroy')->where('id', '[0-9]+');
                Route::delete('/{nomeTabella}/{id}', [App\Http\Controllers\Configurazioni\GestioneTabelleController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
            });
        });
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
    
    // Route Anagrafiche (spostato dentro gruppo autenticato)
    Route::prefix('anagrafiche')->name('anagrafiche.')->group(function () {
        Route::get('/', [AnagraficaController::class, 'index'])->name('index');
        Route::get('/{tipo}/lista', [AnagraficaController::class, 'lista'])->name('lista');
        Route::get('/{tipo}/create', [AnagraficaController::class, 'create'])->name('create');
        Route::post('/', [AnagraficaController::class, 'store'])->name('store');
        Route::get('/{tipo}/excel-export', [AnagraficaController::class, 'exportExcel'])->name('excel-export');
        Route::get('/{tipo}/export/{format}', [AnagraficaController::class, 'export'])->name('export');
        Route::get('/{tipo}/api/search', [AnagraficaController::class, 'apiSearch'])->name('api.search');
        Route::get('/{tipo}/{anagrafica}', [AnagraficaController::class, 'show'])->name('show');
        Route::get('/{tipo}/{anagrafica}/edit', [AnagraficaController::class, 'edit'])->name('edit');
        Route::put('/{tipo}/{anagrafica}', [AnagraficaController::class, 'update'])->name('update');
        Route::delete('/{tipo}/{anagrafica}', [AnagraficaController::class, 'destroy'])->name('destroy');
        Route::get('/{tipo}/{anagrafica}/duplicate', [AnagraficaController::class, 'duplicate'])->name('duplicate');
        Route::post('/{tipo}/{anagrafica}/duplicate', [AnagraficaController::class, 'storeDuplicate'])->name('store.duplicate');
        Route::get('/fornitori-list', [AnagraficaController::class, 'fornitorsList'])->name('fornitori.list');
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