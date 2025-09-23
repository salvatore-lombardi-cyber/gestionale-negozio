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
        Schema::table('fixed_price_denominations', function (Blueprint $table) {
            // Rimuovi tutti i campi extra per semplificare secondo specifica
            $table->dropColumn([
                'code',
                'name', 
                'active',
                'sort_order',
                'deleted_at'
            ]);
            
            // Modifica description per essere string con unique invece di text
            $table->string('description')->unique()->change();
            
            // Aggiungi campo comment
            $table->text('comment')->nullable()->after('description');
            
            // Aggiungi indice per performance
            $table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_price_denominations', function (Blueprint $table) {
            // Ripristina i campi se necessario per rollback
            $table->string('code', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('deleted_at')->nullable();
            
            // Rimuovi campo comment
            $table->dropColumn('comment');
            
            // Rimuovi indice
            $table->dropIndex(['description']);
            
            // Ripristina description come text
            $table->text('description')->change();
        });
    }
};
