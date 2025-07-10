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
    Schema::create('venditas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
        $table->date('data_vendita');
        $table->decimal('totale', 10, 2);
        $table->decimal('sconto', 5, 2)->default(0);
        $table->decimal('totale_finale', 10, 2);
        $table->enum('metodo_pagamento', ['contanti', 'carta', 'bonifico', 'assegno']);
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venditas');
    }
};
