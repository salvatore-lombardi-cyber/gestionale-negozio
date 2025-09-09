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
        Schema::table('banks', function (Blueprint $table) {
            // Campi specifici per banche
            $table->string('abi_code', 5)->nullable()->index()->after('description'); // Codice ABI italiano
            $table->string('bic_swift', 11)->nullable()->index()->after('abi_code'); // Codice BIC/SWIFT
            $table->text('address')->nullable()->after('bic_swift'); // Indirizzo sede
            $table->string('city', 100)->nullable()->after('address'); // Città
            $table->string('postal_code', 20)->nullable()->after('city'); // CAP
            $table->string('country', 100)->default('Italia')->after('postal_code'); // Paese
            $table->string('phone', 50)->nullable()->after('country'); // Telefono
            $table->string('email', 100)->nullable()->after('phone'); // Email
            $table->string('website', 255)->nullable()->after('email'); // Sito web
            $table->boolean('is_italian')->default(true)->after('website'); // Banca italiana
            
            // Campi audit trail
            $table->unsignedBigInteger('created_by')->nullable()->after('is_italian');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Indici per performance
            $table->index(['active', 'is_italian']);
            $table->index(['abi_code', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropIndex(['abi_code', 'active']);
            $table->dropIndex(['active', 'is_italian']);
            $table->dropColumn([
                'abi_code', 'bic_swift', 'address', 'city', 'postal_code', 
                'country', 'phone', 'email', 'website', 'is_italian',
                'created_by', 'updated_by'
            ]);
        });
    }
};
