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
            // Classificazione strategica basata su ricerca
            if (!Schema::hasColumn('supplier_categories', 'category_type')) {
                $table->enum('category_type', ['STRATEGIC', 'PREFERRED', 'TRANSACTIONAL', 'PANEL', 'ON_HOLD'])->default('TRANSACTIONAL')->comment('Tipo categoria strategica')->after('description');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'sector')) {
                $table->string('sector', 100)->nullable()->comment('Settore di business')->after('category_type');
            }
            
            // Rating e valutazioni
            if (!Schema::hasColumn('supplier_categories', 'reliability_rating')) {
                $table->integer('reliability_rating')->default(3)->comment('Rating affidabilità (1-5)')->after('sector');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'quality_rating')) {
                $table->integer('quality_rating')->default(3)->comment('Rating qualità (1-5)')->after('reliability_rating');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'performance_rating')) {
                $table->integer('performance_rating')->default(3)->comment('Rating performance generale (1-5)')->after('quality_rating');
            }
            
            // Termini commerciali
            if (!Schema::hasColumn('supplier_categories', 'payment_terms_days')) {
                $table->integer('payment_terms_days')->default(30)->comment('Giorni termini pagamento standard')->after('performance_rating');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'discount_expected')) {
                $table->decimal('discount_expected', 5, 2)->default(0.00)->comment('Sconto atteso %')->after('payment_terms_days');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'minimum_order_value')) {
                $table->decimal('minimum_order_value', 12, 2)->nullable()->comment('Valore minimo ordine')->after('discount_expected');
            }
            
            // Configurazione procurement
            if (!Schema::hasColumn('supplier_categories', 'approval_required')) {
                $table->boolean('approval_required')->default(false)->comment('Richiede approvazione ordini')->after('minimum_order_value');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'preferred_contact_method')) {
                $table->enum('preferred_contact_method', ['EMAIL', 'PHONE', 'PORTAL', 'EDI'])->default('EMAIL')->comment('Metodo contatto preferito')->after('approval_required');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'lead_time_days')) {
                $table->integer('lead_time_days')->nullable()->comment('Lead time medio in giorni')->after('preferred_contact_method');
            }
            
            // Sicurezza OWASP
            if (!Schema::hasColumn('supplier_categories', 'security_clearance_level')) {
                $table->enum('security_clearance_level', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('MEDIUM')->comment('Livello clearance sicurezza')->after('lead_time_days');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'requires_nda')) {
                $table->boolean('requires_nda')->default(false)->comment('Richiede accordo NDA')->after('security_clearance_level');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'audit_frequency_months')) {
                $table->integer('audit_frequency_months')->nullable()->comment('Frequenza audit in mesi')->after('requires_nda');
            }
            
            // Personalizzazione UI
            if (!Schema::hasColumn('supplier_categories', 'color_hex')) {
                $table->string('color_hex', 7)->default('#029D7E')->comment('Colore identificativo categoria')->after('audit_frequency_months');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'icon')) {
                $table->string('icon', 50)->default('bi-building')->comment('Icona Bootstrap categoria')->after('color_hex');
            }
            
            // Configurazione contratti
            if (!Schema::hasColumn('supplier_categories', 'contract_template')) {
                $table->string('contract_template', 100)->nullable()->comment('Template contratto predefinito')->after('icon');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'auto_renewal')) {
                $table->boolean('auto_renewal')->default(false)->comment('Rinnovo automatico contratti')->after('contract_template');
            }
            
            // Audit trail
            if (!Schema::hasColumn('supplier_categories', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->comment('Data attivazione')->after('active');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->comment('Data disattivazione')->after('activated_at');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'metadata')) {
                $table->json('metadata')->nullable()->comment('Dati aggiuntivi JSON')->after('deactivated_at');
            }
            
            if (!Schema::hasColumn('supplier_categories', 'notes')) {
                $table->string('notes', 500)->nullable()->comment('Note interne categoria')->after('metadata');
            }
        });
        
        // Aggiungi indici per performance
        Schema::table('supplier_categories', function (Blueprint $table) {
            if (Schema::hasColumn('supplier_categories', 'category_type') && Schema::hasColumn('supplier_categories', 'active')) {
                $table->index(['category_type', 'active']);
            }
            
            if (Schema::hasColumn('supplier_categories', 'sector')) {
                $table->index('sector');
            }
            
            if (Schema::hasColumn('supplier_categories', 'reliability_rating')) {
                $table->index('reliability_rating');
            }
            
            if (Schema::hasColumn('supplier_categories', 'security_clearance_level')) {
                $table->index('security_clearance_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Prima rimuovi gli indici
        Schema::table('supplier_categories', function (Blueprint $table) {
            if (Schema::hasColumn('supplier_categories', 'category_type') && Schema::hasColumn('supplier_categories', 'active')) {
                $table->dropIndex(['category_type', 'active']);
            }
            
            if (Schema::hasColumn('supplier_categories', 'sector')) {
                $table->dropIndex(['supplier_categories_sector_index']);
            }
            
            if (Schema::hasColumn('supplier_categories', 'reliability_rating')) {
                $table->dropIndex(['supplier_categories_reliability_rating_index']);
            }
            
            if (Schema::hasColumn('supplier_categories', 'security_clearance_level')) {
                $table->dropIndex(['supplier_categories_security_clearance_level_index']);
            }
        });
        
        // Poi rimuovi le colonne
        Schema::table('supplier_categories', function (Blueprint $table) {
            $columnsToRemove = [
                'category_type', 'sector', 'reliability_rating', 'quality_rating', 'performance_rating',
                'payment_terms_days', 'discount_expected', 'minimum_order_value', 'approval_required',
                'preferred_contact_method', 'lead_time_days', 'security_clearance_level', 'requires_nda',
                'audit_frequency_months', 'color_hex', 'icon', 'contract_template', 'auto_renewal',
                'activated_at', 'deactivated_at', 'metadata', 'notes'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('supplier_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};