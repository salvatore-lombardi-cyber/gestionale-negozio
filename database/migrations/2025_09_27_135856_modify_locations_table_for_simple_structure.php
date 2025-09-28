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
        // Salva i dati esistenti (preservando name come dep e description come ubicazione)
        $existingData = \DB::table('locations')->get();
        
        // Droppa e ricrea la tabella per struttura semplificata
        Schema::dropIfExists('locations');
        
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('dep')->comment('Deposito/Magazzino');
            $table->string('ubicazione')->comment('Ubicazione specifica (scaffale, ripiano, zona)');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('dep');
            $table->index('ubicazione');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('locations')->insert([
                'dep' => $data->name ?? $data->code ?? 'Deposito Principale',
                'ubicazione' => $data->description ?? 'Zona A - Scaffale 01',
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcune ubicazioni predefinite se non ci sono dati
        if ($existingData->isEmpty()) {
            \DB::table('locations')->insert([
                [
                    'dep' => 'Deposito Principale',
                    'ubicazione' => 'Zona A - Scaffale 01 - Ripiano 1',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'dep' => 'Deposito Principale',
                    'ubicazione' => 'Zona A - Scaffale 01 - Ripiano 2',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'dep' => 'Deposito Secondario',
                    'ubicazione' => 'Zona B - Scaffale 10 - Ripiano 1',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'dep' => 'Magazzino Esterno',
                    'ubicazione' => 'Area Container - Settore C1',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'dep' => 'Deposito Temporaneo',
                    'ubicazione' => 'Zona Transito - Bancale T01',
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
        $existingData = \DB::table('locations')->get();
        
        // Ripristina struttura precedente complessa
        Schema::dropIfExists('locations');
        
        Schema::create('locations', function (Blueprint $table) {
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
            \DB::table('locations')->insert([
                'code' => strtoupper(substr(preg_replace('/[^A-Z0-9_-]/', '', str_replace(' ', '_', $data->dep)), 0, 20)),
                'name' => $data->dep,
                'description' => $data->ubicazione,
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};
