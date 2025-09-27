<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Banche - Versione Semplificata
 * Gestione banche con descrizione, ABI, CAB e BIC
 */
class Bank extends Model
{
    use HasFactory;

    // Campi fillable semplificati
    protected $fillable = [
        'description',
        'abi_code',
        'cab_code', 
        'bic_swift'
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
            'abi_code' => 'nullable|string|size:5|regex:/^\d{5}$/',
            'cab_code' => 'nullable|string|size:5|regex:/^\d{5}$/',
            'bic_swift' => 'nullable|string|min:8|max:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }

    // Sanitizzazione automatica input
    public function setAbiCodeAttribute($value): void
    {
        $this->attributes['abi_code'] = $value ? str_pad(trim($value), 5, '0', STR_PAD_LEFT) : null;
    }

    public function setCabCodeAttribute($value): void
    {
        $this->attributes['cab_code'] = $value ? str_pad(trim($value), 5, '0', STR_PAD_LEFT) : null;
    }

    public function setBicSwiftAttribute($value): void
    {
        $this->attributes['bic_swift'] = $value ? strtoupper(trim($value)) : null;
    }
}