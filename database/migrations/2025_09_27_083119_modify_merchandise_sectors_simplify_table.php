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
        // Salva i dati esistenti (preservando code e description)
        $existingData = \DB::table('merchandise_sectors')->get();
        
        // Droppa e ricrea la tabella semplificata
        Schema::dropIfExists('merchandise_sectors');
        
        Schema::create('merchandise_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice settore merceologico');
            $table->string('description')->comment('Descrizione settore merceologico');
            $table->timestamps();
        });
        
        // Ripristina i dati esistenti preservando solo code e description
        foreach ($existingData as $data) {
            \DB::table('merchandise_sectors')->insert([
                'code' => $data->code,
                'description' => $data->description ?? $data->name ?? 'Descrizione settore',
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
        $existingData = \DB::table('merchandise_sectors')->get();
        
        // Ripristina struttura complessa precedente
        Schema::dropIfExists('merchandise_sectors');
        
        Schema::create('merchandise_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('category')->default('generale');
            $table->boolean('requires_certifications')->default(false);
            $table->json('certifications')->nullable();
            $table->decimal('average_margin', 5, 2)->nullable();
            $table->enum('risk_level', ['basso', 'medio', 'alto'])->default('medio');
            $table->boolean('seasonal')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ripristina i dati con struttura complessa
        foreach ($existingData as $data) {
            \DB::table('merchandise_sectors')->insert([
                'code' => $data->code,
                'name' => $data->description,
                'description' => $data->description,
                'category' => 'generale',
                'requires_certifications' => false,
                'certifications' => json_encode([]),
                'average_margin' => null,
                'risk_level' => 'medio',
                'seasonal' => false,
                'active' => true,
                'sort_order' => 0,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};