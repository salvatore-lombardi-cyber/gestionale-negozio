<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vat_nature_associations', function (Blueprint $table) {
            // Rimuovi campi non necessari per associazioni
            $table->dropColumn(['code', 'sort_order']);
            
            // Aggiungi campi specifici per associazioni Nature IVA
            $table->uuid('uuid')->unique()->after('id');
            $table->string('nome_associazione')->after('uuid');
            $table->string('descrizione', 500)->nullable()->after('nome_associazione');
            
            // Foreign keys per associazioni
            $table->unsignedBigInteger('tax_rate_id')->after('descrizione');
            $table->unsignedBigInteger('vat_nature_id')->after('tax_rate_id');
            
            // Flag per associazione predefinita
            $table->boolean('is_default')->default(false)->after('vat_nature_id');
            
            // Utente che ha creato l'associazione
            $table->unsignedBigInteger('created_by')->nullable()->after('is_default');
            
            // Indici per performance
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');
            $table->foreign('vat_nature_id')->references('id')->on('vat_natures')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Indice composto per unicità associazione
            $table->unique(['tax_rate_id', 'vat_nature_id'], 'unique_tax_vat_association');
            
            // Indici per query frequenti
            $table->index(['active', 'is_default']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('vat_nature_associations', function (Blueprint $table) {
            // Rimuovi foreign keys
            $table->dropForeign(['tax_rate_id']);
            $table->dropForeign(['vat_nature_id']); 
            $table->dropForeign(['created_by']);
            
            // Rimuovi indici
            $table->dropUnique('unique_tax_vat_association');
            $table->dropIndex(['active', 'is_default']);
            $table->dropIndex(['created_by']);
            
            // Rimuovi nuove colonne
            $table->dropColumn([
                'uuid', 'nome_associazione', 'descrizione', 
                'tax_rate_id', 'vat_nature_id', 'is_default', 'created_by'
            ]);
            
            // Ripristina colonne originali
            $table->string('code', 20)->unique()->after('id');
            $table->integer('sort_order')->default(0)->after('active');
            
            $table->index(['active', 'sort_order']);
        });
    }
};