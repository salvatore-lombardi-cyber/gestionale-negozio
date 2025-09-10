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
            // Aggiungi campi enterprise che non esistono già (basandomi sulla struttura esistente)
            
            if (!Schema::hasColumn('customer_categories', 'type')) {
                $table->enum('type', ['B2B', 'B2C', 'WHOLESALE', 'RETAIL', 'VIP', 'STANDARD'])->default('STANDARD')->comment('Tipo classificazione cliente')->after('description');
            }
            
            if (!Schema::hasColumn('customer_categories', 'price_list')) {
                $table->enum('price_list', ['LIST_1', 'LIST_2', 'LIST_3', 'WHOLESALE', 'RETAIL'])->default('LIST_1')->comment('Lista prezzi assegnata')->after('payment_terms_days');
            }
            
            if (!Schema::hasColumn('customer_categories', 'show_wholesale_prices')) {
                $table->boolean('show_wholesale_prices')->default(false)->comment('Visualizza prezzi ingrosso')->after('price_list');
            }
            
            if (!Schema::hasColumn('customer_categories', 'icon')) {
                $table->string('icon', 50)->default('bi-person')->comment('Icona Bootstrap categoria')->after('color_hex');
            }
            
            if (!Schema::hasColumn('customer_categories', 'receive_promotions')) {
                $table->boolean('receive_promotions')->default(true)->comment('Ricevi promozioni via email')->after('icon');
            }
            
            if (!Schema::hasColumn('customer_categories', 'priority_support')) {
                $table->boolean('priority_support')->default(false)->comment('Supporto prioritario')->after('receive_promotions');
            }
            
            if (!Schema::hasColumn('customer_categories', 'require_approval')) {
                $table->boolean('require_approval')->default(false)->comment('Richiede approvazione ordini')->after('priority_support');
            }
            
            if (!Schema::hasColumn('customer_categories', 'max_orders_per_day')) {
                $table->integer('max_orders_per_day')->nullable()->comment('Limite ordini giornalieri')->after('require_approval');
            }
            
            if (!Schema::hasColumn('customer_categories', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->comment('Data attivazione')->after('active');
            }
            
            if (!Schema::hasColumn('customer_categories', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->comment('Data disattivazione')->after('activated_at');
            }
            
            if (!Schema::hasColumn('customer_categories', 'metadata')) {
                $table->json('metadata')->nullable()->comment('Dati aggiuntivi JSON')->after('deactivated_at');
            }
            
            if (!Schema::hasColumn('customer_categories', 'notes')) {
                $table->string('notes', 500)->nullable()->comment('Note interne categoria')->after('metadata');
            }
        });
        
        // Aggiungi indici per performance (dopo aver aggiunto le colonne)
        Schema::table('customer_categories', function (Blueprint $table) {
            if (Schema::hasColumn('customer_categories', 'type') && Schema::hasColumn('customer_categories', 'active')) {
                $table->index(['type', 'active']);
            }
            
            if (Schema::hasColumn('customer_categories', 'priority_level') && Schema::hasColumn('customer_categories', 'active')) {
                $table->index(['priority_level', 'active']);
            }
            
            if (Schema::hasColumn('customer_categories', 'discount_percentage')) {
                $table->index('discount_percentage');
            }
            
            if (Schema::hasColumn('customer_categories', 'credit_limit')) {
                $table->index('credit_limit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Prima rimuovi gli indici
        Schema::table('customer_categories', function (Blueprint $table) {
            if (Schema::hasColumn('customer_categories', 'type') && Schema::hasColumn('customer_categories', 'active')) {
                $table->dropIndex(['type', 'active']);
            }
            
            if (Schema::hasColumn('customer_categories', 'priority_level') && Schema::hasColumn('customer_categories', 'active')) {
                $table->dropIndex(['priority_level', 'active']);
            }
            
            if (Schema::hasColumn('customer_categories', 'discount_percentage')) {
                $table->dropIndex(['customer_categories_discount_percentage_index']);
            }
            
            if (Schema::hasColumn('customer_categories', 'credit_limit')) {
                $table->dropIndex(['customer_categories_credit_limit_index']);
            }
        });
        
        // Poi rimuovi le colonne
        Schema::table('customer_categories', function (Blueprint $table) {
            $columnsToRemove = [
                'type', 'price_list', 'show_wholesale_prices', 'icon', 
                'receive_promotions', 'priority_support', 'require_approval',
                'max_orders_per_day', 'activated_at', 'deactivated_at', 'metadata', 'notes'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('customer_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};