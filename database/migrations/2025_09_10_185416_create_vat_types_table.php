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
        Schema::create('vat_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['active', 'sort_order']);
        });

        // Inserimento nature IVA italiane standard
        DB::table('vat_types')->insert([
            ['code' => 'N1', 'name' => 'Escluso ex art. 15', 'description' => 'Operazione esclusa da IVA ex art. 15 del DPR 633/1972', 'active' => true, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N2.1', 'name' => 'Non soggetto ad IVA', 'description' => 'Operazione non soggetta ad IVA ai sensi degli artt. da 7 a 7-septies del DPR 633/1972', 'active' => true, 'sort_order' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N2.2', 'name' => 'Non soggetto - altri casi', 'description' => 'Operazione non soggetta ad IVA per altri casi', 'active' => true, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.1', 'name' => 'Non imponibile - esportazioni', 'description' => 'Operazione non imponibile per esportazioni ex art. 8 del DPR 633/1972', 'active' => true, 'sort_order' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.2', 'name' => 'Non imponibile - cessioni intracomunitarie', 'description' => 'Operazione non imponibile per cessioni intracomunitarie ex art. 8-bis del DPR 633/1972', 'active' => true, 'sort_order' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.3', 'name' => 'Non imponibile - cessioni verso San Marino', 'description' => 'Operazione non imponibile per cessioni verso San Marino ex art. 8-bis del DPR 633/1972', 'active' => true, 'sort_order' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.4', 'name' => 'Non imponibile - operazioni assimilate', 'description' => 'Operazioni non imponibili per operazioni assimilate alle cessioni ex art. 21 del DPR 633/1972', 'active' => true, 'sort_order' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.5', 'name' => 'Non imponibile - cessioni di beni viaggiatori', 'description' => 'Operazione non imponibile per cessioni di beni ai viaggiatori ex art. 38-quater del DPR 633/1972', 'active' => true, 'sort_order' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N3.6', 'name' => 'Non imponibile - operazioni varie', 'description' => 'Operazioni non imponibili per operazioni varie ex artt. 4, 6 e 8-bis del DPR 633/1972', 'active' => true, 'sort_order' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N4', 'name' => 'Esente', 'description' => 'Operazione esente da IVA ex artt. 10 del DPR 633/1972', 'active' => true, 'sort_order' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N5', 'name' => 'Regime del margine', 'description' => 'Operazione soggetta al regime del margine ex art. 36 del DL 41/1995', 'active' => true, 'sort_order' => 110, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.1', 'name' => 'Inversione contabile - cessione rottami', 'description' => 'Operazione con inversione contabile per cessione rottami ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.2', 'name' => 'Inversione contabile - cessione oro', 'description' => 'Operazione con inversione contabile per cessione oro ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 130, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.3', 'name' => 'Inversione contabile - subappalto settore edile', 'description' => 'Operazione con inversione contabile per subappalto settore edile ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 140, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.4', 'name' => 'Inversione contabile - cessione edifici', 'description' => 'Operazione con inversione contabile per cessione edifici ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.5', 'name' => 'Inversione contabile - cessione telefoni cellulari', 'description' => 'Operazione con inversione contabile per cessione telefoni cellulari ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 160, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.6', 'name' => 'Inversione contabile - cessione prodotti elettronici', 'description' => 'Operazione con inversione contabile per cessione prodotti elettronici ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 170, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.7', 'name' => 'Inversione contabile - prestazioni settore edile', 'description' => 'Operazione con inversione contabile per prestazioni settore edile ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 180, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.8', 'name' => 'Inversione contabile - operazioni settore energetico', 'description' => 'Operazione con inversione contabile per operazioni settore energetico ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 190, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N6.9', 'name' => 'Inversione contabile - altri casi', 'description' => 'Operazione con inversione contabile per altri casi ex art. 17, comma 6 del DPR 633/1972', 'active' => true, 'sort_order' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'N7', 'name' => 'IVA assolta in altro stato UE', 'description' => 'Operazione con IVA assolta in altro stato membro UE ex art. 7-ter del DPR 633/1972', 'active' => true, 'sort_order' => 210, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_types');
    }
};
