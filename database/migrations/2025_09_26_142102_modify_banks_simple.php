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
            // Aggiunge il campo CAB
            $table->string('cab_code', 5)->nullable()->after('abi_code');
            
            // Rimuove colonne inutili mantenendo solo description, abi_code, cab_code, bic_swift
            $table->dropColumn([
                'code', 
                'name', 
                'address',
                'city',
                'postal_code', 
                'country',
                'phone',
                'email',
                'website',
                'is_italian',
                'active',
                'created_by',
                'updated_by',
                'deleted_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            // Ripristina colonne
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_italian')->default(true);
            $table->boolean('active')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->dropColumn('cab_code');
        });
    }
};
