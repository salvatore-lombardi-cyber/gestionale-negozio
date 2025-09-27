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
        // Salva i dati esistenti
        $existingData = \DB::table('product_categories')->select('code', 'description')->get();
        
        // Droppa e ricrea la tabella (semplice e sicura)
        Schema::dropIfExists('product_categories');
        
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice categoria');
            $table->text('description')->comment('Descrizione categoria');
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('product_categories')->insert([
                'code' => $data->code,
                'description' => $data->description,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->string('name')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->integer('level')->default(0);
            $table->string('path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('color_hex')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('active')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->softDeletes();
        });
    }
};
