<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Settori Merceologici - Versione Semplificata
 * Gestione settori merceologici con codice e descrizione
 */
class MerchandiseSector extends Model
{
    use HasFactory;

    protected $table = 'merchandise_sectors';

    // Campi fillable semplificati
    protected $fillable = [
        'code',
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
            'code' => 'required|string|max:20|min:1|unique:merchandise_sectors,code',
            'description' => 'required|string|max:255|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:merchandise_sectors,code,' . $id,
            'description' => 'required|string|max:255|min:1'
        ];
    }
}
