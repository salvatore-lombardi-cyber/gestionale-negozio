<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Controller enterprise per gestione multi-utente
 * Architettura enterprise con controllo permessi granulare
 */
class UserManagementController extends Controller
{
    /**
     * Controllo permessi per ogni metodo
     * In Laravel 12 i middleware vengono gestiti diversamente
     */
    public function __construct()
    {
        // I middleware vengono gestiti nelle routes per Laravel 12
    }

    /**
     * Verifica permessi utente corrente
     */
    private function checkPermissions(): void
    {
        if (!Auth::check()) {
            abort(401, 'Accesso non autorizzato');
        }

        if (!Auth::user()->hasSpecialPermission('can_manage_users') && !Auth::user()->isAdmin()) {
            abort(403, 'Non hai i permessi per gestire gli utenti');
        }
    }

    // ================================
    // DASHBOARD E LISTING
    // ================================

    /**
     * Dashboard gestione utenti
     */
    public function index(Request $request): View
    {
        $this->checkPermissions();
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');
        $company_id = $request->get('company_id');

        $users = User::query()
            ->with(['company', 'permissions'])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('department', 'LIKE', "%{$search}%");
                });
            })
            ->when($role, function($query, $role) {
                $query->where('role', $role);
            })
            ->when($status !== null, function($query) use ($status) {
                $query->where('is_active', $status == '1');
            })
            ->when($company_id, function($query, $company_id) {
                $query->where('company_id', $company_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $companies = Company::active()->get();
        $totalUsers = User::count();
        $activeUsers = User::active()->count();
        $inactiveUsers = $totalUsers - $activeUsers;

        // Statistiche per ruoli
        $roleStats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        return view('users.index', compact(
            'users', 'companies', 'totalUsers', 'activeUsers', 
            'inactiveUsers', 'roleStats', 'search', 'role', 'status', 'company_id'
        ));
    }

    /**
     * Visualizza dettagli utente
     */
    public function show(User $user): View
    {
        $this->checkPermissions();
        $user->load(['company', 'permissions']);
        
        $permissionHistory = $user->permissions()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.show', compact('user', 'permissionHistory'));
    }

    // ================================
    // CREAZIONE UTENTE
    // ================================

    /**
     * Form creazione nuovo utente
     */
    public function create(): View
    {
        $this->checkPermissions();
        $companies = Company::active()->get();
        $roles = $this->getAvailableRoles();
        
        return view('users.create', compact('companies', 'roles'));
    }

    /**
     * Salva nuovo utente
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkPermissions();
        $validated = $this->validateUserData($request);

        // Genera password temporanea
        $temporaryPassword = Str::random(12);
        
        // Gestione upload avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'role' => $validated['role'],
            'company_id' => $validated['company_id'] ?? null,
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $avatarPath,
            'is_active' => true
        ]);

        // Crea permessi base per il ruolo
        if (isset($validated['role'])) {
            $permissions = UserPermission::createBasePermissions($user->id, $validated['role']);
            $permissions->save();
        }

        // Invia email con credenziali (opzionale)
        if ($request->has('send_credentials')) {
            $this->sendWelcomeEmail($user, $temporaryPassword);
        }

        return redirect()
            ->route('configurations.users.show', $user)
            ->with('success', 'Utente creato con successo. Password temporanea: ' . $temporaryPassword);
    }

    // ================================
    // MODIFICA UTENTE
    // ================================

    /**
     * Form modifica utente
     */
    public function edit(User $user): View
    {
        $this->checkPermissions();
        $user->load(['company', 'permissions']);
        $companies = Company::active()->get();
        $roles = $this->getAvailableRoles();
        
        return view('users.edit', compact('user', 'companies', 'roles'));
    }

    /**
     * Aggiorna utente
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->checkPermissions();
        $validated = $this->validateUserData($request, $user->id);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'company_id' => $validated['company_id'] ?? null,
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()
            ->route('configurations.users.show', $user)
            ->with('success', 'Utente aggiornato con successo');
    }

    // ================================
    // GESTIONE PERMESSI
    // ================================

    /**
     * Form gestione permessi utente
     */
    public function permissions(User $user): View
    {
        $this->checkPermissions();
        $user->load('permissions');
        
        $availableModules = $this->getAvailableModules();
        $specialPermissions = $this->getSpecialPermissions();
        
        $currentPermission = $user->activePermissions()->first();
        
        return view('users.permissions', compact(
            'user', 'availableModules', 'specialPermissions', 'currentPermission'
        ));
    }

    /**
     * Aggiorna permessi utente
     */
    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $this->checkPermissions();
        $validated = $request->validate([
            'modules' => 'array',
            'modules.*' => 'array',
            'modules.*.read' => 'boolean',
            'modules.*.write' => 'boolean',
            'modules.*.delete' => 'boolean',
            'special_permissions' => 'array',
            'special_permissions.*' => 'boolean',
            'restrictions' => 'array',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Disattiva permessi precedenti
        $user->permissions()->update(['is_active' => false]);

        // Crea nuovo permesso
        UserPermission::create([
            'user_id' => $user->id,
            'modules' => $validated['modules'] ?? [],
            'special_permissions' => $validated['special_permissions'] ?? [],
            'restrictions' => $validated['restrictions'] ?? [],
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'is_active' => true,
            'notes' => $validated['notes'] ?? null
        ]);

        return redirect()
            ->route('configurations.users.show', $user)
            ->with('success', 'Permessi aggiornati con successo');
    }

    // ================================
    // AZIONI UTENTE
    // ================================

    /**
     * Attiva/Disattiva utente
     */
    public function toggleStatus(User $user): JsonResponse
    {
        $this->checkPermissions();
        $user->update(['is_active' => !$user->is_active]);
        
        return response()->json([
            'success' => true,
            'status' => $user->is_active,
            'message' => $user->is_active ? 'Utente attivato' : 'Utente disattivato'
        ]);
    }

    /**
     * Reset password utente
     */
    public function resetPassword(User $user): RedirectResponse
    {
        $this->checkPermissions();
        $newPassword = Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);

        // Invia email con nuova password (opzionale)
        // $this->sendPasswordResetEmail($user, $newPassword);

        return redirect()
            ->route('configurations.users.show', $user)
            ->with('success', 'Password resetata. Nuova password: ' . $newPassword);
    }

    /**
     * Elimina utente (soft delete)
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->checkPermissions();
        // Verifica che non sia l'ultimo admin
        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'amministratore')->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->route('configurations.users.index')
                    ->with('error', 'Non puoi eliminare l\'ultimo amministratore');
            }
        }

        $user->update(['is_active' => false]);
        
        return redirect()
            ->route('configurations.users.index')
            ->with('success', 'Utente eliminato con successo');
    }

    // ================================
    // METODI HELPER
    // ================================

    /**
     * Validazione dati utente
     */
    private function validateUserData(Request $request, int $userId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($userId ? ",$userId" : ''),
            'role' => 'required|in:' . implode(',', array_keys($this->getAvailableRoles())),
            'company_id' => 'nullable|exists:companies,id',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        return $request->validate($rules);
    }

    /**
     * Ruoli disponibili
     */
    private function getAvailableRoles(): array
    {
        return [
            'amministratore' => 'Amministratore',
            'dipendente' => 'Dipendente',
            'sola_lettura' => 'Sola Lettura',
            'responsabile' => 'Responsabile',
            'contabile' => 'Contabile'
        ];
    }

    /**
     * Moduli disponibili per permessi
     */
    private function getAvailableModules(): array
    {
        return [
            'magazzino' => 'Gestione Magazzino',
            'vendite' => 'Gestione Vendite',
            'fatturazione' => 'Fatturazione',
            'anagrafiche' => 'Anagrafiche',
            'configurazioni' => 'Configurazioni'
        ];
    }

    /**
     * Permessi speciali disponibili
     */
    private function getSpecialPermissions(): array
    {
        return [
            'can_manage_users' => 'Gestione Utenti',
            'can_delete_invoices' => 'Eliminazione Fatture',
            'can_modify_prices' => 'Modifica Prezzi',
            'can_access_sensitive_data' => 'Accesso Dati Sensibili',
            'can_export_data' => 'Esportazione Dati'
        ];
    }

    /**
     * Invia email di benvenuto (placeholder)
     */
    private function sendWelcomeEmail(User $user, string $password): void
    {
        // TODO: Implementare invio email
        // Mail::to($user->email)->send(new WelcomeEmail($user, $password));
    }
}