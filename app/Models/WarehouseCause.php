<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Causali di Magazzino - Versione Semplificata
 * Gestione causali magazzino con codice e descrizione
 */
class WarehouseCause extends Model
{
    use HasFactory;

    protected $table = 'warehouse_causes';

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
            'code' => 'required|string|max:20|min:1|unique:warehouse_causes,code',
            'description' => 'required|string|max:500|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:warehouse_causes,code,' . $id,
            'description' => 'required|string|max:500|min:1'
        ];
    }
}