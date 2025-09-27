<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Porto - Versione Semplificata
 * Gestione condizioni di trasporto con descrizione e commento
 */
class Porto extends Model
{
    use HasFactory;

    protected $table = 'porti';

    // Campi fillable semplificati
    protected $fillable = [
        'description',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'description' => 'required|string|max:255|min:1',
            'comment' => 'nullable|string|max:500'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }
}