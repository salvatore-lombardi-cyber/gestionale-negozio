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
        Schema::table('product_categories', function (Blueprint $table) {
            // Campi per gerarchia
            $table->unsignedBigInteger('parent_id')->nullable()->after('description');
            $table->integer('level')->default(0)->after('parent_id');
            $table->text('path')->nullable()->after('level'); // Es: "Electronics/Computers/Laptops"
            
            // Campi per visualizzazione
            $table->string('color_hex', 7)->nullable()->after('path'); // Es: "#FF5733"
            $table->string('icon', 50)->nullable()->after('color_hex'); // Es: "bi-laptop"
            
            // Campi audit trail
            $table->unsignedBigInteger('created_by')->nullable()->after('icon');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Indici per performance
            $table->index(['parent_id', 'active']);
            $table->index(['level', 'active']);
            $table->index(['path']);
            $table->index(['sort_order', 'name']);
            
            // Foreign key per parent
            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id', 'active']);
            $table->dropIndex(['level', 'active']);
            $table->dropIndex(['path']);
            $table->dropIndex(['sort_order', 'name']);
            
            $table->dropColumn([
                'parent_id', 'level', 'path', 'color_hex', 'icon',
                'created_by', 'updated_by'
            ]);
        });
    }
};
