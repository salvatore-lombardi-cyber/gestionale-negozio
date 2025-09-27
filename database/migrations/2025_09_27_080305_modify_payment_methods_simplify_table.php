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
        $existingData = \DB::table('payment_methods')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('payment_methods');
        
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice modalità pagamento');
            $table->string('description')->comment('Descrizione modalità pagamento');
            $table->boolean('banca')->default(false)->comment('Richiede coordinate bancarie');
            $table->timestamps();
        });
        
        // Ripristina i dati preservando il campo banca se esiste
        foreach ($existingData as $data) {
            \DB::table('payment_methods')->insert([
                'code' => $data->code,
                'description' => $data->description,
                'banca' => isset($data->banca) ? (bool)$data->banca : false,
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
        $existingData = \DB::table('payment_methods')->get();
        
        // Ripristina struttura precedente
        Schema::dropIfExists('payment_methods');
        
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('description', 100);
            $table->boolean('banca')->default(false);
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('payment_methods')->insert([
                'code' => $data->code,
                'description' => $data->description,
                'banca' => $data->banca,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};