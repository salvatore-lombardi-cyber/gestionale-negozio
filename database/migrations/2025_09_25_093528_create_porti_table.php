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
        Schema::create('porti', function (Blueprint $table) {
            $table->id();
            $table->string('description', 100)->comment('Descrizione condizione di trasporto (es: Franco fabbrica, Franco destino)');
            $table->string('comment', 255)->nullable()->comment('Commento aggiuntivo sulla condizione di trasporto');
            $table->timestamps();
            
            // Indici per performance
            $table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porti');
    }
};
