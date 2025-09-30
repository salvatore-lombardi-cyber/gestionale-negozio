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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Sistema permessi JSON flessibile
            $table->json('modules')->nullable()->comment('Permessi per moduli: {"magazzino": {"read": true, "write": false}}');
            $table->json('special_permissions')->nullable()->comment('Permessi speciali: {"can_delete_invoices": true}');
            $table->json('restrictions')->nullable()->comment('Restrizioni: {"max_invoice_amount": 1000}');
            
            // Metadati
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indici per performance
            $table->index(['user_id', 'is_active']);
            $table->index('valid_from');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};