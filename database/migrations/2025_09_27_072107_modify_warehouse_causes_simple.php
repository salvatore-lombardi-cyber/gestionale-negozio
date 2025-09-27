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
        $existingData = \DB::table('warehouse_causes')->select('code', 'description')->get();
        
        // Droppa e ricrea la tabella (semplice e sicura)
        Schema::dropIfExists('warehouse_causes');
        
        Schema::create('warehouse_causes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice causale magazzino');
            $table->text('description')->comment('Descrizione causale magazzino');
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('warehouse_causes')->insert([
                'code' => $data->code,
                'description' => $data->description,
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
        Schema::table('warehouse_causes', function (Blueprint $table) {
            // Ripristina colonne se necessario rollback
            $table->integer('sort_order')->default(0);
        });
    }
};