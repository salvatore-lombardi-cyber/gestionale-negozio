<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CausaleMagazzino extends Model
{
    use HasFactory;

    protected $table = 'causali_magazzino';

    protected $fillable = [
        'codice',
        'descrizione',
        'tipo_movimento',
        'attiva',
        'sistema',
        'note'
    ];

    protected $casts = [
        'attiva' => 'boolean',
        'sistema' => 'boolean'
    ];

    /**
     * Relazioni
     */
    public function movimenti()
    {
        return $this->hasMany(MovimentoMagazzino::class, 'causale_id');
    }

    /**
     * Scopes
     */
    public function scopeAttive($query)
    {
        return $query->where('attiva', true);
    }

    public function scopePerTipo($query, $tipo)
    {
        return $query->where('tipo_movimento', $tipo);
    }

    public function scopeSistema($query)
    {
        return $query->where('sistema', true);
    }

    public function scopePersonalizzate($query)
    {
        return $query->where('sistema', false);
    }

    /**
     * Metodi di business
     */
    public function getDescrizioneCompletaAttribute()
    {
        return $this->codice . ' - ' . $this->descrizione;
    }

    public function isModificabile()
    {
        return !$this->sistema;
    }

    /**
     * Causali predefinite del sistema
     */
    public static function getCausaliSistema()
    {
        return [
            [
                'codice' => 'CAR001',
                'descrizione' => 'Carico Standard',
                'tipo_movimento' => 'carico',
                'attiva' => true,
                'sistema' => true
            ],
            [
                'codice' => 'SCA001', 
                'descrizione' => 'Scarico Standard',
                'tipo_movimento' => 'scarico',
                'attiva' => true,
                'sistema' => true
            ],
            [
                'codice' => 'TRA001',
                'descrizione' => 'Trasferimento Standard',
                'tipo_movimento' => 'trasferimento',
                'attiva' => true,
                'sistema' => true
            ],
            [
                'codice' => 'INV001',
                'descrizione' => 'Rettifica Inventario',
                'tipo_movimento' => 'carico',
                'attiva' => true,
                'sistema' => true
            ],
            [
                'codice' => 'VEN001',
                'descrizione' => 'Vendita Cliente',
                'tipo_movimento' => 'scarico',
                'attiva' => true,
                'sistema' => true
            ],
            [
                'codice' => 'RES001',
                'descrizione' => 'Reso Cliente',
                'tipo_movimento' => 'carico',
                'attiva' => true,
                'sistema' => true
            ]
        ];
    }
}