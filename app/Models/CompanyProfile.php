<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'ragione_sociale',
        'nome',
        'cognome',
        'genere',
        'indirizzo_sede',
        'cap',
        'provincia',
        'citta',
        'nazione',
        'telefono1',
        'telefono2',
        'fax',
        'cellulare1',
        'cellulare2',
        'email',
        'sito_web',
        'partita_iva',
        'codice_attivita_iva',
        'regime_fiscale',
        'attivita',
        'numero_tribunale',
        'cciaa',
        'capitale_sociale',
        'provincia_nascita',
        'luogo_nascita',
        'data_nascita',
        'iva_esente',
        'sdi_username',
        'sdi_password',
        'logo_path',
    ];

    protected $casts = [
        'iva_esente' => 'boolean',
        'data_nascita' => 'date',
        'capitale_sociale' => 'decimal:2',
    ];

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return null;
    }
}