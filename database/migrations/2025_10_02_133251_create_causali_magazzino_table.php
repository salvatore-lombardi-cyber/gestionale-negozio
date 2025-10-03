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
        Schema::create('causali_magazzino', function (Blueprint $table) {
            $table->id();
            $table->string('codice')->unique();
            $table->string('descrizione');
            $table->enum('tipo_movimento', ['carico', 'scarico', 'trasferimento']);
            $table->boolean('attiva')->default(true);
            $table->boolean('sistema')->default(false); // Causali predefinite del sistema
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('causali_magazzino');
    }
};
