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
        Schema::create('security_audit_log', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->ipAddress('ip_address')->index();
            $table->enum('severity', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->index();
            $table->json('data'); // Dati completi dell'evento
            $table->timestamp('created_at')->index();
            
            // Indici compositi per performance
            $table->index(['event_type', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['severity', 'created_at']);
            $table->index(['user_id', 'created_at']);
            
            // Partitioning mensile per performance (se supportato dal DB)
            $table->comment('Tabella audit per eventi di sicurezza - partitioning mensile consigliato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audit_log');
    }
};