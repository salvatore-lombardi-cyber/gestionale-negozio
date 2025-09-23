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
        Schema::table('supplier_categories', function (Blueprint $table) {
            // Rimuoviamo tutti i campi assurdi che non servono
            $table->dropColumn([
                'category_type',
                'sector',
                'reliability_rating',
                'quality_rating',
                'performance_rating',
                'payment_terms_days',
                'discount_expected',
                'minimum_order_value',
                'approval_required',
                'preferred_contact_method',
                'lead_time_days',
                'security_clearance_level',
                'requires_nda',
                'audit_frequency_months',
                'color_hex',
                'icon',
                'contract_template',
                'auto_renewal',
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
        Schema::table('supplier_categories', function (Blueprint $table) {
            // Ripristina i campi rimossi (per rollback)
            $table->enum('category_type', ['STRATEGIC','PREFERRED','TRANSACTIONAL','PANEL','ON_HOLD'])->default('TRANSACTIONAL');
            $table->string('sector', 100)->nullable();
            $table->integer('reliability_rating')->default(5);
            $table->integer('quality_rating')->default(5);
            $table->integer('performance_rating')->default(5);
            $table->integer('payment_terms_days')->default(30);
            $table->decimal('discount_expected', 5, 2)->default(0);
            $table->decimal('minimum_order_value', 12, 2)->nullable();
            $table->boolean('approval_required')->default(false);
            $table->enum('preferred_contact_method', ['EMAIL','PHONE','PORTAL','EDI'])->default('EMAIL');
            $table->integer('lead_time_days')->nullable();
            $table->enum('security_clearance_level', ['LOW','MEDIUM','HIGH','CRITICAL'])->default('LOW');
            $table->boolean('requires_nda')->default(false);
            $table->integer('audit_frequency_months')->nullable();
            $table->string('color_hex', 7)->default('#029D7E');
            $table->string('icon', 50)->default('bi-truck');
            $table->string('contract_template', 100)->nullable();
            $table->boolean('auto_renewal')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('notes', 500)->nullable();
        });
    }
};
