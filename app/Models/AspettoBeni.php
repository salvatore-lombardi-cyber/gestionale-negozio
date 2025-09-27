<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Aspetto Beni - Versione Semplificata
 * Gestione aspetto beni con descrizione e commento
 */
class AspettoBeni extends Model
{
    use HasFactory;

    protected $table = 'aspetto_beni';

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
            'description' => 'required|string|max:500|min:1',
            'comment' => 'nullable|string|max:1000'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }
}
