<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    use HasFactory;

    protected $table = 'depositi';

    protected $fillable = [
        'codice',
        'descrizione', 
        'indirizzo',
        'citta',
        'cap',
        'attivo',
        'principale',
        'note'
    ];

    protected $casts = [
        'attivo' => 'boolean',
        'principale' => 'boolean'
    ];

    /**
     * Relazioni
     */
    public function giacenze()
    {
        return $this->hasMany(GiacenzaMagazzino::class);
    }

    public function movimenti()
    {
        return $this->hasMany(MovimentoMagazzino::class);
    }

    public function movimentiSorgente()
    {
        return $this->hasMany(MovimentoMagazzino::class, 'deposito_sorgente_id');
    }

    public function movimentiDestinazione()
    {
        return $this->hasMany(MovimentoMagazzino::class, 'deposito_destinazione_id');
    }

    /**
     * Scopes
     */
    public function scopeAttivi($query)
    {
        return $query->where('attivo', true);
    }

    public function scopePrincipale($query)
    {
        return $query->where('principale', true);
    }

    /**
     * Metodi di business
     */
    public function getDescrizioneCompletaAttribute()
    {
        return $this->codice . ' - ' . $this->descrizione;
    }

    public function getValoreTotaleAttribute()
    {
        return $this->giacenze()
            ->join('prodottos', 'giacenze_magazzino.prodotto_id', '=', 'prodottos.id')
            ->selectRaw('SUM(giacenze_magazzino.quantita_attuale * prodottos.prezzo_costo) as valore')
            ->value('valore') ?? 0;
    }

    public function getArticoliTotaliAttribute()
    {
        return $this->giacenze()->where('quantita_attuale', '>', 0)->count();
    }
}