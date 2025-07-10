<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DettaglioVendita extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendita_id',
        'prodotto_id',
        'taglia',
        'colore',
        'quantita',
        'prezzo_unitario',
        'subtotale'
    ];

    protected $casts = [
        'prezzo_unitario' => 'decimal:2',
        'subtotale' => 'decimal:2',
    ];

    // Un dettaglio appartiene a una vendita
    public function vendita()
    {
        return $this->belongsTo(Vendita::class);
    }

    // Un dettaglio appartiene a un prodotto
    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}