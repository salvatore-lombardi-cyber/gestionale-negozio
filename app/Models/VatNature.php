<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Natura IVA - Versione Semplificata
 * Gestione natura IVA con cod.iva, %, natura e riferimento normativo
 */
class VatNature extends Model
{
    use HasFactory;

    protected $table = 'vat_natures';

    // Campi fillable semplificati
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

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'vat_code' => 'required|string|max:10|min:1|unique:vat_natures,vat_code',
            'percentage' => 'required|numeric|between:0,100',
            'nature' => 'required|string|max:255|min:1',
            'legal_reference' => 'nullable|string|max:500'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'vat_code' => 'required|string|max:10|min:1|unique:vat_natures,vat_code,' . $id,
            'percentage' => 'required|numeric|between:0,100',
            'nature' => 'required|string|max:255|min:1',
            'legal_reference' => 'nullable|string|max:500'
        ];
    }
}