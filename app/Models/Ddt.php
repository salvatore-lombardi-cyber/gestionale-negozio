<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ddt extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_ddt',
        'data_ddt',
        'vendita_id',
        'cliente_id',
        'destinatario_nome',
        'destinatario_cognome',
        'destinatario_indirizzo',
        'destinatario_citta',
        'destinatario_cap',
        'causale',
        'trasportatore',
        'note',
        'stato'
    ];

    protected $casts = [
        'data_ddt' => 'date',
    ];

    // Relazione con Vendita
    public function vendita()
    {
        return $this->belongsTo(Vendita::class);
    }

    // Relazione con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Attributo per nome completo destinatario
    public function getDestinatarioCompletoAttribute()
    {
        return $this->destinatario_nome . ' ' . $this->destinatario_cognome;
    }

    // Attributo per indirizzo completo destinatario
    public function getIndirizzoCompletoAttribute()
    {
        return $this->destinatario_indirizzo . ', ' . $this->destinatario_cap . ' ' . $this->destinatario_citta;
    }

    // Metodo per generare numero DDT automatico
    public static function generaNumeroDdt()
    {
        $anno = date('Y');
        $ultimoDdt = self::whereYear('created_at', $anno)->orderBy('id', 'desc')->first();
        
        if ($ultimoDdt) {
            $ultimoNumero = (int) substr($ultimoDdt->numero_ddt, -3);
            $nuovoNumero = $ultimoNumero + 1;
        } else {
            $nuovoNumero = 1;
        }
        
        return 'DDT-' . $anno . '-' . str_pad($nuovoNumero, 3, '0', STR_PAD_LEFT);
    }
}