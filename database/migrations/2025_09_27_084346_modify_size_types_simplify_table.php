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
        // Salva i dati esistenti (preservando descrizione o nome)
        $existingData = \DB::table('size_types')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('size_types');
        
        Schema::create('size_types', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descrizione tipo di taglia');
            $table->timestamps();
        });
        
        // Ripristina i dati preservando solo descrizione
        foreach ($existingData as $data) {
            \DB::table('size_types')->insert([
                'description' => $data->description ?? $data->name ?? 'Tipo di taglia',
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
        $existingData = \DB::table('size_types')->get();
        
        // Ripristina struttura complessa precedente
        Schema::dropIfExists('size_types');
        
        Schema::create('size_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ripristina i dati con struttura complessa
        foreach ($existingData as $data) {
            \DB::table('size_types')->insert([
                'code' => 'ST' . str_pad($data->id, 3, '0', STR_PAD_LEFT),
                'name' => $data->description,
                'description' => $data->description,
                'category' => 'clothing',
                'measurement_unit' => 'mixed',
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};