<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Associazioni Nature IVA (Card #1 - Configuratore Speciale)
 * Gestisce le associazioni tra aliquote IVA e nature fiscali
 */
class VatNatureAssociation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tax_rate_id',
        'vat_nature_id', 
        'is_default',
        'description',
        'active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relazioni
    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function vatNature()
    {
        return $this->belongsTo(VatNature::class);
    }

    // Scopes per query ottimizzate
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    // Cache key per performance
    public function getCacheKey(): string
    {
        return "vat_nature_association_{$this->id}";
    }
}