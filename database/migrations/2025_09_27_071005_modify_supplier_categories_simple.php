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
        $existingData = \DB::table('supplier_categories')->select('code', 'description')->get();
        
        // Droppa e ricrea la tabella (semplice e sicura)
        Schema::dropIfExists('supplier_categories');
        
        Schema::create('supplier_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice categoria fornitore');
            $table->text('description')->comment('Descrizione categoria fornitore');
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('supplier_categories')->insert([
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
        Schema::table('supplier_categories', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }
};