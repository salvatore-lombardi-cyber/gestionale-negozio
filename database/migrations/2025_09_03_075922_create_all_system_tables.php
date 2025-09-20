<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lista delle tabelle di sistema da creare
        $tables = [
            'vat_nature_associations',
            'vat_natures',
            'good_appearances',
            'banks',
            'product_categories',
            'customer_categories',
            'supplier_categories',
            'size_colors',
            'warehouse_causes',
            'color_variants',
            'conditions',
            'fixed_price_denominations',
            'deposits',
            'price_lists',
            'shipping_terms',
            'merchandising_sectors',
            'size_variants',
            'size_types',
            'payment_types',
            'transports',
            'transport_carriers',
            'locations',
            'unit_of_measures',
            'zones',
            'currencies',
            'tax_rates',
            'payment_methods'
        ];
        
        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->string('code', 50)->unique();
                    $table->string('name');
                    $table->text('description')->nullable();
                    $table->boolean('active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                    $table->softDeletes();
                    
                    $table->index(['active', 'sort_order']);
                    $table->index('code');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'vat_nature_associations',
            'vat_natures', 
            'good_appearances',
            'banks',
            'product_categories',
            'customer_categories',
            'supplier_categories',
            'size_colors',
            'warehouse_causes',
            'color_variants',
            'conditions',
            'fixed_price_denominations',
            'deposits',
            'price_lists',
            'shipping_terms',
            'merchandising_sectors',
            'size_variants',
            'size_types',
            'payment_types',
            'transports',
            'transport_carriers',
            'locations',
            'unit_of_measures',
            'zones',
            'currencies',
            'tax_rates',
            'payment_methods'
        ];
        
        foreach ($tables as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};