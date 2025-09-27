<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Aliquote IVA - Versione Semplificata
 * Gestione aliquote con solo percentuale e descrizione
 */
class TaxRate extends Model
{
    use HasFactory;

    // Campi fillable semplificati
    protected $fillable = [
        'percentuale',
        'description'
    ];

    protected $casts = [
        'percentuale' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'percentuale' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:500|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }

    /**
     * Accessor per formattare la percentuale
     */
    public function getPercentualeFormattedAttribute(): string
    {
        return number_format($this->percentuale, 2) . '%';
    }

    /**
     * Accessor per retrocompatibilità con 'rate'
     */
    public function getRateAttribute()
    {
        return $this->percentuale;
    }

    /**
     * Scope per ordinamento standard
     */
    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('percentuale');
    }
}