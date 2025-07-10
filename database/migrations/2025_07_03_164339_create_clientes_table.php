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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cognome');
        $table->string('telefono')->nullable();
        $table->string('email')->nullable();
        $table->text('indirizzo')->nullable();
        $table->string('citta')->nullable();
        $table->string('cap')->nullable();
        $table->date('data_nascita')->nullable();
        $table->enum('genere', ['M', 'F', 'Altro'])->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
