<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Categorie Fornitori (Card #7)
 * Classificazione fornitori
 */
class SupplierCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_type',
        'sector',
        'reliability_rating',
        'quality_rating',
        'performance_rating',
        'payment_terms_days',
        'discount_expected',
        'minimum_order_value',
        'approval_required',
        'preferred_contact_method',
        'lead_time_days',
        'security_clearance_level',
        'requires_nda',
        'audit_frequency_months',
        'color_hex',
        'icon',
        'contract_template',
        'auto_renewal',
        'activated_at',
        'deactivated_at',
        'metadata',
        'notes',
        'active'
    ];

    protected $casts = [
        'reliability_rating' => 'integer',
        'quality_rating' => 'integer',
        'performance_rating' => 'integer',
        'payment_terms_days' => 'integer',
        'discount_expected' => 'decimal:2',
        'minimum_order_value' => 'decimal:2',
        'approval_required' => 'boolean',
        'lead_time_days' => 'integer',
        'requires_nda' => 'boolean',
        'audit_frequency_months' => 'integer',
        'auto_renewal' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'metadata' => 'array',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeBySector(Builder $query, string $sector): Builder
    {
        return $query->where('sector', $sector);
    }

    public function scopeTopRated(Builder $query, int $minRating = 4): Builder
    {
        return $query->where('reliability_rating', '>=', $minRating);
    }

    // Accessors
    public function getReliabilityStarsAttribute(): string
    {
        return str_repeat('★', $this->reliability_rating) . str_repeat('☆', 5 - $this->reliability_rating);
    }

    public function getQualityStarsAttribute(): string
    {
        return str_repeat('★', $this->quality_rating) . str_repeat('☆', 5 - $this->quality_rating);
    }

    public function getPerformanceStarsAttribute(): string
    {
        return str_repeat('★', $this->performance_rating) . str_repeat('☆', 5 - $this->performance_rating);
    }

    public function getCategoryTypeTranslatedAttribute(): string
    {
        $translations = [
            'STRATEGIC' => 'Strategico',
            'PREFERRED' => 'Preferito', 
            'TRANSACTIONAL' => 'Transazionale',
            'PANEL' => 'In valutazione',
            'ON_HOLD' => 'Sospeso'
        ];
        
        return $translations[$this->category_type] ?? $this->category_type;
    }

    public function getSecurityLevelBadgeAttribute(): string
    {
        $badges = [
            'LOW' => '<span class="badge bg-success">Basso</span>',
            'MEDIUM' => '<span class="badge bg-warning">Medio</span>',
            'HIGH' => '<span class="badge bg-danger">Alto</span>',
            'CRITICAL' => '<span class="badge bg-dark">Critico</span>'
        ];
        
        return $badges[$this->security_clearance_level] ?? '<span class="badge bg-light">N/A</span>';
    }

    public function getFormattedMinimumOrderAttribute(): string
    {
        return $this->minimum_order_value ? '€ ' . number_format($this->minimum_order_value, 2) : 'Nessun minimo';
    }

    public function getContactMethodTranslatedAttribute(): string
    {
        $methods = [
            'EMAIL' => 'Email',
            'PHONE' => 'Telefono',
            'PORTAL' => 'Portale Web',
            'EDI' => 'EDI/B2B'
        ];
        
        return $methods[$this->preferred_contact_method] ?? $this->preferred_contact_method;
    }

    // Business Logic Methods
    public function requiresApproval(): bool
    {
        return $this->approval_required || $this->category_type === 'STRATEGIC' || $this->security_clearance_level === 'CRITICAL';
    }

    public function isHighPerformance(): bool
    {
        return ($this->reliability_rating + $this->quality_rating + $this->performance_rating) / 3 >= 4;
    }

    public function needsAudit(): bool
    {
        if (!$this->audit_frequency_months) {
            return false;
        }

        $lastAudit = $this->metadata['last_audit_date'] ?? null;
        if (!$lastAudit) {
            return true;
        }

        $nextAuditDate = \Carbon\Carbon::parse($lastAudit)->addMonths($this->audit_frequency_months);
        return now()->gte($nextAuditDate);
    }

    // OWASP Security Validations
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:supplier_categories,code' . ($id ? ",$id" : '')
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ]+$/'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'category_type' => [
                'required',
                'in:STRATEGIC,PREFERRED,TRANSACTIONAL,PANEL,ON_HOLD'
            ],
            'sector' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ&.]+$/'
            ],
            'reliability_rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'quality_rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'performance_rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'payment_terms_days' => [
                'required',
                'integer',
                'min:0',
                'max:365'
            ],
            'discount_expected' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'minimum_order_value' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999.99'
            ],
            'preferred_contact_method' => [
                'required',
                'in:EMAIL,PHONE,PORTAL,EDI'
            ],
            'lead_time_days' => [
                'nullable',
                'integer',
                'min:0',
                'max:365'
            ],
            'security_clearance_level' => [
                'required',
                'in:LOW,MEDIUM,HIGH,CRITICAL'
            ],
            'audit_frequency_months' => [
                'nullable',
                'integer',
                'min:1',
                'max:60'
            ],
            'color_hex' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/'
            ],
            'icon' => [
                'required',
                'string',
                'max:50',
                'regex:/^bi-[a-z0-9-]+$/'
            ],
            'contract_template' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_.]+$/'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    // Scopes aggiuntivi per procurement
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('category_type', $type);
    }

    public function scopeStrategic(Builder $query): Builder
    {
        return $query->where('category_type', 'STRATEGIC');
    }

    public function scopePreferred(Builder $query): Builder
    {
        return $query->where('category_type', 'PREFERRED');
    }

    public function scopeHighSecurity(Builder $query): Builder
    {
        return $query->whereIn('security_clearance_level', ['HIGH', 'CRITICAL']);
    }

    public function scopeRequiringApproval(Builder $query): Builder
    {
        return $query->where('approval_required', true)
                    ->orWhere('category_type', 'STRATEGIC')
                    ->orWhere('security_clearance_level', 'CRITICAL');
    }

    public function scopeNeedsAudit(Builder $query): Builder
    {
        return $query->whereNotNull('audit_frequency_months')
                    ->where(function($q) {
                        $q->whereNull('metadata->last_audit_date')
                          ->orWhereRaw('DATE_ADD(STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.last_audit_date")), "%Y-%m-%d"), INTERVAL audit_frequency_months MONTH) <= NOW()');
                    });
    }
}