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
        Schema::create('movimenti_magazzino', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // ID univoco per ogni movimento
            $table->foreignId('prodotto_id')->constrained('anagrafiche')->onDelete('cascade');
            $table->foreignId('deposito_id')->constrained('depositi')->onDelete('cascade');
            $table->foreignId('causale_id')->constrained('causali_magazzino')->onDelete('cascade');
            $table->enum('tipo_movimento', ['carico', 'scarico', 'trasferimento_uscita', 'trasferimento_ingresso']);
            $table->decimal('quantita', 10, 3); // Supporta decimali per kg, litri, metri
            $table->date('data_movimento');
            $table->string('riferimento')->nullable(); // Riferimento libero (DDT, Fattura, etc.)
            
            // Per trasferimenti
            $table->foreignId('deposito_sorgente_id')->nullable()->constrained('depositi');
            $table->foreignId('deposito_destinazione_id')->nullable()->constrained('depositi');
            $table->uuid('movimento_collegato_uuid')->nullable(); // Collega i 2 movimenti del trasferimento
            
            // Associazioni opzionali
            $table->foreignId('cliente_id')->nullable()->constrained('anagrafiche');
            $table->foreignId('fornitore_id')->nullable()->constrained('anagrafiche');
            
            // Audit trail
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('data_registrazione')->useCurrent();
            $table->text('note')->nullable();
            
            $table->timestamps();
            
            // Indici per performance
            $table->index(['data_movimento', 'tipo_movimento']);
            $table->index(['prodotto_id', 'deposito_id']);
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimenti_magazzino');
    }
};
