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
        Schema::create('associazionestampe', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('template_id');
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            // Indici per performance
            $table->index(['document_type_id', 'active']);
            $table->index(['template_id', 'active']);
            
            // Indice univoco per evitare duplicati
            $table->unique(['document_type_id', 'template_id', 'active'], 'unique_active_association');
            
            // Foreign keys (se le tabelle esistono)
            // $table->foreign('document_type_id')->references('id')->on('tipidocumenti')->onDelete('cascade');
            // $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associazionestampe');
    }
};
