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
        // Prima rimuovi indici e foreign keys
        Schema::table('color_variants', function (Blueprint $table) {
            // Rimuovi foreign keys
            $table->dropForeign(['complementary_color_id']);
            
            // Rimuovi indici
            $table->dropIndex('idx_season_active');
            $table->dropIndex('idx_trend_level');
            $table->dropIndex('idx_color_family');
            $table->dropIndex('idx_popularity_score');
        });
        
        // Poi rimuovi le colonne
        Schema::table('color_variants', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'name', 
                'active',
                'sort_order',
                'deleted_at',
                'hex_primary',
                'hex_secondary', 
                'pantone_code',
                'ral_code',
                'rgb_values',
                'cmyk_values',
                'hsl_values',
                'season',
                'year',
                'trend_level',
                'popularity_score',
                'finish_type',
                'opacity_level',
                'price_modifier',
                'minimum_quantity',
                'lead_time_days',
                'color_family',
                'temperature',
                'brightness',
                'compatible_colors',
                'complementary_color_id',
                'usage_count',
                'last_used_at',
                'created_by',
                'updated_by',
                'designer_notes',
                'supplier_info',
                'certifications'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('color_variants', function (Blueprint $table) {
            // Ripristina i campi se necessario per rollback
            $table->string('code', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->string('hex_primary', 7)->nullable();
            $table->string('hex_secondary', 7)->nullable();
            $table->string('pantone_code', 20)->nullable();
            $table->string('ral_code', 20)->nullable();
            $table->json('rgb_values')->nullable();
            $table->json('cmyk_values')->nullable();
            $table->json('hsl_values')->nullable();
            $table->enum('season', ['SPRING', 'SUMMER', 'AUTUMN', 'WINTER', 'ALL_SEASON'])->default('ALL_SEASON');
            $table->year('year')->default(date('Y'));
            $table->enum('trend_level', ['LOW', 'MEDIUM', 'HIGH', 'VIRAL', 'CLASSIC'])->default('MEDIUM');
            $table->integer('popularity_score')->default(0);
            $table->enum('finish_type', ['MATTE', 'GLOSSY', 'SATIN', 'METALLIC', 'PEARL', 'NEON', 'TRANSPARENT'])->default('MATTE');
            $table->integer('opacity_level')->default(100);
            $table->decimal('price_modifier', 8, 2)->default(0);
            $table->integer('minimum_quantity')->default(1);
            $table->integer('lead_time_days')->default(0);
            $table->enum('color_family', ['RED', 'ORANGE', 'YELLOW', 'GREEN', 'BLUE', 'PURPLE', 'PINK', 'BROWN', 'BLACK', 'WHITE', 'GREY', 'MULTICOLOR'])->nullable();
            $table->enum('temperature', ['WARM', 'COOL', 'NEUTRAL'])->nullable();
            $table->enum('brightness', ['VERY_DARK', 'DARK', 'MEDIUM', 'LIGHT', 'VERY_LIGHT'])->default('MEDIUM');
            $table->json('compatible_colors')->nullable();
            $table->unsignedBigInteger('complementary_color_id')->nullable();
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('designer_notes')->nullable();
            $table->json('supplier_info')->nullable();
            $table->json('certifications')->nullable();
        });
    }
};
