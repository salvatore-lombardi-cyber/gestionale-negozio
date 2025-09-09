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
        Schema::create('aspetto_beni', function (Blueprint $table) {
            $table->id();
            $table->string('codice_aspetto', 10)->unique()->index()->comment('Codice univoco aspetto bene');
            $table->string('descrizione', 50)->index()->comment('Descrizione breve aspetto');
            $table->text('descrizione_estesa')->nullable()->comment('Descrizione dettagliata opzionale');
            $table->enum('tipo_confezionamento', ['primario', 'secondario', 'terziario'])->default('primario')->index()->comment('Tipo di confezionamento');
            $table->boolean('utilizzabile_ddt')->default(true)->index()->comment('Utilizzabile nei DDT');
            $table->boolean('utilizzabile_fatture')->default(true)->index()->comment('Utilizzabile in fatturazione');
            $table->boolean('attivo')->default(true)->index()->comment('Record attivo');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Utente che ha creato');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Utente che ha modificato');
            $table->softDeletes()->comment('Eliminazione logica per audit');
            $table->timestamps();

            // Indici composti per performance
            $table->index(['attivo', 'utilizzabile_ddt']);
            $table->index(['attivo', 'utilizzabile_fatture']);
            $table->index(['tipo_confezionamento', 'attivo']);
            
            // Foreign key per audit trail
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspetto_beni');
    }
};
