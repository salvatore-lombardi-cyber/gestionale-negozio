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
        // Aggiorna i ruoli esistenti da inglese a italiano
        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'amministratore']);
            
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'amministratore']);
            
        DB::table('users')
            ->where('role', 'employee')
            ->update(['role' => 'dipendente']);
            
        DB::table('users')
            ->where('role', 'readonly')
            ->update(['role' => 'sola_lettura']);
            
        DB::table('users')
            ->where('role', 'manager')
            ->update(['role' => 'responsabile']);
            
        DB::table('users')
            ->where('role', 'accountant')
            ->update(['role' => 'contabile']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: riporta i ruoli in inglese
        DB::table('users')
            ->where('role', 'amministratore')
            ->update(['role' => 'admin']);
            
        DB::table('users')
            ->where('role', 'dipendente')
            ->update(['role' => 'employee']);
            
        DB::table('users')
            ->where('role', 'sola_lettura')
            ->update(['role' => 'readonly']);
            
        DB::table('users')
            ->where('role', 'responsabile')
            ->update(['role' => 'manager']);
            
        DB::table('users')
            ->where('role', 'contabile')
            ->update(['role' => 'accountant']);
    }
};