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
        Schema::create('giacenze_magazzino', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodotto_id')->constrained('anagrafiche')->onDelete('cascade');
            $table->foreignId('deposito_id')->constrained('depositi')->onDelete('cascade');
            $table->decimal('quantita_attuale', 10, 3)->default(0); // Giacenza corrente
            $table->decimal('giacenza_minima', 10, 3)->nullable(); // Soglia minima
            $table->decimal('giacenza_massima', 10, 3)->nullable(); // Soglia massima
            $table->timestamp('ultimo_aggiornamento')->useCurrent();
            $table->timestamps();
            
            // Vincolo univocità: una giacenza per combinazione prodotto+deposito
            $table->unique(['prodotto_id', 'deposito_id']);
            
            // Indici per performance
            $table->index(['prodotto_id', 'deposito_id']);
            $table->index('quantita_attuale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giacenze_magazzino');
    }
};
