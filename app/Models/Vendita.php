<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendita extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cliente_id',
        'data_vendita',
        'totale',
        'sconto',
        'totale_finale',
        'metodo_pagamento',
        'note'
    ];
    
    protected $casts = [
        'data_vendita' => 'date',
        'totale' => 'decimal:2',
        'sconto' => 'decimal:2',
        'totale_finale' => 'decimal:2',
    ];
    
    // Una vendita appartiene a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    // Una vendita ha molti dettagli
    public function dettagli()
    {
        return $this->hasMany(DettaglioVendita::class);
    }
    // Relazione con DDT
    public function ddt()
    {
        return $this->hasOne(Ddt::class);
    }
}