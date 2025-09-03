<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SystemPagebuilder extends Model
{
    use HasFactory;

    protected $table = 'system_pagebuilder';
    
    protected $fillable = [
        'objname',
        'tablename', 
        'display_name',
        'icon_svg',
        'color_from',
        'color_to',
        'fields_config',
        'ui_config',
        'permissions',
        'is_active',
        'sort_order',
        'enable_search',
        'enable_export',
        'audit_level'
    ];

    protected $casts = [
        'fields_config' => 'array',
        'ui_config' => 'array', 
        'permissions' => 'array',
        'is_active' => 'boolean',
        'enable_search' => 'boolean',
        'enable_export' => 'boolean',
        'sort_order' => 'integer'
    ];

    // ===== SCOPES BUSINESS LOGIC =====
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query) 
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }

    // ===== ACCESSORS =====
    
    public function getColorGradientAttribute(): string
    {
        return "bg-gradient-to-br from-{$this->color_from} to-{$this->color_to}";
    }
    
    public function getColorHoverAttribute(): string
    {
        // Genera hover effect più scuro
        return "hover:from-{$this->color_from}/90 hover:to-{$this->color_to}/90";
    }

    // ===== BUSINESS METHODS =====
    
    /**
     * Ottieni configurazione tabella con cache intelligente
     */
    public static function getTableConfig(string $objname): ?self
    {
        $cacheKey = "pagebuilder.config.{$objname}";
        
        return Cache::remember($cacheKey, 3600, function() use ($objname) {
            return self::active()->where('objname', $objname)->first();
        });
    }
    
    /**
     * Ottieni tutte le tabelle configurate per dashboard
     */
    public static function getDashboardTables(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = 'pagebuilder.dashboard.tables';
        
        return Cache::remember($cacheKey, 1800, function() {
            return self::active()->ordered()->get();
        });
    }
    
    /**
     * Validazione sicurezza OWASP per accesso tabella
     */
    public function canUserAccess(\App\Models\User $user, string $action = 'read'): bool
    {
        // Verifica permessi RBAC
        $userRole = $user->role ?? 'user';
        $requiredPermissions = $this->permissions[$action] ?? ['admin'];
        
        if (in_array($userRole, $requiredPermissions) || in_array('*', $requiredPermissions)) {
            // Log accesso per auditing
            Log::info("SystemTable access granted", [
                'user_id' => $user->id,
                'table' => $this->objname,
                'action' => $action,
                'role' => $userRole
            ]);
            
            return true;
        }
        
        // Log tentativo accesso negato
        Log::warning("SystemTable access denied", [
            'user_id' => $user->id,
            'table' => $this->objname,
            'action' => $action,
            'role' => $userRole,
            'required' => $requiredPermissions
        ]);
        
        return false;
    }
    
    /**
     * Invalidazione cache intelligente
     */
    public function clearCaches(): void
    {
        Cache::forget("pagebuilder.config.{$this->objname}");
        Cache::forget('pagebuilder.dashboard.tables');
        
        Log::info("PageBuilder caches cleared", ['table' => $this->objname]);
    }
    
    /**
     * Override save per invalidare cache
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);
        $this->clearCaches();
        return $result;
    }

    // ===== AUDIT TRAIL =====
    
    protected static function booted()
    {
        static::created(function ($model) {
            Log::info('SystemPagebuilder created', [
                'objname' => $model->objname,
                'created_by' => auth()->id()
            ]);
        });

        static::updated(function ($model) {
            Log::info('SystemPagebuilder updated', [
                'objname' => $model->objname, 
                'changes' => $model->getDirty(),
                'updated_by' => auth()->id()
            ]);
        });
    }
}