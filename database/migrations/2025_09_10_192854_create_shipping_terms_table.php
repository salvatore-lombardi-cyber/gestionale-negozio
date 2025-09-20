<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_terms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('incoterm_code', 10)->nullable();
            $table->enum('type', ['factory', 'delivery', 'mixed'])->default('mixed');
            $table->boolean('customer_pays_shipping')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['active', 'sort_order']);
            $table->index('type');
        });

        // Inserimento termini Porto italiani standard
        DB::table('shipping_terms')->insert([
            ['code' => 'FF', 'name' => 'Franco Fabbrica', 'description' => 'Merce ritirata presso stabilimento del fornitore, trasporto a carico del cliente', 'incoterm_code' => 'EXW', 'type' => 'factory', 'customer_pays_shipping' => true, 'active' => true, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FD', 'name' => 'Franco Domicilio', 'description' => 'Consegna presso domicilio del cliente, trasporto incluso nel prezzo', 'incoterm_code' => 'DAP', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PA', 'name' => 'Porto Assegnato', 'description' => 'Spese di trasporto a carico del destinatario', 'incoterm_code' => 'EXW', 'type' => 'factory', 'customer_pays_shipping' => true, 'active' => true, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PF', 'name' => 'Porto Franco', 'description' => 'Spese di trasporto a carico del mittente', 'incoterm_code' => 'DAP', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FOB', 'name' => 'Free On Board', 'description' => 'Franco a bordo porto di imbarco - Incoterm internazionale', 'incoterm_code' => 'FOB', 'type' => 'mixed', 'customer_pays_shipping' => true, 'active' => true, 'sort_order' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CIF', 'name' => 'Cost Insurance Freight', 'description' => 'Costo, assicurazione e nolo - Incoterm internazionale', 'incoterm_code' => 'CIF', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'EXW', 'name' => 'Ex Works', 'description' => 'Reso fabbrica - Incoterm internazionale', 'incoterm_code' => 'EXW', 'type' => 'factory', 'customer_pays_shipping' => true, 'active' => true, 'sort_order' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DAP', 'name' => 'Delivered At Place', 'description' => 'Reso al luogo di destinazione - Incoterm internazionale', 'incoterm_code' => 'DAP', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DDP', 'name' => 'Delivered Duty Paid', 'description' => 'Reso con dazi pagati - Incoterm internazionale', 'incoterm_code' => 'DDP', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CPT', 'name' => 'Carriage Paid To', 'description' => 'Trasporto pagato fino a - Incoterm internazionale', 'incoterm_code' => 'CPT', 'type' => 'delivery', 'customer_pays_shipping' => false, 'active' => true, 'sort_order' => 100, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_terms');
    }
};
