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
        Schema::create('province', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 2)->unique(); // ES: MI, RM, NA
            $table->string('nome'); // ES: Milano, Roma, Napoli
            $table->string('regione'); // ES: Lombardia, Lazio, Campania
            $table->string('zona', 10); // Nord, Centro, Sud, Isole
            $table->timestamps();
            
            $table->index('sigla');
            $table->index('regione');
            $table->index('zona');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province');
    }
};