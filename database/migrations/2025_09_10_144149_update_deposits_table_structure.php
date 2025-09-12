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
        Schema::table('deposits', function (Blueprint $table) {
            // Rimuove il campo 'name' se esiste (era nella struttura base)
            if (Schema::hasColumn('deposits', 'name')) {
                $table->dropColumn('name');
            }
            
            // Aggiunge i nuovi campi per depositi
            $table->string('address', 255)->nullable()->after('description');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('province', 5)->nullable()->after('state');
            $table->string('postal_code', 10)->nullable()->after('province');
            $table->string('phone', 20)->nullable()->after('postal_code');
            $table->string('fax', 20)->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Rimuove i campi aggiunti
            $table->dropColumn([
                'address', 'city', 'state', 'province', 
                'postal_code', 'phone', 'fax'
            ]);
            
            // Ripristina il campo 'name' se necessario
            $table->string('name', 255)->after('code');
        });
    }
};
