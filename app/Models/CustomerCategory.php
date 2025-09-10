<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Categorie Clienti (Card #6)
 * Segmentazione clientela
 */
class CustomerCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'discount_percentage',
        'color_hex',
        'icon',
        'priority_level',
        'credit_limit',
        'payment_terms_days',
        'price_list',
        'show_wholesale_prices',
        'receive_promotions',
        'priority_support',
        'require_approval',
        'max_orders_per_day',
        'activated_at',
        'deactivated_at',
        'metadata',
        'notes',
        'active'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'priority_level' => 'integer',
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
        'show_wholesale_prices' => 'boolean',
        'receive_promotions' => 'boolean',
        'priority_support' => 'boolean',
        'require_approval' => 'boolean',
        'max_orders_per_day' => 'integer',
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

    public function scopeByPriority(Builder $query): Builder
    {
        return $query->orderBy('priority_level', 'desc');
    }

    public function scopeWithDiscount(Builder $query): Builder
    {
        return $query->where('discount_percentage', '>', 0);
    }

    // Accessors
    public function getFormattedDiscountAttribute(): string
    {
        return $this->discount_percentage ? "{$this->discount_percentage}%" : 'Nessuno';
    }

    public function getFormattedCreditLimitAttribute(): string
    {
        return $this->credit_limit ? '€ ' . number_format($this->credit_limit, 2) : 'Illimitato';
    }

    public function getTypeTranslatedAttribute(): string
    {
        $translations = [
            'B2B' => 'Business to Business',
            'B2C' => 'Business to Consumer', 
            'WHOLESALE' => 'All\'ingrosso',
            'RETAIL' => 'Al dettaglio',
            'VIP' => 'Cliente VIP',
            'STANDARD' => 'Standard'
        ];
        
        return $translations[$this->type] ?? $this->type;
    }

    public function getPriorityLevelBadgeAttribute(): string
    {
        $badges = [
            'LOW' => '<span class="badge bg-secondary">Bassa</span>',
            'MEDIUM' => '<span class="badge bg-primary">Media</span>',
            'HIGH' => '<span class="badge bg-warning">Alta</span>',
            'PREMIUM' => '<span class="badge bg-success">Premium</span>'
        ];
        
        return $badges[$this->priority_level] ?? '<span class="badge bg-light">N/A</span>';
    }

    // Business Logic Methods
    public function canPlaceOrder(int $dailyOrderCount = 0): bool
    {
        if (!$this->active) {
            return false;
        }

        if ($this->max_orders_per_day && $dailyOrderCount >= $this->max_orders_per_day) {
            return false;
        }

        return true;
    }

    public function isWithinCreditLimit(float $orderAmount, float $currentDebt = 0): bool
    {
        if (!$this->credit_limit) {
            return true; // Illimitato
        }

        return ($currentDebt + $orderAmount) <= $this->credit_limit;
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
                'unique:customer_categories,code' . ($id ? ",$id" : '')
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
            'type' => [
                'required',
                'in:B2B,B2C,WHOLESALE,RETAIL,VIP,STANDARD'
            ],
            'discount_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999.99'
            ],
            'payment_terms_days' => [
                'required',
                'integer',
                'min:0',
                'max:365'
            ],
            'price_list' => [
                'required',
                'in:LIST_1,LIST_2,LIST_3,WHOLESALE,RETAIL'
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
            'max_orders_per_day' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    // Scopes aggiuntivi
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeVip(Builder $query): Builder
    {
        return $query->where('type', 'VIP')->orWhere('priority_level', 'PREMIUM');
    }

    public function scopeB2B(Builder $query): Builder
    {
        return $query->where('type', 'B2B');
    }

    public function scopeB2C(Builder $query): Builder
    {
        return $query->where('type', 'B2C');
    }
}