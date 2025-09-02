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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('ragione_sociale')->nullable();
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->enum('genere', ['M', 'F', 'Altro'])->nullable();
            $table->text('indirizzo_sede')->nullable();
            $table->string('cap')->nullable();
            $table->string('provincia')->nullable();
            $table->string('citta')->nullable();
            $table->string('nazione')->default('Italia');
            $table->string('telefono1')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('fax')->nullable();
            $table->string('cellulare1')->nullable();
            $table->string('cellulare2')->nullable();
            $table->string('email')->nullable();
            $table->string('sito_web')->nullable();
            $table->string('partita_iva')->nullable();
            $table->string('codice_attivita_iva')->nullable();
            $table->string('regime_fiscale')->nullable();
            $table->string('attivita')->nullable();
            $table->string('numero_tribunale')->nullable();
            $table->string('cciaa')->nullable();
            $table->decimal('capitale_sociale', 15, 2)->nullable();
            $table->string('provincia_nascita')->nullable();
            $table->string('luogo_nascita')->nullable();
            $table->date('data_nascita')->nullable();
            $table->boolean('iva_esente')->default(false);
            $table->string('sdi_username')->nullable();
            $table->string('sdi_password')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};