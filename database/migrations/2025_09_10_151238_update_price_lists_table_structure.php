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
        Schema::table('price_lists', function (Blueprint $table) {
            // Rimuove il campo 'name' se esiste (era nella struttura base)
            if (Schema::hasColumn('price_lists', 'name')) {
                $table->dropColumn('name');
            }
            
            // Aggiunge i nuovi campi per listini
            $table->decimal('discount_percentage', 8, 2)->default(0)->after('description');
            $table->date('valid_from')->after('discount_percentage');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->boolean('is_default')->default(false)->after('valid_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_lists', function (Blueprint $table) {
            // Rimuove i campi aggiunti
            $table->dropColumn([
                'discount_percentage', 'valid_from', 'valid_to', 'is_default'
            ]);
            
            // Ripristina il campo 'name' se necessario  
            $table->string('name', 255)->after('code');
        });
    }
};
