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
        Schema::create('transport_means', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione modalità di trasporto a mezzo');
            $table->text('comment')->nullable()->comment('Commento aggiuntivo');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('description');
        });
        
        // Aggiungi alcuni trasporti a mezzo predefiniti
        \DB::table('transport_means')->insert([
            [
                'description' => 'Trasporto via mare',
                'comment' => 'Spedizione tramite nave cargo o container marittimo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Trasporto via aerea',
                'comment' => 'Spedizione tramite aereo cargo per consegne rapide',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Trasporto ferroviario',
                'comment' => 'Spedizione tramite treno merci per distanze medie/lunghe',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Trasporto stradale',
                'comment' => 'Spedizione tramite autotrasporto e camion',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Trasporto multimodale',
                'comment' => 'Combinazione di più modalità di trasporto per ottimizzare costi e tempi',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_means');
    }
};
