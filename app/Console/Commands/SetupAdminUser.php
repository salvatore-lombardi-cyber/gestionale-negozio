<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetupAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:admin {--force : Force creation even if admin exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the initial super admin user for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Sistema Multi-Utente - Setup Amministratore');
        $this->line('================================================');

        // Verifica se esiste già un super admin
        if (User::where('role', 'super_admin')->exists() && !$this->option('force')) {
            $this->error('❌ Un super amministratore esiste già nel sistema!');
            $this->line('Usa --force per sovrascrivere.');
            return 1;
        }

        // Raccolta dati
        $name = $this->ask('👤 Nome completo dell\'amministratore');
        $email = $this->ask('📧 Email dell\'amministratore');
        
        // Validazione email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            $this->error('❌ Email non valida o già esistente!');
            return 1;
        }

        $password = $this->secret('🔒 Password (minimo 8 caratteri)');
        
        if (strlen($password) < 8) {
            $this->error('❌ Password deve essere di almeno 8 caratteri!');
            return 1;
        }

        $confirmPassword = $this->secret('🔒 Conferma password');
        
        if ($password !== $confirmPassword) {
            $this->error('❌ Le password non coincidono!');
            return 1;
        }

        // Creazione utente
        $this->info('⏳ Creazione amministratore...');
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Creazione permessi completi
        UserPermission::create([
            'user_id' => $user->id,
            'modules' => [
                'magazzino' => ['read' => true, 'write' => true, 'delete' => true],
                'vendite' => ['read' => true, 'write' => true, 'delete' => true],
                'fatturazione' => ['read' => true, 'write' => true, 'delete' => true],
                'anagrafiche' => ['read' => true, 'write' => true, 'delete' => true],
                'configurazioni' => ['read' => true, 'write' => true, 'delete' => true],
                'reports' => ['read' => true, 'write' => true, 'delete' => true]
            ],
            'special_permissions' => [
                'can_manage_users' => true,
                'can_view_reports' => true,
                'can_delete_invoices' => true,
                'can_modify_prices' => true,
                'can_access_sensitive_data' => true,
                'can_export_data' => true
            ],
            'is_active' => true,
            'notes' => 'Amministratore iniziale del sistema'
        ]);

        $this->info('✅ Super Amministratore creato con successo!');
        $this->line('================================================');
        $this->line("👤 Nome: {$name}");
        $this->line("📧 Email: {$email}");
        $this->line("🔑 Ruolo: Super Amministratore");
        $this->line("🎯 Permessi: Completi su tutti i moduli");
        $this->line('================================================');
        $this->info('🎉 Il sistema è pronto per l\'uso!');

        return 0;
    }
}
