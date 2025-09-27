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
        // Droppa e ricrea la tabella (sicura perché è vuota)
        Schema::dropIfExists('aspetto_beni');
        
        Schema::create('aspetto_beni', function (Blueprint $table) {
            $table->id();
            $table->text('description')->comment('Descrizione aspetto beni');
            $table->text('comment')->nullable()->comment('Commento aggiuntivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aspetto_beni', function (Blueprint $table) {
            // Ripristina colonne
            $table->string('codice_aspetto')->nullable();
            $table->string('descrizione')->nullable();
            $table->text('descrizione_estesa')->nullable();
            $table->enum('tipo_confezionamento', ['primario', 'secondario', 'terziario'])->nullable();
            $table->boolean('utilizzabile_ddt')->default(true);
            $table->boolean('utilizzabile_fatture')->default(true);
            $table->boolean('attivo')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->dropColumn(['description', 'comment']);
        });
    }
};
