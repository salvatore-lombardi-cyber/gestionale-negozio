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
        Schema::create('merchandise_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->index();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('category', 50)->default('generale')->index(); // alimentare, moda, elettronica, servizi, etc.
            $table->boolean('requires_certifications')->default(false);
            $table->json('certifications')->nullable(); // Certificazioni richieste (ISO, CE, etc.)
            $table->decimal('average_margin', 5, 2)->nullable(); // Margine medio del settore
            $table->enum('risk_level', ['basso', 'medio', 'alto'])->default('medio');
            $table->boolean('seasonal')->default(false); // Settore stagionale
            $table->boolean('active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        // Inserimento settori merceologici italiani principali
        DB::table('merchandise_sectors')->insert([
            // ALIMENTARE E BEVANDE
            ['code' => 'ALI001', 'name' => 'Alimentari Freschi', 'description' => 'Prodotti alimentari freschi: frutta, verdura, carni, latticini', 'category' => 'alimentare', 'requires_certifications' => true, 'certifications' => json_encode(['HACCP', 'ISO 22000']), 'average_margin' => 25.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ALI002', 'name' => 'Prodotti Confezionati', 'description' => 'Alimenti confezionati, conserve, prodotti da forno industriali', 'category' => 'alimentare', 'requires_certifications' => true, 'certifications' => json_encode(['HACCP', 'BRC']), 'average_margin' => 18.50, 'risk_level' => 'basso', 'seasonal' => false, 'active' => true, 'sort_order' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BEV001', 'name' => 'Bevande Alcoliche', 'description' => 'Vini, birre, liquori, distillati', 'category' => 'alimentare', 'requires_certifications' => true, 'certifications' => json_encode(['Licenza UTI', 'HACCP']), 'average_margin' => 35.00, 'risk_level' => 'alto', 'seasonal' => false, 'active' => true, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BEV002', 'name' => 'Bevande Analcoliche', 'description' => 'Acqua, bibite, succhi di frutta, bevande energetiche', 'category' => 'alimentare', 'requires_certifications' => true, 'certifications' => json_encode(['HACCP']), 'average_margin' => 28.00, 'risk_level' => 'basso', 'seasonal' => true, 'active' => true, 'sort_order' => 40, 'created_at' => now(), 'updated_at' => now()],

            // MODA E ABBIGLIAMENTO
            ['code' => 'MOD001', 'name' => 'Abbigliamento Uomo', 'description' => 'Abbigliamento maschile: abiti, camicie, pantaloni, casual wear', 'category' => 'moda', 'requires_certifications' => false, 'certifications' => json_encode(['OEKO-TEX']), 'average_margin' => 55.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'MOD002', 'name' => 'Abbigliamento Donna', 'description' => 'Abbigliamento femminile: vestiti, gonne, bluse, intimo', 'category' => 'moda', 'requires_certifications' => false, 'certifications' => json_encode(['OEKO-TEX']), 'average_margin' => 58.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'MOD003', 'name' => 'Calzature', 'description' => 'Scarpe, sandali, stivali per uomo, donna e bambini', 'category' => 'moda', 'requires_certifications' => false, 'certifications' => json_encode(['CE']), 'average_margin' => 45.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'MOD004', 'name' => 'Accessori Moda', 'description' => 'Borse, portafogli, cinture, gioielli, orologi', 'category' => 'moda', 'requires_certifications' => false, 'certifications' => json_encode(['CE']), 'average_margin' => 65.00, 'risk_level' => 'basso', 'seasonal' => false, 'active' => true, 'sort_order' => 80, 'created_at' => now(), 'updated_at' => now()],

            // ELETTRONICA E TECNOLOGIA
            ['code' => 'ELE001', 'name' => 'Elettronica di Consumo', 'description' => 'TV, audio, fotocamere, console gaming', 'category' => 'elettronica', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'RoHS', 'WEEE']), 'average_margin' => 12.50, 'risk_level' => 'alto', 'seasonal' => false, 'active' => true, 'sort_order' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ELE002', 'name' => 'Informatica', 'description' => 'Computer, tablet, smartphone, accessori informatici', 'category' => 'elettronica', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'FCC', 'RoHS']), 'average_margin' => 8.50, 'risk_level' => 'alto', 'seasonal' => false, 'active' => true, 'sort_order' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ELE003', 'name' => 'Elettrodomestici', 'description' => 'Grandi e piccoli elettrodomestici per la casa', 'category' => 'elettronica', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'Classe Energetica']), 'average_margin' => 22.00, 'risk_level' => 'medio', 'seasonal' => false, 'active' => true, 'sort_order' => 110, 'created_at' => now(), 'updated_at' => now()],

            // CASA E ARREDAMENTO
            ['code' => 'CAS001', 'name' => 'Mobili', 'description' => 'Mobili per casa e ufficio, complementi d\'arredo', 'category' => 'casa', 'requires_certifications' => false, 'certifications' => json_encode(['FSC', 'GREENGUARD']), 'average_margin' => 40.00, 'risk_level' => 'medio', 'seasonal' => false, 'active' => true, 'sort_order' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAS002', 'name' => 'Articoli per la Casa', 'description' => 'Casalinghi, stoviglie, tessuti per la casa', 'category' => 'casa', 'requires_certifications' => false, 'certifications' => json_encode(['CE']), 'average_margin' => 35.00, 'risk_level' => 'basso', 'seasonal' => true, 'active' => true, 'sort_order' => 130, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAS003', 'name' => 'Giardinaggio', 'description' => 'Piante, attrezzi da giardino, fertilizzanti, vasi', 'category' => 'casa', 'requires_certifications' => true, 'certifications' => json_encode(['Fitosanitari']), 'average_margin' => 42.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 140, 'created_at' => now(), 'updated_at' => now()],

            // SALUTE E BELLEZZA
            ['code' => 'SAL001', 'name' => 'Prodotti Farmaceutici', 'description' => 'Farmaci da banco, integratori, dispositivi medici', 'category' => 'salute', 'requires_certifications' => true, 'certifications' => json_encode(['Autorizzazione Ministeriale', 'GMP']), 'average_margin' => 30.00, 'risk_level' => 'alto', 'seasonal' => false, 'active' => true, 'sort_order' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BEL001', 'name' => 'Cosmetici e Bellezza', 'description' => 'Prodotti per la cura del corpo, makeup, profumi', 'category' => 'bellezza', 'requires_certifications' => true, 'certifications' => json_encode(['Notifica CPNP', 'ISO 22716']), 'average_margin' => 50.00, 'risk_level' => 'medio', 'seasonal' => false, 'active' => true, 'sort_order' => 160, 'created_at' => now(), 'updated_at' => now()],

            // SPORT E TEMPO LIBERO
            ['code' => 'SPO001', 'name' => 'Articoli Sportivi', 'description' => 'Attrezzature sportive, abbigliamento tecnico, calzature sportive', 'category' => 'sport', 'requires_certifications' => false, 'certifications' => json_encode(['CE']), 'average_margin' => 38.00, 'risk_level' => 'basso', 'seasonal' => true, 'active' => true, 'sort_order' => 170, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'GIO001', 'name' => 'Giocattoli', 'description' => 'Giocattoli per bambini, giochi educativi, modellismo', 'category' => 'tempo_libero', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'EN 71']), 'average_margin' => 45.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 180, 'created_at' => now(), 'updated_at' => now()],

            // AUTOMOTIVE
            ['code' => 'AUT001', 'name' => 'Ricambi Auto', 'description' => 'Ricambi e accessori per automobili, moto, veicoli commerciali', 'category' => 'automotive', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'ECE']), 'average_margin' => 32.00, 'risk_level' => 'medio', 'seasonal' => false, 'active' => true, 'sort_order' => 190, 'created_at' => now(), 'updated_at' => now()],
            
            // SERVIZI
            ['code' => 'SER001', 'name' => 'Servizi Professionali', 'description' => 'Consulenza, servizi legali, contabilità, marketing', 'category' => 'servizi', 'requires_certifications' => false, 'certifications' => json_encode(['Albo Professionale']), 'average_margin' => 60.00, 'risk_level' => 'basso', 'seasonal' => false, 'active' => true, 'sort_order' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SER002', 'name' => 'Servizi Turistici', 'description' => 'Agenzie viaggi, tour operator, strutture ricettive', 'category' => 'servizi', 'requires_certifications' => true, 'certifications' => json_encode(['Licenza Turismo']), 'average_margin' => 15.00, 'risk_level' => 'alto', 'seasonal' => true, 'active' => true, 'sort_order' => 210, 'created_at' => now(), 'updated_at' => now()],
            
            // INDUSTRIALE
            ['code' => 'IND001', 'name' => 'Materiali da Costruzione', 'description' => 'Cemento, ferro, laterizi, isolanti, vernici', 'category' => 'industriale', 'requires_certifications' => true, 'certifications' => json_encode(['CE', 'UNI EN']), 'average_margin' => 20.00, 'risk_level' => 'medio', 'seasonal' => true, 'active' => true, 'sort_order' => 220, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandise_sectors');
    }
};
