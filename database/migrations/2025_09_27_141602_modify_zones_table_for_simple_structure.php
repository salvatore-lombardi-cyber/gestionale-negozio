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
        // Salva i dati esistenti (preservando code come codice e name/description come descrizione)
        $existingData = \DB::table('zones')->get();
        
        // Droppa e ricrea la tabella per struttura semplificata
        Schema::dropIfExists('zones');
        
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('codice', 20)->comment('Codice identificativo zona (ES. NORD-IT, SUD-EU, ASIA-PAC)');
            $table->string('descrizione')->comment('Descrizione completa della zona geografica');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('codice');
            $table->index('descrizione');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('zones')->insert([
                'codice' => $data->code ?? 'ZONA-01',
                'descrizione' => $data->name ?? $data->description ?? 'Zona geografica generica',
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcune zone predefinite se non ci sono dati
        if ($existingData->isEmpty()) {
            \DB::table('zones')->insert([
                [
                    'codice' => 'NORD-IT',
                    'descrizione' => 'Nord Italia - Lombardia, Piemonte, Veneto, Liguria',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'codice' => 'CENTRO-IT',
                    'descrizione' => 'Centro Italia - Lazio, Toscana, Umbria, Marche',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'codice' => 'SUD-IT',
                    'descrizione' => 'Sud Italia - Campania, Puglia, Calabria, Sicilia, Sardegna',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'codice' => 'EUROPA',
                    'descrizione' => 'Europa - Francia, Germania, Spagna, Paesi Bassi',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'codice' => 'MONDO',
                    'descrizione' => 'Resto del Mondo - USA, Asia, Africa, Oceania',
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
        $existingData = \DB::table('zones')->get();
        
        // Ripristina struttura precedente complessa
        Schema::dropIfExists('zones');
        
        Schema::create('zones', function (Blueprint $table) {
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
            \DB::table('zones')->insert([
                'code' => $data->codice,
                'name' => $data->descrizione,
                'description' => $data->descrizione,
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};
