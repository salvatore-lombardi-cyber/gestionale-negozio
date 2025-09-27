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
        $existingData = \DB::table('porti')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('porti');
        
        Schema::create('porti', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione condizione di trasporto');
            $table->text('comment')->nullable()->comment('Commento aggiuntivo');
            $table->timestamps();
        });
        
        // Ripristina i dati esistenti
        foreach ($existingData as $data) {
            \DB::table('porti')->insert([
                'description' => $data->description,
                'comment' => $data->comment ?? null,
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
        $existingData = \DB::table('porti')->get();
        
        // Ripristina struttura precedente
        Schema::dropIfExists('porti');
        
        Schema::create('porti', function (Blueprint $table) {
            $table->id();
            $table->string('description', 100);
            $table->string('comment')->nullable();
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('porti')->insert([
                'description' => $data->description,
                'comment' => $data->comment,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};