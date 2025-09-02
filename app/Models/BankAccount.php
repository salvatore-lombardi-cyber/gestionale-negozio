<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}