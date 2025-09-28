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
        // Salva i dati esistenti (preservando codice e descrizione)
        $existingData = \DB::table('payment_types')->get();
        
        // Droppa e ricrea la tabella per rateizzazioni
        Schema::dropIfExists('payment_types');
        
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->comment('Codice identificativo piano pagamento');
            $table->string('description')->comment('Descrizione piano di pagamento');
            $table->integer('total_installments')->default(1)->comment('Numero totale rate');
            
            // Colonne per percentuali delle 12 rate
            $table->decimal('percentage_1', 5, 2)->nullable()->comment('Percentuale 1° rata');
            $table->decimal('percentage_2', 5, 2)->nullable()->comment('Percentuale 2° rata');
            $table->decimal('percentage_3', 5, 2)->nullable()->comment('Percentuale 3° rata');
            $table->decimal('percentage_4', 5, 2)->nullable()->comment('Percentuale 4° rata');
            $table->decimal('percentage_5', 5, 2)->nullable()->comment('Percentuale 5° rata');
            $table->decimal('percentage_6', 5, 2)->nullable()->comment('Percentuale 6° rata');
            $table->decimal('percentage_7', 5, 2)->nullable()->comment('Percentuale 7° rata');
            $table->decimal('percentage_8', 5, 2)->nullable()->comment('Percentuale 8° rata');
            $table->decimal('percentage_9', 5, 2)->nullable()->comment('Percentuale 9° rata');
            $table->decimal('percentage_10', 5, 2)->nullable()->comment('Percentuale 10° rata');
            $table->decimal('percentage_11', 5, 2)->nullable()->comment('Percentuale 11° rata');
            $table->decimal('percentage_12', 5, 2)->nullable()->comment('Percentuale 12° rata');
            
            $table->boolean('end_payment')->default(false)->comment('Pagamento a fine lavori');
            $table->timestamps();
            $table->softDeletes();
            
            // Indici per performance
            $table->unique('code');
            $table->index('total_installments');
        });
        
        // Ripristina i dati convertendo al nuovo formato
        foreach ($existingData as $data) {
            \DB::table('payment_types')->insert([
                'code' => $data->code ?? 'GENERIC',
                'description' => $data->description ?? $data->name ?? 'Piano di pagamento',
                'total_installments' => 1,
                'percentage_1' => 100.00, // Pagamento unico
                'end_payment' => false,
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Aggiungi alcuni piani predefiniti se non ci sono dati
        if ($existingData->isEmpty()) {
            // Piano 1: Pagamento unico
            \DB::table('payment_types')->insert([
                'code' => 'UNICA',
                'description' => 'Pagamento in unica soluzione',
                'total_installments' => 1,
                'percentage_1' => 100.00,
                'end_payment' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Piano 2: 3 rate uguali
            \DB::table('payment_types')->insert([
                'code' => 'RATE_3',
                'description' => 'Pagamento in 3 rate uguali',
                'total_installments' => 3,
                'percentage_1' => 33.33,
                'percentage_2' => 33.33,
                'percentage_3' => 33.34,
                'end_payment' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Piano 3: Acconto 50%
            \DB::table('payment_types')->insert([
                'code' => 'ACCONTO_50',
                'description' => 'Acconto 50% + saldo alla consegna',
                'total_installments' => 2,
                'percentage_1' => 50.00,
                'percentage_2' => 50.00,
                'end_payment' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Piano 4: Fine lavori
            \DB::table('payment_types')->insert([
                'code' => 'FINE_LAVORI',
                'description' => 'Pagamento a fine lavori',
                'total_installments' => 1,
                'percentage_1' => 100.00,
                'end_payment' => true,
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
        // Salva i dati rateizzati
        $existingData = \DB::table('payment_types')->get();
        
        // Ripristina struttura precedente semplificata
        Schema::dropIfExists('payment_types');
        
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ripristina i dati con struttura semplificata
        foreach ($existingData as $data) {
            \DB::table('payment_types')->insert([
                'code' => $data->code,
                'name' => $data->description,
                'description' => $data->description,
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};