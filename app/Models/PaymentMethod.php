<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model semplificato per Modalità di Pagamento
 * Versione base con solo codice e descrizione
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione sicura OWASP
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:10|unique:payment_methods,code|regex:/^[A-Z0-9_-]+$/',
            'description' => 'required|string|max:100|min:2'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:10|unique:payment_methods,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }
}