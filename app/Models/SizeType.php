<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Tipo di Taglie - Versione Semplificata
 * Gestione tipi di taglie con solo descrizione
 */
class SizeType extends Model
{
    use HasFactory;

    protected $table = 'size_types';

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
            'description' => 'required|string|max:255|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }
}
