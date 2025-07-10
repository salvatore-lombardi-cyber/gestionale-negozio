<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazzino extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodotto_id',
        'taglia',
        'colore',
        'quantita',
        'scorta_minima'
    ];

    // Ogni riga di magazzino appartiene a un prodotto
    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}
