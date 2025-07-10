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
    Schema::create('dettaglio_venditas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendita_id')->constrained('venditas')->onDelete('cascade');
        $table->foreignId('prodotto_id')->constrained('prodottos')->onDelete('cascade');
        $table->string('taglia');
        $table->string('colore');
        $table->integer('quantita');
        $table->decimal('prezzo_unitario', 8, 2);
        $table->decimal('subtotale', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettaglio_venditas');
    }
};
