<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Taglie e Colori (Card #8)
 * Gestione varianti prodotto
 */
class SizeColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type', // 'size' o 'color'
        'code',
        'name',
        'hex_value', // per colori
        'size_category', // per taglie: XS-S-M-L-XL, numerico, etc.
        'sort_order',
        'active'
    ];

    protected $casts = [
        'sort_order' => 'integer',
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

    public function scopeSizes(Builder $query): Builder
    {
        return $query->where('type', 'size');
    }

    public function scopeColors(Builder $query): Builder
    {
        return $query->where('type', 'color');
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('size_category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getIsColorAttribute(): bool
    {
        return $this->type === 'color';
    }

    public function getIsSizeAttribute(): bool
    {
        return $this->type === 'size';
    }

    public function getColorSwatchAttribute(): ?string
    {
        if ($this->is_color && $this->hex_value) {
            return "<div style='width:20px;height:20px;background:{$this->hex_value};border:1px solid #ccc;display:inline-block;'></div>";
        }
        return null;
    }
}