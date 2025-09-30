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
        Schema::table('users', function (Blueprint $table) {
            // Campi per sistema multi-utente (TUTTI NULLABLE per compatibilità)
            $table->unsignedBigInteger('company_id')->nullable()->after('role');
            $table->string('department')->nullable()->after('company_id');
            $table->text('bio')->nullable()->after('department');
            $table->json('preferences')->nullable()->after('bio');
            $table->timestamp('last_login_at')->nullable()->after('preferences');
            $table->boolean('is_active')->default(true)->after('last_login_at');
            $table->string('phone')->nullable()->after('is_active');
            
            // Indici per performance
            $table->index('company_id');
            $table->index(['is_active', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['is_active', 'company_id']);
            
            $table->dropColumn([
                'company_id',
                'department', 
                'bio',
                'preferences',
                'last_login_at',
                'is_active',
                'phone'
            ]);
        });
    }
};