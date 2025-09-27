<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Condizioni - Versione Semplificata
 * Gestione condizioni con solo descrizione
 */
class Condition extends Model
{
    use HasFactory;

    protected $table = 'conditions';

    // Campi fillable semplificati
    protected $fillable = [
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'description' => 'required|string|max:500|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }
}
