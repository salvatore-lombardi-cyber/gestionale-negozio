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
        'sector',
        'reliability_rating',
        'payment_terms_days',
        'discount_expected',
        'quality_rating',
        'active'
    ];

    protected $casts = [
        'reliability_rating' => 'integer',
        'payment_terms_days' => 'integer',
        'discount_expected' => 'decimal:2',
        'quality_rating' => 'integer',
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
}