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
        Schema::table('prodottos', function (Blueprint $table) {
            // Codice alfanumerico leggibile (es: MAG001-L-ROSSO)
            $table->string('codice_etichetta', 25)->nullable()->after('codice_prodotto');
            $table->index('codice_etichetta'); // Per ricerca veloce
            
            // QR Code info
            $table->text('qr_code')->nullable()->after('codice_etichetta');
            $table->string('qr_code_path')->nullable()->after('qr_code');
            $table->boolean('qr_enabled')->default(true)->after('qr_code_path');
            
            // Contatore per progressivo categoria
            $table->integer('progressivo_categoria')->nullable()->after('qr_enabled');
        });

        Schema::table('magazzinos', function (Blueprint $table) {
            // Codice variante specifico (es: MAG001-L-ROSSO)
            $table->string('codice_variante', 30)->nullable()->after('quantita');
            $table->text('variant_qr_code')->nullable()->after('codice_variante');
            $table->string('variant_qr_path')->nullable()->after('variant_qr_code');
            $table->index('codice_variante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodottos', function (Blueprint $table) {
            $table->dropIndex(['codice_etichetta']);
            $table->dropColumn([
                'codice_etichetta', 'qr_code', 'qr_code_path', 
                'qr_enabled', 'progressivo_categoria'
            ]);
        });

        Schema::table('magazzinos', function (Blueprint $table) {
            $table->dropIndex(['codice_variante']);
            $table->dropColumn(['codice_variante', 'variant_qr_code', 'variant_qr_path']);
        });
    }
};