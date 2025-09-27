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
        Schema::table('tax_rates', function (Blueprint $table) {
            // Prima rimuove i foreign key constraints se esistono
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        
        Schema::table('tax_rates', function (Blueprint $table) {
            // Poi rimuove le colonne
            $table->dropColumn([
                'uuid', 
                'code', 
                'name', 
                'riferimento_normativo',
                'active', 
                'sort_order', 
                'created_by', 
                'updated_by',
                'deleted_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->string('uuid')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->text('riferimento_normativo')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->softDeletes();
        });
    }
};
