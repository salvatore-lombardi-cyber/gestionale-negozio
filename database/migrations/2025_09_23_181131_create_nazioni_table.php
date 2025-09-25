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
        Schema::create('nazioni', function (Blueprint $table) {
            $table->id();
            $table->string('codice_iso2', 2)->unique(); // IT, FR, DE, etc
            $table->string('codice_iso3', 3)->unique(); // ITA, FRA, DEU, etc  
            $table->string('nome_italiano'); // Italia, Francia, Germania
            $table->string('nome_inglese'); // Italy, France, Germany
            $table->string('valuta_iso', 3)->nullable(); // EUR, USD, GBP
            $table->string('prefisso_telefonico')->nullable(); // +39, +33, +49
            $table->boolean('unione_europea')->default(false);
            $table->timestamps();
            
            $table->index('codice_iso2');
            $table->index('codice_iso3');
            $table->index('unione_europea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nazioni');
    }
};