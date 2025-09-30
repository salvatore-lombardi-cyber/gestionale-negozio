<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role',
        'company_id',
        'department',
        'bio',
        'preferences',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Verifica se l'utente ha un ruolo specifico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role || $this->role === 'super_admin';
    }

    /**
     * Verifica se l'utente ha uno dei ruoli specificati
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles) || $this->role === 'super_admin';
    }

    /**
     * Verifica se l'utente è amministratore
     */
    public function isAdmin(): bool
    {
        return $this->role === 'amministratore';
    }

    /**
     * Verifica se l'utente può configurare il sistema
     */
    public function canConfigure(): bool
    {
        return $this->role === 'amministratore';
    }

    // ================================
    // RELAZIONI MULTI-UTENTE
    // ================================

    /**
     * Relazione con Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relazione con UserPermission
     */
    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * Ottiene i permessi attivi
     */
    public function activePermissions()
    {
        return $this->permissions()
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            });
    }

    // ================================
    // METODI SISTEMA PERMESSI
    // ================================

    /**
     * Verifica se l'utente può accedere a un modulo
     */
    public function canAccessModule(string $module, string $action = 'read'): bool
    {
        // Super admin ha accesso a tutto
        if ($this->role === 'super_admin') {
            return true;
        }

        // Verifica permessi specifici
        $permissions = $this->activePermissions()->get();
        
        foreach ($permissions as $permission) {
            $modules = $permission->modules ?? [];
            if (isset($modules[$module][$action]) && $modules[$module][$action] === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica permesso speciale
     */
    public function hasSpecialPermission(string $permission): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        $permissions = $this->activePermissions()->get();
        
        foreach ($permissions as $perm) {
            $special = $perm->special_permissions ?? [];
            if (isset($special[$permission]) && $special[$permission] === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ottiene restrizioni attive
     */
    public function getRestrictions(): array
    {
        $restrictions = [];
        
        $permissions = $this->activePermissions()->get();
        foreach ($permissions as $permission) {
            if ($permission->restrictions) {
                $restrictions = array_merge($restrictions, $permission->restrictions);
            }
        }
        
        return $restrictions;
    }

    /**
     * Aggiorna ultimo accesso
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    // ================================
    // SCOPES E QUERY HELPERS
    // ================================

    /**
     * Scope per utenti attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per utenti di una specifica company
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope per ruoli specifici
     */
    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
