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
        Schema::table('size_colors', function (Blueprint $table) {
            // Rimuoviamo tutti i campi complessi che non servono per un sistema semplice
            $table->dropColumn([
                'hex_value',
                'rgb_value', 
                'pantone_code',
                'size_category',
                'size_system',
                'numeric_value',
                'eu_size',
                'us_size', 
                'uk_size',
                'chest_cm',
                'waist_cm',
                'hip_cm',
                'seasonal',
                'season_tags',
                'price_modifier',
                'barcode_prefix',
                'sku_suffix',
                'default_stock_level',
                'icon',
                'css_class',
                'requires_approval',
                'restricted_markets',
                'compliance_notes',
                'metadata',
                'last_used_at',
                'usage_count'
            ]);
            
            // Manteniamo il campo type più semplice
            $table->enum('type', ['TAGLIA', 'COLORE'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('size_colors', function (Blueprint $table) {
            // Ripristina i campi rimossi per rollback
            $table->string('hex_value', 7)->nullable();
            $table->string('rgb_value', 20)->nullable();
            $table->string('pantone_code', 20)->nullable();
            $table->enum('size_category', ['NUMERIC','LETTER','EU','US','UK','IT','FR','CUSTOM'])->nullable();
            $table->string('size_system', 50)->nullable();
            $table->decimal('numeric_value', 8, 2)->nullable();
            $table->string('eu_size', 10)->nullable();
            $table->string('us_size', 10)->nullable();
            $table->string('uk_size', 10)->nullable();
            $table->integer('chest_cm')->nullable();
            $table->integer('waist_cm')->nullable();
            $table->integer('hip_cm')->nullable();
            $table->boolean('seasonal')->default(false);
            $table->longText('season_tags')->nullable();
            $table->decimal('price_modifier', 8, 2)->default(0);
            $table->string('barcode_prefix', 20)->nullable();
            $table->string('sku_suffix', 10)->nullable();
            $table->integer('default_stock_level')->default(0);
            $table->string('icon', 50)->nullable();
            $table->string('css_class', 100)->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->longText('restricted_markets')->nullable();
            $table->string('compliance_notes', 500)->nullable();
            $table->longText('metadata')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);
            $table->enum('type', ['size','color'])->change();
        });
    }
};
