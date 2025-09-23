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
            // Rimuove tutti i campi enterprise non necessari
            $table->dropColumn([
                'electronic_invoice_code',
                'type', 
                'default_due_days',
                'active',
                'sort_order',
                'deleted_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Ripristina i campi enterprise se necessario
            $table->string('electronic_invoice_code', 4)->nullable();
            $table->enum('type', ['immediate', 'deferred', 'installment'])->default('immediate');
            $table->integer('default_due_days')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('deleted_at')->nullable();
        });
    }
};