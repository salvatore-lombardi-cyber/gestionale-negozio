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
        // Rimuove soft deletes dalle tabelle di configurazione
        
        // Taglie e Colori
        Schema::table('size_colors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        // Causali Magazzino
        Schema::table('warehouse_causes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        // Categorie Clienti (se ha soft deletes)
        if (Schema::hasColumn('customer_categories', 'deleted_at')) {
            Schema::table('customer_categories', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
        
        // Categorie Fornitori (se ha soft deletes)
        if (Schema::hasColumn('supplier_categories', 'deleted_at')) {
            Schema::table('supplier_categories', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ripristina soft deletes se necessario per rollback
        
        Schema::table('size_colors', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('warehouse_causes', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('customer_categories', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('supplier_categories', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
