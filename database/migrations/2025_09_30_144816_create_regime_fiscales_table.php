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
        Schema::create('regime_fiscales', function (Blueprint $table) {
            $table->id();
            $table->string('codice', 10)->unique(); // RF01, RF02, etc.
            $table->string('descrizione'); // Descrizione completa
            $table->boolean('attivo')->default(true);
            $table->timestamps();
            
            // Indice per performance
            $table->index('codice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regime_fiscales');
    }
};
