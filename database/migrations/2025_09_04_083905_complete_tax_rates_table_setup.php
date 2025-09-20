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
        Schema::table('tax_rates', function (Blueprint $table) {
            // 1. Aggiungi campo name se non esiste
            if (!Schema::hasColumn('tax_rates', 'name')) {
                $table->string('name')->after('code');
            }
            
            // 2. Aggiungi UUID per audit trail (seguendo pattern VatNatureAssociation)
            if (!Schema::hasColumn('tax_rates', 'uuid')) {
                $table->string('uuid')->nullable()->after('id');
            }
            
            // 3. Aggiungi riferimento_normativo per i riferimenti normativi/legali
            if (!Schema::hasColumn('tax_rates', 'riferimento_normativo')) {
                $table->text('riferimento_normativo')->nullable()->after('description');
            }
            
            // 4. Aggiungi created_by per audit trail
            if (!Schema::hasColumn('tax_rates', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('active');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // 5. Aggiungi updated_by per completare l'audit trail
            if (!Schema::hasColumn('tax_rates', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // 6. Aggiungi campi standard se non esistenti
            if (!Schema::hasColumn('tax_rates', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('updated_by');
            }
            
            if (!Schema::hasColumn('tax_rates', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        
        // 7. Rinomina rate in percentuale per chiarezza (se esiste)
        if (Schema::hasColumn('tax_rates', 'rate') && !Schema::hasColumn('tax_rates', 'percentuale')) {
            Schema::table('tax_rates', function (Blueprint $table) {
                $table->renameColumn('rate', 'percentuale');
            });
        }
        
        // 8. Popola UUID per tutti i record esistenti con UUID univoci
        $records = \DB::table('tax_rates')->whereNull('uuid')->orWhere('uuid', '')->get();
        foreach ($records as $record) {
            \DB::table('tax_rates')
                ->where('id', $record->id)
                ->update(['uuid' => \Illuminate\Support\Str::uuid()->toString()]);
        }
        
        // 9. Aggiungi constraint unique su UUID se non esiste
        $uniqueIndexExists = collect(\DB::select('SHOW INDEX FROM tax_rates'))
            ->where('Key_name', 'tax_rates_uuid_unique')
            ->isNotEmpty();
            
        if (!$uniqueIndexExists) {
            Schema::table('tax_rates', function (Blueprint $table) {
                $table->unique('uuid');
            });
        }
        
        // 10. Aggiungi indici per performance se non esistono
        $existingIndexes = collect(\DB::select('SHOW INDEX FROM tax_rates'))
            ->pluck('Key_name')
            ->toArray();
            
        Schema::table('tax_rates', function (Blueprint $table) use ($existingIndexes) {
            if (!in_array('tax_rates_active_sort_order_index', $existingIndexes)) {
                $table->index(['active', 'sort_order']);
            }
            if (!in_array('tax_rates_code_index', $existingIndexes)) {
                $table->index('code');
            }
            if (!in_array('tax_rates_created_by_created_at_index', $existingIndexes)) {
                $table->index(['created_by', 'created_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            // Rimuovi indici
            $table->dropIndex(['created_by', 'created_at']);
            $table->dropIndex(['active', 'sort_order']);
            $table->dropIndex(['code']);
            
            // Rimuovi constraint unique su UUID
            $table->dropUnique(['uuid']);
            
            // Rimuovi foreign key constraints
            if (Schema::hasColumn('tax_rates', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            
            if (Schema::hasColumn('tax_rates', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
            
            // Rimuovi campi aggiunti
            if (Schema::hasColumn('tax_rates', 'name')) {
                $table->dropColumn('name');
            }
            
            if (Schema::hasColumn('tax_rates', 'uuid')) {
                $table->dropColumn('uuid');
            }
            
            if (Schema::hasColumn('tax_rates', 'riferimento_normativo')) {
                $table->dropColumn('riferimento_normativo');
            }
            
            if (Schema::hasColumn('tax_rates', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
            
            if (Schema::hasColumn('tax_rates', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            // Ripristina nome originale
            if (Schema::hasColumn('tax_rates', 'percentuale') && !Schema::hasColumn('tax_rates', 'rate')) {
                $table->renameColumn('percentuale', 'rate');
            }
        });
    }
};
