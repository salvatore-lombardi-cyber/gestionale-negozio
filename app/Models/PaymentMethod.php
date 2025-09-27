<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Modalità di Pagamento - Versione Semplificata
 * Gestione modalità pagamento con codice, descrizione e checkbox banca
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    // Campi fillable semplificati
    protected $fillable = [
        'code',
        'description',
        'banca'
    ];

    protected $casts = [
        'banca' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:payment_methods,code',
            'description' => 'required|string|max:255|min:1',
            'banca' => 'boolean'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:payment_methods,code,' . $id,
            'description' => 'required|string|max:255|min:1',
            'banca' => 'boolean'
        ];
    }
}