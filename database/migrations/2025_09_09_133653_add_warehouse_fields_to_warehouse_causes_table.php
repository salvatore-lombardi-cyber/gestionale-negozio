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
        Schema::table('warehouse_causes', function (Blueprint $table) {
            // Tipo movimento: entrata, uscita, rettifica
            if (!Schema::hasColumn('warehouse_causes', 'movement_type')) {
                $table->enum('movement_type', ['in', 'out', 'adjustment'])->after('description')->comment('Tipo movimento: entrata/uscita/rettifica');
            }
            
            // Controlli costo e impatto fiscale
            if (!Schema::hasColumn('warehouse_causes', 'affects_cost')) {
                $table->boolean('affects_cost')->default(true)->after('movement_type')->comment('Se il movimento influenza il costo del prodotto');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'requires_document')) {
                $table->boolean('requires_document')->default(false)->after('affects_cost')->comment('Se richiede documento fiscale allegato');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'auto_calculate_cost')) {
                $table->boolean('auto_calculate_cost')->default(false)->after('requires_document')->comment('Se calcola automaticamente il costo');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'fiscal_relevant')) {
                $table->boolean('fiscal_relevant')->default(false)->after('auto_calculate_cost')->comment('Se è rilevante fiscalmente');
            }
            
            // Campi enterprise per gestionale italiano
            if (!Schema::hasColumn('warehouse_causes', 'fiscal_code')) {
                $table->string('fiscal_code', 10)->nullable()->after('fiscal_relevant')->comment('Codice fiscale per dichiarazioni');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'category')) {
                $table->enum('category', ['ORDINARY', 'INVENTORY', 'PRODUCTION', 'LOSS', 'TRANSFER', 'RETURN', 'SAMPLE'])->default('ORDINARY')->after('fiscal_code')->comment('Categoria causale');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'priority_level')) {
                $table->enum('priority_level', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('MEDIUM')->after('category')->comment('Livello priorità movimento');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'approval_required')) {
                $table->boolean('approval_required')->default(false)->after('priority_level')->comment('Richiede approvazione manuale');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'notify_threshold')) {
                $table->decimal('notify_threshold', 8, 2)->nullable()->after('approval_required')->comment('Soglia importo per notifiche automatiche');
            }
            
            // Configurazione business
            if (!Schema::hasColumn('warehouse_causes', 'color_hex')) {
                $table->string('color_hex', 7)->default('#029D7E')->after('notify_threshold')->comment('Colore identificativo causale');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'icon')) {
                $table->string('icon', 50)->default('bi-box-seam')->after('color_hex')->comment('Icona Bootstrap identificativa');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'default_location')) {
                $table->string('default_location', 100)->nullable()->after('icon')->comment('Ubicazione predefinita per questa causale');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'auto_assign_lot')) {
                $table->boolean('auto_assign_lot')->default(false)->after('default_location')->comment('Assegna automaticamente lotto/serial number');
            }
            
            // Audit e compliance OWASP
            if (!Schema::hasColumn('warehouse_causes', 'usage_count')) {
                $table->integer('usage_count')->default(0)->after('auto_assign_lot')->comment('Contatore utilizzi causale');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('usage_count')->comment('Ultima volta utilizzata');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('last_used_at')->comment('ID utente creatore');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by')->comment('ID ultimo modificatore');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'metadata')) {
                $table->json('metadata')->nullable()->after('updated_by')->comment('Dati aggiuntivi JSON per estensioni future');
            }
            
            if (!Schema::hasColumn('warehouse_causes', 'compliance_notes')) {
                $table->string('compliance_notes', 500)->nullable()->after('metadata')->comment('Note conformità e normative');
            }
        });
        
        // Aggiungi indici per performance
        Schema::table('warehouse_causes', function (Blueprint $table) {
            if (Schema::hasColumn('warehouse_causes', 'movement_type') && Schema::hasColumn('warehouse_causes', 'active')) {
                $table->index(['movement_type', 'active'], 'idx_movement_active');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'category')) {
                $table->index('category', 'idx_category');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'fiscal_relevant')) {
                $table->index('fiscal_relevant', 'idx_fiscal_relevant');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'priority_level')) {
                $table->index('priority_level', 'idx_priority_level');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'approval_required')) {
                $table->index('approval_required', 'idx_approval_required');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Prima rimuovi gli indici
        Schema::table('warehouse_causes', function (Blueprint $table) {
            if (Schema::hasColumn('warehouse_causes', 'movement_type') && Schema::hasColumn('warehouse_causes', 'active')) {
                $table->dropIndex('idx_movement_active');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'category')) {
                $table->dropIndex('idx_category');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'fiscal_relevant')) {
                $table->dropIndex('idx_fiscal_relevant');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'priority_level')) {
                $table->dropIndex('idx_priority_level');
            }
            
            if (Schema::hasColumn('warehouse_causes', 'approval_required')) {
                $table->dropIndex('idx_approval_required');
            }
        });
        
        // Poi rimuovi le colonne
        Schema::table('warehouse_causes', function (Blueprint $table) {
            $columnsToRemove = [
                'movement_type', 'affects_cost', 'requires_document', 'auto_calculate_cost', 'fiscal_relevant',
                'fiscal_code', 'category', 'priority_level', 'approval_required', 'notify_threshold',
                'color_hex', 'icon', 'default_location', 'auto_assign_lot', 'usage_count', 'last_used_at',
                'created_by', 'updated_by', 'metadata', 'compliance_notes'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('warehouse_causes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};