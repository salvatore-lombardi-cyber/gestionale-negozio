<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrazione Enterprise per Vettori/Spedizionieri
 * 
 * Sistema di gestione vettori superiore ai competitor con:
 * - Dati completi per logistica italiana ed europea
 * - Integrazione con sistemi di tracking
 * - Calcolo automatico costi spedizione
 * - Analisi performance e tempi consegna
 * - Compliance normative trasporti
 */
return new class extends Migration
{
    /**
     * Crea tabella vettori con struttura enterprise
     */
    public function up(): void
    {
        Schema::create('vettori', function (Blueprint $table) {
            $table->id();
            
            // === DATI IDENTIFICATIVI ===
            $table->string('ragione_sociale')->index();
            $table->string('nome_commerciale')->nullable();
            $table->string('codice_vettore', 10)->unique()->index(); // Codice interno
            $table->enum('tipo_soggetto', ['persona_fisica', 'persona_giuridica', 'ente_pubblico'])->default('persona_giuridica');
            
            // Persona fisica (opzionali)
            $table->string('nome', 100)->nullable();
            $table->string('cognome', 100)->nullable();
            $table->date('data_nascita')->nullable();
            $table->enum('genere', ['M', 'F', 'altro'])->nullable();
            
            // === DATI FISCALI ITALIA ===
            $table->string('codice_fiscale', 16)->unique()->index();
            $table->string('partita_iva', 11)->nullable()->unique()->index();
            $table->string('regime_fiscale', 5)->nullable()->default('RF01'); // RF01=Ordinario
            $table->boolean('split_payment')->default(false); // PA
            
            // === DATI CONTATTO ===
            $table->string('email', 255)->nullable()->index();
            $table->string('pec', 255)->nullable(); // Obbligatoria per aziende
            $table->string('telefono', 20)->nullable();
            $table->string('telefono_mobile', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('sito_web', 500)->nullable();
            
            // === INDIRIZZO SEDE LEGALE ===
            $table->string('indirizzo', 500)->nullable();
            $table->string('citta', 100)->nullable()->index();
            $table->string('provincia', 2)->nullable()->index(); // Sigla provincia
            $table->string('cap', 5)->nullable()->index();
            $table->string('paese', 2)->default('IT')->index(); // ISO 3166-1 alpha-2
            
            // === INDIRIZZO OPERATIVO (se diverso) ===
            $table->string('indirizzo_operativo', 500)->nullable();
            $table->string('citta_operativa', 100)->nullable();
            $table->string('provincia_operativa', 2)->nullable();
            $table->string('cap_operativo', 5)->nullable();
            $table->string('paese_operativo', 2)->nullable();
            
            // === DATI TRASPORTO ===
            $table->enum('tipo_vettore', [
                'corriere_espresso',
                'trasporto_standard', 
                'trasporto_pesante',
                'logistica_integrata',
                'posta_ordinaria'
            ])->default('corriere_espresso');
            
            $table->json('servizi_offerti')->nullable(); // ["standard", "express", "same_day", "refrigerato"]
            $table->json('aree_copertura')->nullable(); // ["nazionale", "europa", "mondiale"]
            $table->json('tipologie_merci')->nullable(); // ["generale", "pericolosa", "deperibile", "fragile"]
            
            // === CONTRATTO E CONDIZIONI ===
            $table->decimal('costo_base_kg', 8, 4)->nullable(); // €/kg
            $table->decimal('costo_minimo_spedizione', 8, 2)->nullable();
            $table->decimal('soglia_franco', 10, 2)->nullable(); // Spedizione gratuita oltre €
            $table->json('fasce_peso')->nullable(); // Tariffario per fasce peso
            $table->json('supplementi')->nullable(); // Costi aggiuntivi
            
            // === PERFORMANCE E TRACKING ===
            $table->string('codice_tracking_prefisso', 10)->nullable();
            $table->string('api_tracking_url', 500)->nullable();
            $table->string('api_tracking_key', 255)->nullable();
            $table->integer('tempo_consegna_standard')->nullable(); // giorni lavorativi
            $table->integer('tempo_consegna_express')->nullable();
            $table->decimal('percentuale_puntualita', 5, 2)->nullable(); // %
            
            // === DATI BANCARI ===
            $table->string('iban', 34)->nullable();
            $table->enum('modalita_pagamento', [
                'bonifico',
                'rid',
                'contanti', 
                'assegno',
                'carta'
            ])->default('bonifico');
            $table->integer('giorni_pagamento')->default(30); // giorni dilazione
            
            // === CLASSIFICAZIONE ===
            $table->enum('classe_vettore', [
                'premium',     // Servizio premium, costi alti, affidabilità massima
                'standard',    // Rapporto qualità/prezzo ottimo
                'economico',   // Low cost ma affidabile
                'occasionale'  // Uso sporadico
            ])->default('standard');
            
            $table->decimal('valutazione', 3, 2)->nullable(); // 1.00-5.00
            $table->integer('numero_valutazioni')->default(0);
            
            // === ASSICURAZIONE E RESPONSABILITÀ ===
            $table->string('numero_polizza_assicurativa', 100)->nullable();
            $table->decimal('massimale_assicurazione', 12, 2)->nullable();
            $table->date('scadenza_polizza')->nullable();
            $table->string('compagnia_assicurativa', 255)->nullable();
            
            // === AUTORIZZAZIONI TRASPORTI ===
            $table->string('numero_iscrizione_albo', 100)->nullable();
            $table->string('licenza_trasporti', 100)->nullable();
            $table->json('abilitazioni_speciali')->nullable(); // ["merci_pericolose", "adr", "haccp"]
            
            // === STATO E CONTROLLO ===
            $table->boolean('attivo')->default(true)->index();
            $table->boolean('verificato')->default(false);
            $table->timestamp('ultima_verifica_dati')->nullable();
            $table->text('note_interne')->nullable();
            $table->string('referente_commerciale', 255)->nullable();
            $table->string('telefono_referente', 20)->nullable();
            
            // === PRIVACY GDPR ===
            $table->boolean('consenso_dati')->default(false); // Obbligatorio
            $table->boolean('consenso_marketing')->default(false);
            $table->timestamp('consenso_data')->nullable();
            $table->ipAddress('consenso_ip')->nullable();
            
            // === AUDIT TRAIL ===
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at per soft delete
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            // === INDICI PERFORMANCE ===
            $table->index(['attivo', 'classe_vettore']); // Filtri comuni
            $table->index(['tipo_vettore', 'attivo']); // Ricerca per tipo
            $table->index(['citta', 'provincia']); // Ricerca geografica
            $table->index(['created_at', 'attivo']); // Ordinamento
            $table->index('valutazione'); // Ordinamento per rating
            
            // === FOREIGN KEYS ===
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Elimina tabella vettori
     */
    public function down(): void
    {
        Schema::dropIfExists('vettori');
    }
};