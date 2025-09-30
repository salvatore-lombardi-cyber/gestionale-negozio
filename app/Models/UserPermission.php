<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model per gestione permessi utenti granulari
 * Sistema enterprise con permessi JSON flessibili
 */
class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modules',
        'special_permissions',
        'restrictions',
        'valid_from',
        'valid_until',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'modules' => 'array',
        'special_permissions' => 'array', 
        'restrictions' => 'array',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    // ================================
    // RELAZIONI
    // ================================

    /**
     * Relazione con User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ================================
    // SCOPES
    // ================================

    /**
     * Scope per permessi attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per permessi validi nel tempo
     */
    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', now());
        })->where(function($q) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>=', now());
        });
    }

    /**
     * Scope per permessi attivi e validi
     */
    public function scopeActiveAndValid($query)
    {
        return $query->active()->valid();
    }

    // ================================
    // METODI BUSINESS LOGIC
    // ================================

    /**
     * Verifica se il permesso è attualmente valido
     */
    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        // Controlla validità temporale
        if ($this->valid_from && $this->valid_from->gt($now)) {
            return false;
        }
        
        if ($this->valid_until && $this->valid_until->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se l'utente può eseguire un'azione su un modulo
     */
    public function canAccessModule(string $module, string $action = 'read'): bool
    {
        if (!$this->isCurrentlyValid()) {
            return false;
        }

        $modules = $this->modules ?? [];
        return isset($modules[$module][$action]) && $modules[$module][$action] === true;
    }

    /**
     * Verifica se l'utente ha un permesso speciale
     */
    public function hasSpecialPermission(string $permission): bool
    {
        if (!$this->isCurrentlyValid()) {
            return false;
        }

        $special = $this->special_permissions ?? [];
        return isset($special[$permission]) && $special[$permission] === true;
    }

    /**
     * Ottiene una restrizione specifica
     */
    public function getRestriction(string $key, $default = null)
    {
        $restrictions = $this->restrictions ?? [];
        return $restrictions[$key] ?? $default;
    }

    /**
     * Imposta permessi per un modulo
     */
    public function setModulePermissions(string $module, array $permissions): void
    {
        $modules = $this->modules ?? [];
        $modules[$module] = $permissions;
        $this->modules = $modules;
    }

    /**
     * Aggiunge un permesso speciale
     */
    public function addSpecialPermission(string $permission, bool $value = true): void
    {
        $special = $this->special_permissions ?? [];
        $special[$permission] = $value;
        $this->special_permissions = $special;
    }

    /**
     * Imposta una restrizione
     */
    public function setRestriction(string $key, $value): void
    {
        $restrictions = $this->restrictions ?? [];
        $restrictions[$key] = $value;
        $this->restrictions = $restrictions;
    }

    // ================================
    // FACTORY METHODS
    // ================================

    /**
     * Crea permessi base per un ruolo standard
     */
    public static function createBasePermissions(int $userId, string $role): self
    {
        $permissions = new self([
            'user_id' => $userId,
            'is_active' => true
        ]);

        // Definisci permessi base per ruolo
        switch ($role) {
            case 'admin':
                $permissions->modules = [
                    'magazzino' => ['read' => true, 'write' => true, 'delete' => true],
                    'vendite' => ['read' => true, 'write' => true, 'delete' => true],
                    'fatturazione' => ['read' => true, 'write' => true, 'delete' => true],
                    'configurazioni' => ['read' => true, 'write' => true, 'delete' => false],
                ];
                $permissions->special_permissions = [
                    'can_manage_users' => true,
                    'can_view_reports' => true
                ];
                break;

            case 'employee':
                $permissions->modules = [
                    'magazzino' => ['read' => true, 'write' => true, 'delete' => false],
                    'vendite' => ['read' => true, 'write' => true, 'delete' => false],
                    'fatturazione' => ['read' => true, 'write' => false, 'delete' => false],
                ];
                break;

            case 'readonly':
                $permissions->modules = [
                    'magazzino' => ['read' => true, 'write' => false, 'delete' => false],
                    'vendite' => ['read' => true, 'write' => false, 'delete' => false],
                    'fatturazione' => ['read' => true, 'write' => false, 'delete' => false],
                ];
                break;
        }

        return $permissions;
    }
}