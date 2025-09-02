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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->string('group')->nullable(); // numeratori, stampe, generale, etc.
            $table->timestamps();
        });

        Schema::create('document_numbering', function (Blueprint $table) {
            $table->id();
            $table->string('document_type'); // fatture, ddt, preventivi, etc.
            $table->integer('current_number')->default(1);
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('use_year')->default(true);
            $table->boolean('use_month')->default(false);
            $table->string('separator')->default('/');
            $table->integer('reset_frequency')->default(1); // 1=annuale, 2=mensile, 3=mai
            $table->timestamps();
            
            $table->unique('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('document_numbering');
    }
};