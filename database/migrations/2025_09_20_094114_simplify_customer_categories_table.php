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
        Schema::table('customer_categories', function (Blueprint $table) {
            // Rimuoviamo tutti i campi complessi che non servono
            $table->dropColumn([
                'type',
                'price_list', 
                'show_wholesale_prices',
                'icon',
                'receive_promotions',
                'priority_support',
                'require_approval',
                'max_orders_per_day',
                'activated_at',
                'deactivated_at',
                'metadata',
                'notes'
            ]);
            
            // Modifichiamo il campo code per essere più flessibile
            $table->string('code', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            // Ripristina i campi rimossi (per rollback)
            $table->enum('type', ['B2B','B2C','WHOLESALE','RETAIL','VIP','STANDARD'])->default('STANDARD');
            $table->enum('price_list', ['LIST_1','LIST_2','LIST_3','WHOLESALE','RETAIL'])->default('LIST_1');
            $table->boolean('show_wholesale_prices')->default(false);
            $table->string('icon', 50)->default('bi-person');
            $table->boolean('receive_promotions')->default(true);
            $table->boolean('priority_support')->default(false);
            $table->boolean('require_approval')->default(false);
            $table->integer('max_orders_per_day')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('notes', 500)->nullable();
        });
    }
};
