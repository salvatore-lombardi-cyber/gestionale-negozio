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
        $existingData = \DB::table('deposits')->get();
        
        // Droppa e ricrea la tabella con nomenclatura italiana
        Schema::dropIfExists('deposits');
        
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Codice deposito');
            $table->string('description')->comment('Descrizione deposito');
            $table->string('indirizzo')->nullable()->comment('Indirizzo');
            $table->string('localita')->nullable()->comment('Località/Città');
            $table->string('stato')->nullable()->comment('Stato');
            $table->string('provincia', 5)->nullable()->comment('Provincia');
            $table->string('cap', 10)->nullable()->comment('CAP');
            $table->string('telefono', 20)->nullable()->comment('Telefono');
            $table->string('fax', 20)->nullable()->comment('Fax');
            $table->timestamps();
        });
        
        // Ripristina i dati mappando i campi inglesi a quelli italiani
        foreach ($existingData as $data) {
            \DB::table('deposits')->insert([
                'code' => $data->code,
                'description' => $data->description,
                'indirizzo' => $data->address,
                'localita' => $data->city,
                'stato' => $data->state,
                'provincia' => $data->province,
                'cap' => $data->postal_code,
                'telefono' => $data->phone,
                'fax' => $data->fax,
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
        // Salva i dati italiani
        $existingData = \DB::table('deposits')->get();
        
        // Ripristina struttura inglese
        Schema::dropIfExists('deposits');
        
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('description');
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('province', 5)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->timestamps();
        });
        
        // Ripristina i dati
        foreach ($existingData as $data) {
            \DB::table('deposits')->insert([
                'code' => $data->code,
                'description' => $data->description,
                'address' => $data->indirizzo,
                'city' => $data->localita,
                'state' => $data->stato,
                'province' => $data->provincia,
                'postal_code' => $data->cap,
                'phone' => $data->telefono,
                'fax' => $data->fax,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
    }
};