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
        Schema::table('venditas', function (Blueprint $table) {
            $table->string('tipo_documento')->default('vendita')->after('id'); // vendita, fattura, ddt
            $table->integer('numero_documento')->nullable()->after('tipo_documento');
            $table->date('data_documento')->nullable()->after('numero_documento');
            $table->decimal('subtotale', 10, 2)->nullable()->after('totale');
            $table->decimal('iva', 10, 2)->nullable()->after('subtotale');
            $table->json('prodotti_vendita')->nullable()->after('iva'); // Dettagli prodotti per fatture
            $table->index(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venditas', function (Blueprint $table) {
            $table->dropIndex(['tipo_documento', 'numero_documento']);
            $table->dropColumn([
                'tipo_documento',
                'numero_documento', 
                'data_documento',
                'subtotale',
                'iva',
                'prodotti_vendita'
            ]);
        });
    }
};
