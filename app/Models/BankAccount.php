<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nome_banca',
        'abi',
        'cab',
        'conto_corrente',
        'iban',
        'swift',
        'sia',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Decripta IBAN per visualizzazione
     */
    public function getIbanAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // Se fallisce la decriptazione, ritorna il valore originale
            return $value;
        }
    }

    /**
     * Decripta SWIFT per visualizzazione
     */
    public function getSwiftAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // Se fallisce la decriptazione, ritorna il valore originale
            return $value;
        }
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}