<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Porto - Condizioni di trasporto e consegna merci
 * Gestisce chi si fa carico dei costi e responsabilità del trasporto
 * Es: Franco fabbrica, Franco destino, Porto assegnato, etc.
 */
class Porto extends Model
{
    use HasFactory;

    protected $table = 'porti';

    protected $fillable = [
        'description',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione sicura OWASP
    public static function validationRules(): array
    {
        return [
            'description' => 'required|string|max:100|min:2',
            'comment' => 'nullable|string|max:255'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }
}