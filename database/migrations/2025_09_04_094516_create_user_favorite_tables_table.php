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
        Schema::create('user_favorite_tables', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('table_objname', 100); // Nome della tabella (es: tax_rates)
            $table->integer('sort_order')->default(0); // Ordine di visualizzazione
            $table->integer('click_count')->default(0); // Contatore utilizzo
            $table->timestamp('last_accessed_at')->nullable(); // Ultimo accesso
            $table->timestamps();
            
            // Indici per performance
            $table->index(['user_id', 'sort_order']);
            $table->index(['user_id', 'click_count']);
            $table->unique(['user_id', 'table_objname']); // Un utente non può avere duplicati
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorite_tables');
    }
};
