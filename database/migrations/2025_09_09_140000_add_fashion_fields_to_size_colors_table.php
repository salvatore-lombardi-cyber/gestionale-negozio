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
            // Tipo: distingue tra taglie e colori
            if (!Schema::hasColumn('size_colors', 'type')) {
                $table->enum('type', ['size', 'color'])->after('description')->comment('Tipo: taglia o colore');
            }
            
            // Campi specifici per COLORI
            if (!Schema::hasColumn('size_colors', 'hex_value')) {
                $table->string('hex_value', 7)->nullable()->after('type')->comment('Valore esadecimale per colori');
            }
            
            if (!Schema::hasColumn('size_colors', 'rgb_value')) {
                $table->string('rgb_value', 20)->nullable()->after('hex_value')->comment('Valore RGB per colori');
            }
            
            if (!Schema::hasColumn('size_colors', 'pantone_code')) {
                $table->string('pantone_code', 20)->nullable()->after('rgb_value')->comment('Codice Pantone per colori');
            }
            
            // Campi specifici per TAGLIE
            if (!Schema::hasColumn('size_colors', 'size_category')) {
                $table->enum('size_category', ['NUMERIC', 'LETTER', 'EU', 'US', 'UK', 'IT', 'FR', 'CUSTOM'])->nullable()->after('pantone_code')->comment('Categoria sistema taglie');
            }
            
            if (!Schema::hasColumn('size_colors', 'size_system')) {
                $table->string('size_system', 50)->nullable()->after('size_category')->comment('Sistema taglie (es: EU, US, UK)');
            }
            
            if (!Schema::hasColumn('size_colors', 'numeric_value')) {
                $table->decimal('numeric_value', 8, 2)->nullable()->after('size_system')->comment('Valore numerico per taglie');
            }
            
            // Mappatura internazionale taglie (basata su EN 13402)
            if (!Schema::hasColumn('size_colors', 'eu_size')) {
                $table->string('eu_size', 10)->nullable()->after('numeric_value')->comment('Taglia equivalente EU');
            }
            
            if (!Schema::hasColumn('size_colors', 'us_size')) {
                $table->string('us_size', 10)->nullable()->after('eu_size')->comment('Taglia equivalente US');
            }
            
            if (!Schema::hasColumn('size_colors', 'uk_size')) {
                $table->string('uk_size', 10)->nullable()->after('us_size')->comment('Taglia equivalente UK');
            }
            
            // Misurazioni fisiche per taglie
            if (!Schema::hasColumn('size_colors', 'chest_cm')) {
                $table->integer('chest_cm')->nullable()->after('uk_size')->comment('Circonferenza petto in cm');
            }
            
            if (!Schema::hasColumn('size_colors', 'waist_cm')) {
                $table->integer('waist_cm')->nullable()->after('chest_cm')->comment('Circonferenza vita in cm');
            }
            
            if (!Schema::hasColumn('size_colors', 'hip_cm')) {
                $table->integer('hip_cm')->nullable()->after('waist_cm')->comment('Circonferenza fianchi in cm');
            }
            
            // Configurazione business
            if (!Schema::hasColumn('size_colors', 'seasonal')) {
                $table->boolean('seasonal')->default(false)->after('hip_cm')->comment('Variante stagionale');
            }
            
            if (!Schema::hasColumn('size_colors', 'season_tags')) {
                $table->json('season_tags')->nullable()->after('seasonal')->comment('Tag stagioni (primavera, estate, etc)');
            }
            
            if (!Schema::hasColumn('size_colors', 'price_modifier')) {
                $table->decimal('price_modifier', 8, 2)->default(0)->after('season_tags')->comment('Modificatore prezzo per variante');
            }
            
            // Configurazione magazzino
            if (!Schema::hasColumn('size_colors', 'barcode_prefix')) {
                $table->string('barcode_prefix', 20)->nullable()->after('price_modifier')->comment('Prefisso barcode per variante');
            }
            
            if (!Schema::hasColumn('size_colors', 'sku_suffix')) {
                $table->string('sku_suffix', 10)->nullable()->after('barcode_prefix')->comment('Suffisso SKU per variante');
            }
            
            if (!Schema::hasColumn('size_colors', 'default_stock_level')) {
                $table->integer('default_stock_level')->default(0)->after('sku_suffix')->comment('Livello stock predefinito');
            }
            
            // Personalizzazione UI
            if (!Schema::hasColumn('size_colors', 'icon')) {
                $table->string('icon', 50)->nullable()->after('default_stock_level')->comment('Icona Bootstrap per variante');
            }
            
            if (!Schema::hasColumn('size_colors', 'css_class')) {
                $table->string('css_class', 100)->nullable()->after('icon')->comment('Classe CSS personalizzata');
            }
            
            // Controlli qualità e OWASP
            if (!Schema::hasColumn('size_colors', 'requires_approval')) {
                $table->boolean('requires_approval')->default(false)->after('css_class')->comment('Richiede approvazione per ordini');
            }
            
            if (!Schema::hasColumn('size_colors', 'restricted_markets')) {
                $table->json('restricted_markets')->nullable()->after('requires_approval')->comment('Mercati con restrizioni');
            }
            
            if (!Schema::hasColumn('size_colors', 'compliance_notes')) {
                $table->string('compliance_notes', 500)->nullable()->after('restricted_markets')->comment('Note conformità e compliance');
            }
            
            // Metadati e audit
            if (!Schema::hasColumn('size_colors', 'metadata')) {
                $table->json('metadata')->nullable()->after('compliance_notes')->comment('Dati aggiuntivi JSON');
            }
            
            if (!Schema::hasColumn('size_colors', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('metadata')->comment('Ultima volta utilizzata');
            }
            
            if (!Schema::hasColumn('size_colors', 'usage_count')) {
                $table->integer('usage_count')->default(0)->after('last_used_at')->comment('Contatore utilizzi');
            }
        });
        
        // Aggiungi indici per performance
        Schema::table('size_colors', function (Blueprint $table) {
            if (Schema::hasColumn('size_colors', 'type') && Schema::hasColumn('size_colors', 'active')) {
                $table->index(['type', 'active']);
            }
            
            if (Schema::hasColumn('size_colors', 'size_category')) {
                $table->index('size_category');
            }
            
            if (Schema::hasColumn('size_colors', 'hex_value')) {
                $table->index('hex_value');
            }
            
            if (Schema::hasColumn('size_colors', 'seasonal')) {
                $table->index('seasonal');
            }
            
            if (Schema::hasColumn('size_colors', 'sort_order')) {
                $table->index('sort_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Prima rimuovi gli indici
        Schema::table('size_colors', function (Blueprint $table) {
            if (Schema::hasColumn('size_colors', 'type') && Schema::hasColumn('size_colors', 'active')) {
                $table->dropIndex(['type', 'active']);
            }
            
            if (Schema::hasColumn('size_colors', 'size_category')) {
                $table->dropIndex(['size_colors_size_category_index']);
            }
            
            if (Schema::hasColumn('size_colors', 'hex_value')) {
                $table->dropIndex(['size_colors_hex_value_index']);
            }
            
            if (Schema::hasColumn('size_colors', 'seasonal')) {
                $table->dropIndex(['size_colors_seasonal_index']);
            }
            
            if (Schema::hasColumn('size_colors', 'sort_order')) {
                $table->dropIndex(['size_colors_sort_order_index']);
            }
        });
        
        // Poi rimuovi le colonne
        Schema::table('size_colors', function (Blueprint $table) {
            $columnsToRemove = [
                'type', 'hex_value', 'rgb_value', 'pantone_code', 'size_category', 'size_system',
                'numeric_value', 'eu_size', 'us_size', 'uk_size', 'chest_cm', 'waist_cm', 'hip_cm',
                'seasonal', 'season_tags', 'price_modifier', 'barcode_prefix', 'sku_suffix',
                'default_stock_level', 'icon', 'css_class', 'requires_approval', 'restricted_markets',
                'compliance_notes', 'metadata', 'last_used_at', 'usage_count'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('size_colors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};