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
        // Salva i dati esistenti usando name come description
        $existingData = \DB::table('size_colors')->select('name')->get();
        
        // Droppa e ricrea la tabella (semplice e sicura)
        Schema::dropIfExists('size_colors');
        
        Schema::create('size_colors', function (Blueprint $table) {
            $table->id();
            $table->text('description')->comment('Descrizione taglia/colore');
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('size_colors')->insert([
                'description' => $data->name ?: 'Elemento importato',
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
        Schema::table('size_colors', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->string('code', 50)->nullable();
            $table->string('name')->nullable();
            $table->enum('type', ['TAGLIA', 'COLORE'])->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }
};