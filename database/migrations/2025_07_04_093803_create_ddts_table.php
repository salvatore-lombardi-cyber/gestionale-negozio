<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ddts', function (Blueprint $table) {
        $table->id();
        $table->string('numero_ddt')->unique(); // Es: DDT-2025-001
        $table->date('data_ddt');
        $table->foreignId('vendita_id')->constrained('venditas')->onDelete('cascade');
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        
        // Dati destinatario (può essere diverso dal cliente)
        $table->string('destinatario_nome');
        $table->string('destinatario_cognome');
        $table->string('destinatario_indirizzo');
        $table->string('destinatario_citta');
        $table->string('destinatario_cap');
        
        // Info trasporto
        $table->string('causale')->default('Vendita'); // Vendita, Reso, Riparazione, ecc.
        $table->string('trasportatore')->nullable(); // Chi effettua il trasporto
        $table->text('note')->nullable();
        
        // Stato DDT
        $table->enum('stato', ['bozza', 'confermato', 'spedito', 'consegnato'])->default('bozza');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ddts');
    }
};
