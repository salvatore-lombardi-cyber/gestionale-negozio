<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendita extends Model
{
    use HasFactory;
    
    protected $table = 'venditas';
    
    protected $fillable = [
        'cliente_id',
        'tipo_documento',
        'numero_documento',
        'data_documento',
        'data_vendita',
        'subtotale',
        'iva',
        'totale',
        'sconto',
        'totale_finale',
        'metodo_pagamento',
        'prodotti_vendita',
        'note'
    ];
    
    protected $casts = [
        'data_vendita' => 'date',
        'data_documento' => 'date',
        'subtotale' => 'decimal:2',
        'iva' => 'decimal:2',
        'totale' => 'decimal:2',
        'sconto' => 'decimal:2',
        'totale_finale' => 'decimal:2',
        'prodotti_vendita' => 'array',
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