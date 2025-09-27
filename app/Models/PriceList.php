<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Listini - Versione Semplificata
 * Gestione listini con descrizione e percentuale
 */
class PriceList extends Model
{
    use HasFactory;

    protected $table = 'price_lists';

    // Campi fillable semplificati
    protected $fillable = [
        'description',
        'percentuale'
    ];

    protected $casts = [
        'percentuale' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'description' => 'required|string|max:255|min:1',
            'percentuale' => 'required|numeric|between:-100,1000'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'description' => 'required|string|max:255|min:1',
            'percentuale' => 'required|numeric|between:-100,1000'
        ];
    }
}
