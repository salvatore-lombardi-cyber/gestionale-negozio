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
        // Salva i dati esistenti (preservando code come valuta, exchange_rate come conversione, name come descrizione)
        $existingData = \DB::table('currencies')->get();
        
        // Droppa e ricrea la tabella per struttura semplificata
        Schema::dropIfExists('currencies');
        
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('valuta', 10)->comment('Codice valuta (EUR, USD, GBP, etc.)');
            $table->decimal('conversione', 12, 6)->comment('Tasso di conversione rispetto alla valuta base');
            $table->string('descrizione')->comment('Descrizione completa della valuta');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->index('valuta');
            $table->index('conversione');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('currencies')->insert([
                'valuta' => $data->code ?? 'EUR',
                'conversione' => $data->exchange_rate ?? 1.000000,
                'descrizione' => $data->name ?? 'Euro',
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcune valute predefinite se non ci sono dati
        if ($existingData->isEmpty()) {
            \DB::table('currencies')->insert([
                [
                    'valuta' => 'EUR',
                    'conversione' => 1.000000,
                    'descrizione' => 'Euro - Valuta europea',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'valuta' => 'USD',
                    'conversione' => 1.100000,
                    'descrizione' => 'Dollaro americano - Valuta statunitense',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'valuta' => 'GBP',
                    'conversione' => 0.850000,
                    'descrizione' => 'Sterlina britannica - Valuta del Regno Unito',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'valuta' => 'CHF',
                    'conversione' => 0.950000,
                    'descrizione' => 'Franco svizzero - Valuta della Svizzera',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'valuta' => 'JPY',
                    'conversione' => 165.000000,
                    'descrizione' => 'Yen giapponese - Valuta del Giappone',
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
        $existingData = \DB::table('currencies')->get();
        
        // Ripristina struttura precedente complessa
        Schema::dropIfExists('currencies');
        
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name', 150);
            $table->string('symbol', 10)->nullable();
            $table->decimal('exchange_rate', 12, 6);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        
        // Ripristina i dati con struttura complessa
        foreach ($existingData as $data) {
            \DB::table('currencies')->insert([
                'code' => substr($data->valuta, 0, 3),
                'name' => $data->descrizione,
                'symbol' => substr($data->valuta, 0, 3),
                'exchange_rate' => $data->conversione,
                'active' => true,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};
