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
        Schema::create('comuni', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Nome comune
            $table->string('cap', 5); // Codice Avviamento Postale
            $table->string('provincia_sigla', 2); // FK a province.sigla
            $table->string('codice_catastale', 4)->nullable(); // Codice catastale
            $table->decimal('latitudine', 10, 8)->nullable();
            $table->decimal('longitudine', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index(['provincia_sigla', 'nome']);
            $table->index('cap');
            $table->index('codice_catastale');
            
            $table->foreign('provincia_sigla')->references('sigla')->on('province')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comuni');
    }
};