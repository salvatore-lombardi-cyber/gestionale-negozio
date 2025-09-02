<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Nature IVA (Card #16)
 * Gestisce le nature fiscali (imponibile, non imponibile, esente, ecc.)
 */
class VatNature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'fiscal_code',
        'is_taxable',
        'notes',
        'active'
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relazioni
    public function associations()
    {
        return $this->hasMany(VatNatureAssociation::class);
    }

    public function taxRates()
    {
        return $this->belongsToMany(TaxRate::class, 'vat_nature_associations');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeTaxable(Builder $query): Builder
    {
        return $query->where('is_taxable', true);
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }
}