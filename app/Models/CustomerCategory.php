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
        'discount_percentage',
        'color_hex',
        'priority_level',
        'credit_limit',
        'payment_terms_days',
        'active'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'priority_level' => 'integer',
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
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
}