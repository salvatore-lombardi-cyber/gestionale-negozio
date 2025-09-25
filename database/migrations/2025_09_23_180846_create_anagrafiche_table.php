<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anagrafiche', function (Blueprint $table) {
            $table->id();
            
            // IDENTIFICAZIONE
            $table->enum('tipo', ['cliente', 'fornitore', 'vettore', 'agente', 'articolo', 'servizio']);
            $table->string('codice_interno')->unique(); // Generato automaticamente
            $table->enum('tipo_soggetto', ['definitivo', 'potenziale'])->nullable(); // Solo per soggetti
            
            // DATI BASE
            $table->string('nome')->nullable(); // ragione_sociale per aziende
            $table->string('cognome')->nullable(); // Solo per persone fisiche
            $table->text('altro')->nullable(); // Informazioni aggiuntive
            
            // INDIRIZZO COMPLETO
            $table->string('indirizzo')->nullable();
            $table->string('provincia', 2)->nullable(); // Sigla provincia (MI, RM, etc)
            $table->string('comune')->nullable();
            $table->string('cap', 5)->nullable();
            $table->string('nazione', 3)->default('ITA'); // Codice ISO 3 lettere
            
            // DATI FISCALI
            $table->string('codice_fiscale', 16)->nullable();
            $table->string('partita_iva', 11)->nullable();
            $table->string('partita_iva_cee')->nullable();
            $table->string('codice_sdi')->nullable(); // Sistema di Interscambio
            $table->boolean('is_pubblica_amministrazione')->default(false);
            $table->boolean('split_payment')->default(false);
            
            // CONTATTI
            $table->string('telefono_1')->nullable();
            $table->string('telefono_2')->nullable();
            $table->string('fax_1')->nullable();
            $table->string('fax_2')->nullable();
            $table->string('contatto_referente')->nullable();
            $table->string('email')->nullable();
            $table->string('sito_web')->nullable();
            $table->string('pec')->nullable(); // Posta Elettronica Certificata
            $table->string('numero_tessera')->nullable();
            
            // DATI COMMERCIALI GENERICI
            $table->decimal('sconto_1', 5, 2)->nullable(); // Percentuale sconto
            $table->decimal('sconto_2', 5, 2)->nullable(); // Secondo sconto
            $table->string('valuta', 3)->default('EUR'); // Codice ISO valuta
            
            // COORDINATE BANCARIE
            $table->string('banca')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift')->nullable();
            
            // DATI SPECIFICI FORNITORI
            $table->string('codice_fornitore')->nullable();
            $table->string('condizioni_pagamento')->nullable();
            $table->integer('lead_time_giorni')->nullable();
            $table->string('categoria_merceologica')->nullable();
            $table->string('responsabile_acquisti')->nullable();
            $table->text('note_interne')->nullable();
            
            // DATI SPECIFICI VETTORI
            $table->string('codice_vettore')->nullable();
            $table->json('zone_consegna')->nullable(); // Array di zone
            $table->json('tariffe_trasporto')->nullable(); // Struttura tariffe
            $table->integer('tempi_standard_ore')->nullable();
            $table->enum('tipo_trasporto', ['proprio', 'terzi'])->nullable();
            $table->boolean('assicurazione_disponibile')->default(false);
            
            // DATI SPECIFICI AGENTI
            $table->string('codice_agente')->nullable();
            $table->decimal('percentuale_provvigione', 5, 2)->nullable();
            $table->json('zone_competenza')->nullable(); // Array di zone
            $table->enum('tipo_contratto', ['dipendente', 'collaboratore', 'esterno'])->nullable();
            $table->date('data_inizio_contratto')->nullable();
            $table->decimal('obiettivi_vendita_annuali', 10, 2)->nullable();
            
            // DATI SPECIFICI ARTICOLI
            $table->string('codice_articolo')->nullable();
            $table->string('categoria_articolo')->nullable();
            $table->string('unita_misura')->nullable();
            $table->decimal('prezzo_acquisto', 10, 4)->nullable();
            $table->decimal('prezzo_vendita', 10, 4)->nullable();
            $table->integer('scorta_minima')->nullable();
            $table->unsignedBigInteger('fornitore_principale_id')->nullable();
            $table->text('note_tecniche')->nullable();
            $table->string('codice_barre')->nullable();
            $table->text('descrizione_estesa')->nullable();
            
            // DATI SPECIFICI SERVIZI
            $table->string('codice_servizio')->nullable();
            $table->string('categoria_servizio')->nullable();
            $table->integer('durata_standard_minuti')->nullable();
            $table->decimal('tariffa_oraria', 8, 2)->nullable();
            $table->json('competenze_richieste')->nullable(); // Array competenze
            $table->json('materiali_inclusi')->nullable(); // Array materiali
            
            // METADATI
            $table->boolean('attivo')->default(true);
            $table->timestamps();
            
            // INDICI
            $table->index(['tipo', 'attivo']);
            $table->index(['tipo', 'nome']);
            $table->index(['provincia', 'comune']);
            $table->index('codice_fiscale');
            $table->index('partita_iva');
            $table->index('email');
            
            // FOREIGN KEYS
            $table->foreign('fornitore_principale_id')->references('id')->on('anagrafiche')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anagrafiche');
    }
};