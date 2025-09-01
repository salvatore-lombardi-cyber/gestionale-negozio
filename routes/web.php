<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdottoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VenditaController;
use App\Http\Controllers\MagazzinoController;
use App\Http\Controllers\DdtController;
use App\Http\Controllers\LabelController;
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
    
    // Anagrafiche
    Route::get('/anagrafiche', [App\Http\Controllers\AnagraficheController::class, 'index'])->name('anagrafiche.index');
    
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
    Route::resource('prodotti', ProdottoController::class)->parameter('prodotti', 'prodotto');
    Route::resource('clienti', ClienteController::class)->parameter('clienti', 'cliente');
    Route::resource('vendite', VenditaController::class)->parameter('vendite', 'vendita');
    
    Route::get('/magazzino/caricamento-multiplo', [MagazzinoController::class, 'caricamentoMultiplo'])->name('magazzino.caricamento-multiplo');
    Route::post('/magazzino/salva-multiplo', [MagazzinoController::class, 'salvaMultiplo'])->name('magazzino.salva-multiplo');
    Route::get('/magazzino/prodotto/{prodotto}', [MagazzinoController::class, 'dettaglioProdotto'])->name('magazzino.dettaglio-prodotto');
    Route::resource('magazzino', MagazzinoController::class)->parameter('magazzino', 'magazzino');
    
    Route::resource('ddts', DdtController::class)->parameter('ddts', 'ddt');
    Route::get('/ddts/{ddt}/pdf', [DdtController::class, 'downloadPdf'])->name('ddts.pdf');
    Route::post('/ddts/{ddt}/email', [DdtController::class, 'sendEmail'])->name('ddts.email');
    
    // Route QR Code e Etichette
    Route::prefix('prodotti/{id}')->group(function () {
        Route::post('/generate-qr', [ProdottoController::class, 'generateQR'])->name('prodotti.generate-qr');
        Route::post('/generate-all-qr', [ProdottoController::class, 'generateAllQR'])->name('prodotti.generate-all-qr');
        Route::get('/labels', [ProdottoController::class, 'printLabels'])->name('prodotti.labels');
    });
    
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