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
        Schema::table('color_variants', function (Blueprint $table) {
            // Colori primari e secondari per gradienti
            if (!Schema::hasColumn('color_variants', 'hex_primary')) {
                $table->string('hex_primary', 7)->after('description')->comment('Colore primario in formato hex');
            }
            
            if (!Schema::hasColumn('color_variants', 'hex_secondary')) {
                $table->string('hex_secondary', 7)->nullable()->after('hex_primary')->comment('Colore secondario per gradienti');
            }
            
            // Codici colore professionali
            if (!Schema::hasColumn('color_variants', 'pantone_code')) {
                $table->string('pantone_code', 20)->nullable()->after('hex_secondary')->comment('Codice Pantone per stampa professionale');
            }
            
            if (!Schema::hasColumn('color_variants', 'ral_code')) {
                $table->string('ral_code', 20)->nullable()->after('pantone_code')->comment('Codice RAL per vernici');
            }
            
            // Valori colore per design professionale
            if (!Schema::hasColumn('color_variants', 'rgb_values')) {
                $table->json('rgb_values')->nullable()->after('ral_code')->comment('Valori RGB {r: 255, g: 0, b: 0}');
            }
            
            if (!Schema::hasColumn('color_variants', 'cmyk_values')) {
                $table->json('cmyk_values')->nullable()->after('rgb_values')->comment('Valori CMYK per stampa {c: 0, m: 100, y: 100, k: 0}');
            }
            
            if (!Schema::hasColumn('color_variants', 'hsl_values')) {
                $table->json('hsl_values')->nullable()->after('cmyk_values')->comment('Valori HSL {h: 360, s: 100, l: 50}');
            }
            
            // Gestione stagioni fashion
            if (!Schema::hasColumn('color_variants', 'season')) {
                $table->enum('season', ['SPRING', 'SUMMER', 'AUTUMN', 'WINTER', 'ALL_SEASON'])->default('ALL_SEASON')->after('hsl_values')->comment('Stagione di tendenza');
            }
            
            if (!Schema::hasColumn('color_variants', 'year')) {
                $table->year('year')->default(date('Y'))->after('season')->comment('Anno di collezione');
            }
            
            // Trend e popolarità
            if (!Schema::hasColumn('color_variants', 'trend_level')) {
                $table->enum('trend_level', ['LOW', 'MEDIUM', 'HIGH', 'VIRAL', 'CLASSIC'])->default('MEDIUM')->after('year')->comment('Livello di tendenza');
            }
            
            if (!Schema::hasColumn('color_variants', 'popularity_score')) {
                $table->integer('popularity_score')->default(0)->after('trend_level')->comment('Punteggio popolarità 0-100');
            }
            
            // Proprietà fisiche colore
            if (!Schema::hasColumn('color_variants', 'finish_type')) {
                $table->enum('finish_type', ['MATTE', 'GLOSSY', 'SATIN', 'METALLIC', 'PEARL', 'NEON', 'TRANSPARENT'])->default('MATTE')->after('popularity_score')->comment('Tipo finitura');
            }
            
            if (!Schema::hasColumn('color_variants', 'opacity_level')) {
                $table->integer('opacity_level')->default(100)->after('finish_type')->comment('Livello opacità 0-100');
            }
            
            // Business logic
            if (!Schema::hasColumn('color_variants', 'price_modifier')) {
                $table->decimal('price_modifier', 8, 2)->default(0)->after('opacity_level')->comment('Modificatore prezzo per colore speciale');
            }
            
            if (!Schema::hasColumn('color_variants', 'minimum_quantity')) {
                $table->integer('minimum_quantity')->default(1)->after('price_modifier')->comment('Quantità minima ordinabile');
            }
            
            if (!Schema::hasColumn('color_variants', 'lead_time_days')) {
                $table->integer('lead_time_days')->default(0)->after('minimum_quantity')->comment('Giorni aggiuntivi per produzione');
            }
            
            // Metadati e classificazione
            if (!Schema::hasColumn('color_variants', 'color_family')) {
                $table->enum('color_family', ['RED', 'ORANGE', 'YELLOW', 'GREEN', 'BLUE', 'PURPLE', 'PINK', 'BROWN', 'BLACK', 'WHITE', 'GREY', 'MULTICOLOR'])->nullable()->after('lead_time_days')->comment('Famiglia colore principale');
            }
            
            if (!Schema::hasColumn('color_variants', 'temperature')) {
                $table->enum('temperature', ['WARM', 'COOL', 'NEUTRAL'])->nullable()->after('color_family')->comment('Temperatura colore');
            }
            
            if (!Schema::hasColumn('color_variants', 'brightness')) {
                $table->enum('brightness', ['VERY_DARK', 'DARK', 'MEDIUM', 'LIGHT', 'VERY_LIGHT'])->default('MEDIUM')->after('temperature')->comment('Luminosità colore');
            }
            
            // Compatibilità e abbinamenti
            if (!Schema::hasColumn('color_variants', 'compatible_colors')) {
                $table->json('compatible_colors')->nullable()->after('brightness')->comment('Array ID colori compatibili per abbinamenti');
            }
            
            if (!Schema::hasColumn('color_variants', 'complementary_color_id')) {
                $table->unsignedBigInteger('complementary_color_id')->nullable()->after('compatible_colors')->comment('Colore complementare');
            }
            
            // Audit e performance
            if (!Schema::hasColumn('color_variants', 'usage_count')) {
                $table->integer('usage_count')->default(0)->after('complementary_color_id')->comment('Contatore utilizzi nei prodotti');
            }
            
            if (!Schema::hasColumn('color_variants', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('usage_count')->comment('Ultimo utilizzo');
            }
            
            if (!Schema::hasColumn('color_variants', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('last_used_at')->comment('ID utente creatore');
            }
            
            if (!Schema::hasColumn('color_variants', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by')->comment('ID ultimo modificatore');
            }
            
            // Note e metadata
            if (!Schema::hasColumn('color_variants', 'designer_notes')) {
                $table->text('designer_notes')->nullable()->after('updated_by')->comment('Note del designer');
            }
            
            if (!Schema::hasColumn('color_variants', 'supplier_info')) {
                $table->json('supplier_info')->nullable()->after('designer_notes')->comment('Informazioni fornitore colore');
            }
            
            if (!Schema::hasColumn('color_variants', 'certifications')) {
                $table->json('certifications')->nullable()->after('supplier_info')->comment('Certificazioni colore (eco-friendly, etc.)');
            }
        });
        
        // Aggiungi indici per performance
        Schema::table('color_variants', function (Blueprint $table) {
            if (Schema::hasColumn('color_variants', 'season') && Schema::hasColumn('color_variants', 'active')) {
                $table->index(['season', 'active'], 'idx_season_active');
            }
            
            if (Schema::hasColumn('color_variants', 'trend_level')) {
                $table->index('trend_level', 'idx_trend_level');
            }
            
            if (Schema::hasColumn('color_variants', 'color_family')) {
                $table->index('color_family', 'idx_color_family');
            }
            
            if (Schema::hasColumn('color_variants', 'popularity_score')) {
                $table->index('popularity_score', 'idx_popularity_score');
            }
            
            if (Schema::hasColumn('color_variants', 'complementary_color_id')) {
                $table->foreign('complementary_color_id')->references('id')->on('color_variants')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Prima rimuovi indici e foreign keys
        Schema::table('color_variants', function (Blueprint $table) {
            if (Schema::hasColumn('color_variants', 'season') && Schema::hasColumn('color_variants', 'active')) {
                $table->dropIndex('idx_season_active');
            }
            
            if (Schema::hasColumn('color_variants', 'trend_level')) {
                $table->dropIndex('idx_trend_level');
            }
            
            if (Schema::hasColumn('color_variants', 'color_family')) {
                $table->dropIndex('idx_color_family');
            }
            
            if (Schema::hasColumn('color_variants', 'popularity_score')) {
                $table->dropIndex('idx_popularity_score');
            }
            
            if (Schema::hasColumn('color_variants', 'complementary_color_id')) {
                $table->dropForeign(['complementary_color_id']);
            }
        });
        
        // Poi rimuovi le colonne
        Schema::table('color_variants', function (Blueprint $table) {
            $columnsToRemove = [
                'hex_primary', 'hex_secondary', 'pantone_code', 'ral_code', 'rgb_values', 'cmyk_values', 'hsl_values',
                'season', 'year', 'trend_level', 'popularity_score', 'finish_type', 'opacity_level',
                'price_modifier', 'minimum_quantity', 'lead_time_days', 'color_family', 'temperature', 'brightness',
                'compatible_colors', 'complementary_color_id', 'usage_count', 'last_used_at', 'created_by', 'updated_by',
                'designer_notes', 'supplier_info', 'certifications'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('color_variants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};