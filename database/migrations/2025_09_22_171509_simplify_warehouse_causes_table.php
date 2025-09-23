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
            // Rimuoviamo campi complessi non necessari per un sistema semplice
            $table->dropColumn([
                'name', // Usiamo solo description
                'affects_cost',
                'requires_document', 
                'auto_calculate_cost',
                'fiscal_relevant',
                'approval_required',
                'notify_threshold',
                'color_hex',
                'icon',
                'default_location',
                'auto_assign_lot',
                'usage_count',
                'last_used_at',
                'created_by',
                'updated_by',
                'metadata',
                'compliance_notes'
            ]);
            
            // Semplifichiamo movement_type
            $table->enum('movement_type', ['CARICO', 'SCARICO', 'TRASFERIMENTO', 'RETTIFICA'])->change();
            
            // Semplifichiamo category - manteniamo solo le principali
            $table->enum('category', ['VENDITA', 'ACQUISTO', 'RESO', 'TRASFERIMENTO', 'INVENTARIO', 'ALTRO'])->change();
            
            // Semplifichiamo priority_level 
            $table->enum('priority_level', ['NORMALE', 'ALTA'])->default('NORMALE')->change();
            
            // Aggiungiamo campo per il conto contabile
            $table->string('account_code', 20)->nullable()->after('fiscal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_causes', function (Blueprint $table) {
            // Ripristina i campi rimossi
            $table->string('name', 255)->nullable();
            $table->boolean('affects_cost')->default(false);
            $table->boolean('requires_document')->default(false);
            $table->boolean('auto_calculate_cost')->default(false);
            $table->boolean('fiscal_relevant')->default(false);
            $table->boolean('approval_required')->default(false);
            $table->decimal('notify_threshold', 8, 2)->nullable();
            $table->string('color_hex', 7)->default('#007bff');
            $table->string('icon', 50)->default('box');
            $table->string('default_location', 100)->nullable();
            $table->boolean('auto_assign_lot')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('compliance_notes', 500)->nullable();
            
            // Ripristina enum originali
            $table->enum('movement_type', ['in', 'out', 'adjustment'])->change();
            $table->enum('category', ['ORDINARY', 'INVENTORY', 'PRODUCTION', 'LOSS', 'TRANSFER', 'RETURN', 'SAMPLE'])->change();
            $table->enum('priority_level', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->change();
            
            $table->dropColumn('account_code');
        });
    }
};
