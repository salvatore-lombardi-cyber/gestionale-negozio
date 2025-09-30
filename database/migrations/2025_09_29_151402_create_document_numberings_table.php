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
        Schema::create('document_numberings', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');
            $table->integer('current_number')->default(1);
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('use_year')->default(false);
            $table->boolean('use_month')->default(false);
            $table->string('separator', 10)->default('/');
            $table->integer('reset_frequency')->default(1);
            $table->timestamps();
            
            $table->unique('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_numberings');
    }
};
