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
        Schema::table('size_types', function (Blueprint $table) {
            // Campo category per classificare i tipi di taglie
            $table->string('category', 50)->nullable()->after('description')
                  ->comment('Categoria: clothing,shoes,children,accessories,underwear,sportswear,formal,casual');
            
            // Campo measurement_unit per specificare l'unità di misura
            $table->string('measurement_unit', 20)->nullable()->after('category')
                  ->comment('Unità di misura: cm,inches,mixed,numeric,letter');
            
            // Indici per performance
            $table->index('category');
            $table->index('measurement_unit');
            $table->index(['category', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('size_types', function (Blueprint $table) {
            $table->dropIndex(['category', 'active']);
            $table->dropIndex(['measurement_unit']);
            $table->dropIndex(['category']);
            $table->dropColumn(['category', 'measurement_unit']);
        });
    }
};