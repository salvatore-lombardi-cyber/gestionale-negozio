<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_pagebuilder', function (Blueprint $table) {
            $table->id();
            $table->string('objname')->unique()->comment('Nome identificativo oggetto/tabella');
            $table->string('tablename')->comment('Nome tabella database fisica');
            $table->string('display_name')->comment('Nome visualizzato nella UI');
            $table->string('icon_svg', 2000)->comment('Codice SVG icona personalizzata');
            $table->string('color_from')->comment('Colore gradiente iniziale (Tailwind/Hex)');
            $table->string('color_to')->comment('Colore gradiente finale (Tailwind/Hex)');
            $table->json('fields_config')->comment('Configurazione campi (names, types, validation)');
            $table->json('ui_config')->comment('Configurazione UI (columns, filters, sorting)');
            $table->json('permissions')->comment('Configurazione permessi RBAC');
            $table->boolean('is_active')->default(true)->comment('Tabella attiva');
            $table->integer('sort_order')->default(0)->comment('Ordine visualizzazione');
            $table->boolean('enable_search')->default(true)->comment('Abilitare ricerca');
            $table->boolean('enable_export')->default(true)->comment('Abilitare export');
            $table->string('audit_level')->default('full')->comment('Livello auditing: none,basic,full');
            $table->timestamps();
            
            // Indici per performance
            $table->index(['objname', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_pagebuilder');
    }
};