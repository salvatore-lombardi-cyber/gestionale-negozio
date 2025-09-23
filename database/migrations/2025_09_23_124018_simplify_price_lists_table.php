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
            // Rimuovi tutti i campi extra per semplificare secondo specifica
            $table->dropColumn([
                'code',
                'valid_from',
                'valid_to',
                'is_default',
                'active',
                'sort_order',
                'deleted_at'
            ]);
            
            // Modifica description per essere string invece di text
            $table->string('description', 255)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_lists', function (Blueprint $table) {
            // Ripristina i campi se necessario per rollback
            $table->string('code', 50)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('deleted_at')->nullable();
            
            // Rimuovi unique constraint da description
            $table->dropUnique(['description']);
            
            // Ripristina description come text
            $table->text('description')->change();
        });
    }
};
