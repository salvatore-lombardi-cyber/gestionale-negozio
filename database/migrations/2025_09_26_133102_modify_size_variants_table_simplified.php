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
        Schema::table('size_variants', function (Blueprint $table) {
            // Rimuove colonne inutili
            $table->dropColumn(['code', 'name', 'active', 'sort_order', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('size_variants', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->softDeletes();
        });
    }
};
