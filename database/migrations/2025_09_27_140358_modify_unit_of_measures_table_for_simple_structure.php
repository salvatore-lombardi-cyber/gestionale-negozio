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
        // Salva i dati esistenti (preservando name come description e description come comment)
        $existingData = \DB::table('unit_of_measures')->get();
        
        // Droppa e ricrea la tabella per struttura semplificata
        Schema::dropIfExists('unit_of_measures');
        
        Schema::create('unit_of_measures', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione unità di misura (es. Chilogrammi, Litri, Metri)');
            $table->text('comment')->nullable()->comment('Commento aggiuntivo');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('description');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('unit_of_measures')->insert([
                'description' => $data->name ?? $data->code ?? 'Unità generica',
                'comment' => $data->description ?? null,
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcune unità di misura predefinite se non ci sono dati
        if ($existingData->isEmpty()) {
            \DB::table('unit_of_measures')->insert([
                [
                    'description' => 'Chilogrammi',
                    'comment' => 'Unità di misura per peso in chilogrammi (kg)',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Litri',
                    'comment' => 'Unità di misura per volume in litri (l)',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Metri',
                    'comment' => 'Unità di misura per lunghezza in metri (m)',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Pezzi',
                    'comment' => 'Unità di misura per conteggio singoli pezzi (pz)',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Confezioni',
                    'comment' => 'Unità di misura per confezioni multiple (conf)',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'description' => 'Metri quadrati',
                    'comment' => 'Unità di misura per superfici in metri quadrati (m²)',
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
        $existingData = \DB::table('unit_of_measures')->get();
        
        // Ripristina struttura precedente complessa
        Schema::dropIfExists('unit_of_measures');
        
        Schema::create('unit_of_measures', function (Blueprint $table) {
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
            \DB::table('unit_of_measures')->insert([
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
