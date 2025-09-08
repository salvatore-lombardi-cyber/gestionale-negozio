<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration enterprise per fornitori con standard B2B italiani
     * Superiore ai competitor con sicurezza e compliance totale
     */
    public function up(): void
    {
        Schema::create('fornitori', function (Blueprint $table) {
            $table->id();
            
            // Dati anagrafici fondamentali
            $table->string('ragione_sociale')->index(); // Per aziende
            $table->string('nome')->nullable(); // Per persone fisiche
            $table->string('cognome')->nullable(); // Per persone fisiche
            
            // Dati fiscali obbligatori B2B Italia
            $table->string('codice_fiscale', 16)->unique()->index();
            $table->string('partita_iva', 11)->nullable()->unique()->index();
            $table->enum('tipo_soggetto', ['persona_fisica', 'persona_giuridica', 'ente_pubblico'])->default('persona_giuridica');
            
            // Dati di contatto enterprise
            $table->string('telefono')->nullable()->index();
            $table->string('telefono_mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('pec')->nullable(); // PEC obbligatoria per aziende
            $table->string('sito_web')->nullable();
            
            // Indirizzo sede legale/operativa
            $table->text('indirizzo')->nullable();
            $table->string('citta')->nullable()->index();
            $table->string('provincia', 2)->nullable();
            $table->string('cap', 5)->nullable()->index();
            $table->string('paese', 2)->default('IT')->index(); // ISO 3166-1
            
            // Dati fatturazione elettronica B2B
            $table->string('codice_destinatario', 7)->nullable(); // Codice SDI
            $table->string('regime_fiscale')->default('RF01'); // Regime ordinario
            $table->boolean('split_payment')->default(false); // Per enti pubblici
            
            // Dati bancari e pagamento
            $table->string('iban')->nullable();
            $table->string('banca')->nullable();
            $table->string('swift_bic')->nullable(); // Per fornitori esteri
            $table->enum('modalita_pagamento', ['bonifico', 'rid', 'contanti', 'assegno', 'carta'])->default('bonifico');
            $table->integer('giorni_pagamento')->default(30);
            
            // Classificazione fornitore enterprise
            $table->string('categoria_merceologica')->nullable()->index();
            $table->string('codice_istat')->nullable(); // Richiesto per enti pubblici
            $table->enum('classe_fornitore', ['strategico', 'preferito', 'standard', 'occasionale'])->default('standard');
            $table->decimal('limite_credito', 10, 2)->nullable();
            
            // Dati per persona fisica
            $table->date('data_nascita')->nullable();
            $table->string('luogo_nascita')->nullable();
            $table->enum('genere', ['M', 'F', 'altro'])->nullable();
            
            // Gestione multilangua e internazionale
            $table->string('lingua_preferita', 2)->default('it');
            $table->string('valuta_preferita', 3)->default('EUR');
            
            // Compliance e audit trail enterprise
            $table->boolean('attivo')->default(true)->index();
            $table->timestamp('ultima_verifica_dati')->nullable();
            $table->json('note_interne')->nullable(); // JSON per flessibilità
            $table->string('referente_interno')->nullable(); // Chi gestisce il fornitore
            
            // Privacy e GDPR compliance
            $table->boolean('consenso_marketing')->default(false);
            $table->boolean('consenso_dati')->default(true);
            $table->timestamp('data_consenso')->nullable();
            
            // Timestamps enterprise
            $table->timestamps();
            $table->softDeletes(); // Soft delete per audit
            
            // Indici per performance enterprise
            $table->index(['attivo', 'tipo_soggetto']);
            $table->index(['categoria_merceologica', 'classe_fornitore']);
            $table->index(['created_at', 'attivo']);
            $table->index(['citta', 'provincia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornitori');
    }
};