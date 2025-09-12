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
        Schema::table('payment_methods', function (Blueprint $table) {
            // Rimuove il campo 'name' se esiste (era nella struttura base)
            if (Schema::hasColumn('payment_methods', 'name')) {
                $table->dropColumn('name');
            }
            
            // Aggiunge solo i campi mancanti
            if (!Schema::hasColumn('payment_methods', 'electronic_invoice_code')) {
                $table->string('electronic_invoice_code', 4)->nullable()->after('description');
            }
            
            // Modifica il campo type esistente da varchar a enum
            $table->dropColumn('type');
            $table->enum('type', ['immediate', 'deferred', 'installment'])->default('immediate')->after('electronic_invoice_code');
            
            if (!Schema::hasColumn('payment_methods', 'default_due_days')) {
                $table->integer('default_due_days')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('payment_methods', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('default_due_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Rimuove i campi aggiunti
            $table->dropColumn([
                'electronic_invoice_code', 'type', 'default_due_days', 'sort_order'
            ]);
            
            // Ripristina il campo 'name' se necessario
            $table->string('name', 255)->after('code');
        });
    }
};
