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
        // Salva i dati esistenti (preservando descrizione)
        $existingData = \DB::table('transports')->get();
        
        // Droppa e ricrea la tabella per struttura semplificata
        Schema::dropIfExists('transports');
        
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione modalità di trasporto');
            $table->text('comment')->nullable()->comment('Commento aggiuntivo');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('description');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('transports')->insert([
                'description' => $data->description ?? $data->name ?? 'Trasporto generico',
                'comment' => $data->comment ?? null,
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcuni trasporti predefiniti se non ci sono dati
        if ($existingData->isEmpty()) {
            \DB::table('transports')->insert([
                [
                    'description' => 'Corriere espresso',
                    'comment' => 'Consegna in 24-48 ore lavorative',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Trasporto proprio',
                    'comment' => 'Consegna con mezzi aziendali',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Spedizioniere',
                    'comment' => 'Trasporto tramite spedizioniere terzo',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Ritiro in sede',
                    'comment' => 'Cliente ritira direttamente presso i nostri uffici',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Salva i dati semplificati
        $existingData = \DB::table('transports')->get();
        
        // Ripristina struttura precedente complessa
        Schema::dropIfExists('transports');
        
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ripristina i dati con struttura complessa
        foreach ($existingData as $data) {
            \DB::table('transports')->insert([
                'code' => strtoupper(substr(preg_replace('/[^A-Z0-9_-]/', '', str_replace(' ', '_', $data->description)), 0, 20)),
                'name' => $data->description,
                'description' => $data->comment,
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};
