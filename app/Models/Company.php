<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model per gestione multi-tenancy aziendale
 * Sistema enterprise per gestire più aziende
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'vat_number',
        'tax_code',
        'address',
        'city',
        'postal_code',
        'country',
        'settings',
        'limits',
        'plan',
        'is_active',
        'trial_ends_at',
        'subscription_ends_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'limits' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime'
    ];

    // ================================
    // RELAZIONI
    // ================================

    /**
     * Relazione con Users
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Utenti attivi della company
     */
    public function activeUsers(): HasMany
    {
        return $this->users()->where('is_active', true);
    }

    /**
     * Amministratori della company
     */
    public function admins(): HasMany
    {
        return $this->users()->whereIn('role', ['admin', 'super_admin']);
    }

    // ================================
    // SCOPES
    // ================================

    /**
     * Scope per company attive
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per company con piano specifico
     */
    public function scopeWithPlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }

    /**
     * Scope per company in trial
     */
    public function scopeInTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')
                     ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope per company con abbonamento attivo
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->whereNotNull('subscription_ends_at')
                     ->where('subscription_ends_at', '>', now());
    }

    // ================================
    // METODI BUSINESS LOGIC
    // ================================

    /**
     * Verifica se la company è in trial
     */
    public function isInTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->gt(now());
    }

    /**
     * Verifica se l'abbonamento è attivo
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->gt(now());
    }

    /**
     * Verifica se la company può operare (trial o abbonamento attivo)
     */
    public function canOperate(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->isInTrial() || $this->hasActiveSubscription();
    }

    /**
     * Ottiene un setting specifico
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->settings ?? [];
        return $settings[$key] ?? $default;
    }

    /**
     * Imposta un setting
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        $this->save();
    }

    /**
     * Ottiene un limite specifico
     */
    public function getLimit(string $key, $default = null)
    {
        $limits = $this->limits ?? [];
        return $limits[$key] ?? $default;
    }

    /**
     * Verifica se un limite è raggiunto
     */
    public function isLimitReached(string $limitType): bool
    {
        $limit = $this->getLimit($limitType);
        if (!$limit) {
            return false;
        }

        switch ($limitType) {
            case 'max_users':
                return $this->users()->count() >= $limit;
            
            case 'max_invoices_per_month':
                // Assuming we have an invoices relationship
                // return $this->invoicesThisMonth()->count() >= $limit;
                return false; // Placeholder

            default:
                return false;
        }
    }

    /**
     * Conta utenti attivi
     */
    public function countActiveUsers(): int
    {
        return $this->activeUsers()->count();
    }

    /**
     * Genera uno slug unico
     */
    public function generateUniqueSlug(): string
    {
        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ================================
    // EVENTI MODEL
    // ================================

    /**
     * Boot method per eventi automatici
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-genera slug se non presente
        static::creating(function ($company) {
            if (!$company->slug) {
                $company->slug = $company->generateUniqueSlug();
            }
        });

        // Aggiorna slug se cambia il nome
        static::updating(function ($company) {
            if ($company->isDirty('name') && !$company->isDirty('slug')) {
                $company->slug = $company->generateUniqueSlug();
            }
        });
    }

    // ================================
    // FACTORY METHODS
    // ================================

    /**
     * Crea una company con impostazioni di default
     */
    public static function createWithDefaults(array $data): self
    {
        $defaults = [
            'plan' => 'basic',
            'is_active' => true,
            'country' => 'IT',
            'settings' => [
                'locale' => 'it',
                'timezone' => 'Europe/Rome',
                'currency' => 'EUR',
                'date_format' => 'd/m/Y',
                'time_format' => 'H:i'
            ],
            'limits' => [
                'max_users' => 5,
                'max_invoices_per_month' => 100,
                'max_products' => 1000,
                'max_storage_mb' => 100
            ]
        ];

        return self::create(array_merge($defaults, $data));
    }

    /**
     * Inizia trial per la company
     */
    public function startTrial(int $days = 30): void
    {
        $this->update([
            'trial_ends_at' => now()->addDays($days)
        ]);
    }

    /**
     * Attiva abbonamento
     */
    public function activateSubscription(string $plan, int $months = 12): void
    {
        $this->update([
            'plan' => $plan,
            'subscription_ends_at' => now()->addMonths($months),
            'trial_ends_at' => null // Rimuovi trial se presente
        ]);
    }
}