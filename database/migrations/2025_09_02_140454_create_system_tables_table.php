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
        Schema::create('system_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('code')->nullable();
            $table->string('description');
            $table->text('extra_data')->nullable(); // JSON per dati aggiuntivi
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['table_name', 'active']);
        });

        // Creare le tabelle specifiche più importanti
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->decimal('rate', 5, 2);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->string('type')->nullable(); // contanti, carta, bonifico, etc.
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3);
            $table->string('name');
            $table->string('symbol');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_tables');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('currencies');
    }
};