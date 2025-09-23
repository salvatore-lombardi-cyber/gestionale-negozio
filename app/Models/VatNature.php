<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model semplificato per Natura IVA
 * Versione base con Cod.IVA, %, Natura, Riferimento Normativo
 */
class VatNature extends Model
{
    use HasFactory;

    protected $fillable = [
        'vat_code',
        'percentage',
        'nature',
        'legal_reference'
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione sicura OWASP
    public static function validationRules(): array
    {
        return [
            'vat_code' => 'required|string|max:10|unique:vat_natures,vat_code|regex:/^[A-Z0-9._-]+$/',
            'percentage' => 'required|numeric|min:0|max:100',
            'nature' => 'required|string|max:255|min:2',
            'legal_reference' => 'nullable|string|max:500'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['vat_code'] = 'required|string|max:10|unique:vat_natures,vat_code,' . $id . '|regex:/^[A-Z0-9._-]+$/';
        return $rules;
    }
}