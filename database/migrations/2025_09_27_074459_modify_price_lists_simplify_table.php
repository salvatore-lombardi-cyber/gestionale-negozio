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
        $existingData = \DB::table('price_lists')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('price_lists');
        
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione listino');
            $table->decimal('percentuale', 8, 2)->comment('Percentuale sconto/maggiorazione');
            $table->timestamps();
        });
        
        // Ripristina i dati mappando discount_percentage a percentuale
        foreach ($existingData as $data) {
            \DB::table('price_lists')->insert([
                'description' => $data->description,
                'percentuale' => $data->discount_percentage ?? 0,
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Salva i dati semplificati
        $existingData = \DB::table('price_lists')->get();
        
        // Ripristina struttura complessa
        Schema::dropIfExists('price_lists');
        
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('description')->unique();
            $table->decimal('discount_percentage', 8, 2);
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('price_lists')->insert([
                'description' => $data->description,
                'discount_percentage' => $data->percentuale,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};