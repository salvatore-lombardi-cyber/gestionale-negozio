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
        Schema::table('warehouse_causes', function (Blueprint $table) {
            // Rimuovi le colonne extra per semplificare secondo specifica originale
            $table->dropColumn([
                'movement_type',
                'fiscal_code', 
                'account_code',
                'category',
                'priority_level',
                'active'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_causes', function (Blueprint $table) {
            // Ripristina le colonne se necessario per rollback
            $table->enum('movement_type', ['CARICO', 'SCARICO', 'TRASFERIMENTO', 'RETTIFICA'])->nullable();
            $table->string('fiscal_code', 10)->nullable();
            $table->string('account_code', 20)->nullable();
            $table->enum('category', ['VENDITA', 'ACQUISTO', 'RESO', 'TRASFERIMENTO', 'INVENTARIO', 'ALTRO'])->nullable();
            $table->enum('priority_level', ['NORMALE', 'ALTA'])->default('NORMALE');
            $table->boolean('active')->default(true);
        });
    }
};
