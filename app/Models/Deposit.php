<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Depositi - Versione Semplificata
 * Gestione depositi con campi italiani
 */
class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposits';

    // Campi fillable con nomenclatura italiana
    protected $fillable = [
        'code',
        'description',
        'indirizzo',
        'localita',
        'stato',
        'provincia',
        'cap',
        'telefono',
        'fax'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:deposits,code',
            'description' => 'required|string|max:255|min:1',
            'indirizzo' => 'nullable|string|max:255',
            'localita' => 'nullable|string|max:100',
            'stato' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:5',
            'cap' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:deposits,code,' . $id,
            'description' => 'required|string|max:255|min:1',
            'indirizzo' => 'nullable|string|max:255',
            'localita' => 'nullable|string|max:100',
            'stato' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:5',
            'cap' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20'
        ];
    }
}
