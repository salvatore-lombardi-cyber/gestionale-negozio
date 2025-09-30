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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            
            // Dati aziendali base
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('IT');
            
            // Configurazioni e limiti
            $table->json('settings')->nullable()->comment('Impostazioni specifiche azienda');
            $table->json('limits')->nullable()->comment('Limiti: {"max_users": 10, "max_invoices_per_month": 100}');
            $table->string('plan')->default('basic')->comment('Piano: basic, premium, enterprise');
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            
            $table->timestamps();
            
            // Indici
            $table->index(['is_active', 'plan']);
            $table->index('trial_ends_at');
            $table->index('subscription_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};