<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome',
        'cognome',
        'telefono',
        'email',
        'indirizzo',
        'citta',
        'cap',
        'data_nascita',
        'genere'
    ];
    
    protected $casts = [
        'data_nascita' => 'date',
    ];
    
    // Un cliente può avere molte vendite
    public function vendite()
    {
        return $this->hasMany(Vendita::class);
    }
    
    // Metodo per ottenere nome completo
    public function getNomeCompletoAttribute()
    {
        return $this->nome . ' ' . $this->cognome;
    }
    // Relazione con DDT
    public function ddts()
    {
        return $this->hasMany(Ddt::class);
    }
}