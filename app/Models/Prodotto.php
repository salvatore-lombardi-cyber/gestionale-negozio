<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descrizione',
        'prezzo',
        'categoria',
        'brand',
        'codice_prodotto',
        'attivo'
    ];

    // Un prodotto può avere molte righe di magazzino
    public function magazzino()
    {
        return $this->hasMany(Magazzino::class);
    }

    // Un prodotto può essere in molti dettagli vendita
    public function dettagliVendita()
    {
        return $this->hasMany(DettaglioVendita::class);
    }
}