<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegimeFiscale extends Model
{
    protected $fillable = [
        'codice',
        'descrizione',
        'attivo'
    ];

    protected $casts = [
        'attivo' => 'boolean'
    ];

    /**
     * Scope per ottenere solo i regimi attivi
     */
    public function scopeAttivo($query)
    {
        return $query->where('attivo', true);
    }

    /**
     * Formatta il regime per display (codice + descrizione)
     */
    public function getDisplayNameAttribute()
    {
        return $this->codice . ' - ' . $this->descrizione;
    }

    /**
     * Ordina per codice (RF01, RF02, ecc.)
     */
    public function scopeOrdinato($query)
    {
        return $query->orderBy('codice');
    }
}
