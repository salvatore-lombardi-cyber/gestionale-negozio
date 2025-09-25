<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anagrafica extends Model
{
    use HasFactory;

    protected $table = 'anagrafiche';

    protected $fillable = [
        // Identificazione
        'tipo',
        'codice_interno',
        'tipo_soggetto',
        
        // Dati base
        'nome',
        'cognome',
        'altro',
        
        // Indirizzo
        'indirizzo',
        'provincia',
        'comune',
        'cap',
        'nazione',
        
        // Dati fiscali
        'codice_fiscale',
        'partita_iva',
        'partita_iva_cee',
        'codice_sdi',
        'is_pubblica_amministrazione',
        'split_payment',
        
        // Contatti
        'telefono_1',
        'telefono_2',
        'fax_1',
        'fax_2',
        'contatto_referente',
        'email',
        'sito_web',
        'pec',
        'numero_tessera',
        
        // Dati commerciali
        'sconto_1',
        'sconto_2',
        'valuta',
        
        // Coordinate bancarie
        'banca',
        'iban',
        'swift',
        
        // Dati specifici fornitori
        'codice_fornitore',
        'condizioni_pagamento',
        'lead_time_giorni',
        'categoria_merceologica',
        'responsabile_acquisti',
        'note_interne',
        
        // Dati specifici vettori
        'codice_vettore',
        'zone_consegna',
        'tariffe_trasporto',
        'tempi_standard_ore',
        'tipo_trasporto',
        'assicurazione_disponibile',
        
        // Dati specifici agenti
        'codice_agente',
        'percentuale_provvigione',
        'zone_competenza',
        'tipo_contratto',
        'data_inizio_contratto',
        'obiettivi_vendita_annuali',
        
        // Dati specifici articoli
        'codice_articolo',
        'categoria_articolo',
        'unita_misura',
        'prezzo_acquisto',
        'prezzo_vendita',
        'scorta_minima',
        'fornitore_principale_id',
        'note_tecniche',
        'codice_barre',
        'descrizione_estesa',
        
        // Dati specifici servizi
        'codice_servizio',
        'categoria_servizio',
        'durata_standard_minuti',
        'tariffa_oraria',
        'competenze_richieste',
        'materiali_inclusi',
        
        // Metadati
        'attivo',
    ];

    protected $casts = [
        'is_pubblica_amministrazione' => 'boolean',
        'split_payment' => 'boolean',
        'assicurazione_disponibile' => 'boolean',
        'attivo' => 'boolean',
        'sconto_1' => 'decimal:2',
        'sconto_2' => 'decimal:2',
        'percentuale_provvigione' => 'decimal:2',
        'prezzo_acquisto' => 'decimal:4',
        'prezzo_vendita' => 'decimal:4',
        'tariffa_oraria' => 'decimal:2',
        'obiettivi_vendita_annuali' => 'decimal:2',
        'data_inizio_contratto' => 'date',
        'zone_consegna' => 'array',
        'tariffe_trasporto' => 'array',
        'zone_competenza' => 'array',
        'competenze_richieste' => 'array',
        'materiali_inclusi' => 'array',
    ];

    // SCOPE PER TIPO
    public function scopeClienti($query)
    {
        return $query->where('tipo', 'cliente');
    }

    public function scopeFornitori($query)
    {
        return $query->where('tipo', 'fornitore');
    }

    public function scopeVettori($query)
    {
        return $query->where('tipo', 'vettore');
    }

    public function scopeAgenti($query)
    {
        return $query->where('tipo', 'agente');
    }

    public function scopeArticoli($query)
    {
        return $query->where('tipo', 'articolo');
    }

    public function scopeServizi($query)
    {
        return $query->where('tipo', 'servizio');
    }

    public function scopeAttivi($query)
    {
        return $query->where('attivo', true);
    }

    // RELAZIONI
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia', 'sigla');
    }

    public function comune()
    {
        return $this->belongsTo(Comune::class, 'comune', 'nome');
    }

    public function nazione()
    {
        return $this->belongsTo(Nazione::class, 'nazione', 'codice_iso3');
    }

    public function fornitore_principale()
    {
        return $this->belongsTo(Anagrafica::class, 'fornitore_principale_id');
    }

    public function articoli_forniti()
    {
        return $this->hasMany(Anagrafica::class, 'fornitore_principale_id');
    }

    // RELAZIONI CON ALTRI MODULI
    public function vendite()
    {
        return $this->hasMany(Vendita::class, 'cliente_id')
                    ->where('tipo', 'cliente');
    }

    public function ddts()
    {
        return $this->hasMany(Ddt::class, 'cliente_id')
                    ->where('tipo', 'cliente');
    }

    // ACCESSORI
    public function getNomeCompletoAttribute()
    {
        if ($this->cognome) {
            return $this->nome . ' ' . $this->cognome;
        }
        return $this->nome;
    }

    public function getIndirizzoCompletoAttribute()
    {
        $indirizzo = collect([
            $this->indirizzo,
            $this->cap,
            $this->comune,
            $this->provincia ? "({$this->provincia})" : null,
            $this->nazione !== 'ITA' ? $this->nazione : null
        ])->filter()->join(', ');

        return $indirizzo ?: 'Non specificato';
    }

    public function getDescrizioneAttribute()
    {
        return match($this->tipo) {
            'cliente' => $this->nome_completo,
            'fornitore' => $this->nome_completo,
            'vettore' => $this->nome_completo,
            'agente' => $this->nome_completo,
            'articolo' => $this->nome . ($this->descrizione_estesa ? " - {$this->descrizione_estesa}" : ''),
            'servizio' => $this->nome . ($this->categoria_servizio ? " ({$this->categoria_servizio})" : ''),
            default => $this->nome
        };
    }

    // MUTATORI
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = ucwords(strtolower($value));
    }

    public function setCognomeAttribute($value)
    {
        $this->attributes['cognome'] = $value ? ucwords(strtolower($value)) : null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? strtolower($value) : null;
    }

    public function setPartitaIvaAttribute($value)
    {
        $this->attributes['partita_iva'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setCodiceFiscaleAttribute($value)
    {
        $this->attributes['codice_fiscale'] = $value ? strtoupper($value) : null;
    }

    // METODI STATICI
    public static function generaCodiceInterno($tipo)
    {
        $prefissi = [
            'cliente' => 'CLI',
            'fornitore' => 'FOR',
            'vettore' => 'VET',
            'agente' => 'AGE', 
            'articolo' => 'ART',
            'servizio' => 'SER'
        ];

        $prefisso = $prefissi[$tipo] ?? 'GEN';
        $numero = static::where('tipo', $tipo)->count() + 1;
        
        return $prefisso . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    public static function tipiDisponibili()
    {
        return [
            'cliente' => 'Cliente',
            'fornitore' => 'Fornitore', 
            'vettore' => 'Vettore',
            'agente' => 'Agente',
            'articolo' => 'Articolo',
            'servizio' => 'Servizio'
        ];
    }

    // EVENTI MODEL
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($anagrafica) {
            if (empty($anagrafica->codice_interno)) {
                $anagrafica->codice_interno = static::generaCodiceInterno($anagrafica->tipo);
            }
        });
    }
}