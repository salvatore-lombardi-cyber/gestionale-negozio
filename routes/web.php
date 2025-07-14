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
    
    // Profile routes (da Breeze)
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');   
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
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