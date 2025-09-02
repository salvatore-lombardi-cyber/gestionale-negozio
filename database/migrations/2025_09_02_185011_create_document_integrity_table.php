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
        Schema::create('document_integrity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->string('hash', 64); // SHA-256 hash
            $table->json('blockchain_record');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_tampered')->default(false);
            $table->timestamps();
            
            $table->index(['document_id', 'hash']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_integrity');
    }
};
