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
        // Disabilita temporaneamente i controlli foreign key
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Salva i dati esistenti
        $existingData = \DB::table('vat_natures')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('vat_natures');
        
        Schema::create('vat_natures', function (Blueprint $table) {
            $table->id();
            $table->string('vat_code', 10)->unique()->comment('Codice IVA');
            $table->decimal('percentage', 5, 2)->comment('Percentuale IVA');
            $table->string('nature')->comment('Natura IVA');
            $table->text('legal_reference')->nullable()->comment('Riferimento normativo');
            $table->timestamps();
        });
        
        // Ripristina i dati esistenti
        foreach ($existingData as $data) {
            \DB::table('vat_natures')->insert([
                'vat_code' => $data->vat_code,
                'percentage' => $data->percentage ?? 0,
                'nature' => $data->nature,
                'legal_reference' => $data->legal_reference ?? null,
                'created_at' => $data->created_at ?: now(),
                'updated_at' => $data->updated_at ?: now()
            ]);
        }
        
        // Riabilita i controlli foreign key
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Salva i dati semplificati
        $existingData = \DB::table('vat_natures')->get();
        
        // Ripristina struttura precedente
        Schema::dropIfExists('vat_natures');
        
        Schema::create('vat_natures', function (Blueprint $table) {
            $table->id();
            $table->string('vat_code', 10)->unique();
            $table->decimal('percentage', 5, 2);
            $table->string('nature');
            $table->string('legal_reference', 500)->nullable();
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('vat_natures')->insert([
                'vat_code' => $data->vat_code,
                'percentage' => $data->percentage,
                'nature' => $data->nature,
                'legal_reference' => $data->legal_reference,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};