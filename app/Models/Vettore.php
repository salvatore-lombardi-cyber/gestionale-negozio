<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * Model Enterprise Vettore
 * 
 * Gestione vettori/spedizionieri superiore ai competitor con:
 * - Validazioni avanzate per trasporti
 * - Calcolo automatico costi spedizione
 * - Integrazione API tracking
 * - Analisi performance consegne
 * - Compliance normative italiane ed europee
 */
class Vettore extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vettori';

    /**
     * Campi mass-assignable
     */
    protected $fillable = [
        // Dati identificativi
        'ragione_sociale', 'nome_commerciale', 'codice_vettore', 'tipo_soggetto',
        'nome', 'cognome', 'data_nascita', 'genere',
        
        // Dati fiscali
        'codice_fiscale', 'partita_iva', 'regime_fiscale', 'split_payment',
        
        // Contatti
        'email', 'pec', 'telefono', 'telefono_mobile', 'fax', 'sito_web',
        
        // Indirizzi
        'indirizzo', 'citta', 'provincia', 'cap', 'paese',
        'indirizzo_operativo', 'citta_operativa', 'provincia_operativa', 'cap_operativo', 'paese_operativo',
        
        // Trasporto
        'tipo_vettore', 'servizi_offerti', 'aree_copertura', 'tipologie_merci',
        
        // Contratto
        'costo_base_kg', 'costo_minimo_spedizione', 'soglia_franco', 'fasce_peso', 'supplementi',
        
        // Performance
        'codice_tracking_prefisso', 'api_tracking_url', 'api_tracking_key',
        'tempo_consegna_standard', 'tempo_consegna_express', 'percentuale_puntualita',
        
        // Bancari
        'iban', 'modalita_pagamento', 'giorni_pagamento',
        
        // Classificazione
        'classe_vettore', 'valutazione', 'numero_valutazioni',
        
        // Assicurazione
        'numero_polizza_assicurativa', 'massimale_assicurazione', 'scadenza_polizza', 'compagnia_assicurativa',
        
        // Autorizzazioni
        'numero_iscrizione_albo', 'licenza_trasporti', 'abilitazioni_speciali',
        
        // Stato
        'attivo', 'verificato', 'ultima_verifica_dati', 'note_interne',
        'referente_commerciale', 'telefono_referente',
        
        // Privacy
        'consenso_dati', 'consenso_marketing', 'consenso_data', 'consenso_ip',
        
        // Audit
        'created_by', 'updated_by'
    ];

    /**
     * Cast automatici
     */
    protected $casts = [
        'data_nascita' => 'date',
        'split_payment' => 'boolean',
        'servizi_offerti' => 'json',
        'aree_copertura' => 'json', 
        'tipologie_merci' => 'json',
        'costo_base_kg' => 'decimal:4',
        'costo_minimo_spedizione' => 'decimal:2',
        'soglia_franco' => 'decimal:2',
        'fasce_peso' => 'json',
        'supplementi' => 'json',
        'tempo_consegna_standard' => 'integer',
        'tempo_consegna_express' => 'integer',
        'percentuale_puntualita' => 'decimal:2',
        'giorni_pagamento' => 'integer',
        'valutazione' => 'decimal:2',
        'numero_valutazioni' => 'integer',
        'massimale_assicurazione' => 'decimal:2',
        'scadenza_polizza' => 'date',
        'abilitazioni_speciali' => 'json',
        'attivo' => 'boolean',
        'verificato' => 'boolean',
        'ultima_verifica_dati' => 'datetime',
        'consenso_dati' => 'boolean',
        'consenso_marketing' => 'boolean',
        'consenso_data' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Attributi nascosti
     */
    protected $hidden = [
        'api_tracking_key', 'consenso_ip'
    ];

    // === ACCESSORS & MUTATORS ===

    /**
     * Nome completo del vettore
     */
    protected function nomeCompleto(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->tipo_soggetto === 'persona_fisica' && $this->nome && $this->cognome) {
                    return trim("{$this->nome} {$this->cognome}");
                }
                return $this->nome_commerciale ?: $this->ragione_sociale;
            }
        );
    }

    /**
     * Indirizzo completo sede legale
     */
    protected function indirizzoCompleto(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->indirizzo}, {$this->cap} {$this->citta} ({$this->provincia})")
        );
    }

    /**
     * Indirizzo completo operativo
     */
    protected function indirizzoOperativoCompleto(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->indirizzo_operativo) return null;
                return trim("{$this->indirizzo_operativo}, {$this->cap_operativo} {$this->citta_operativa} ({$this->provincia_operativa})");
            }
        );
    }

    /**
     * Auto-maiuscolo per codice fiscale
     */
    protected function codiceFiscale(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtoupper(trim($value))
        );
    }

    /**
     * Auto-maiuscolo per provincia
     */
    protected function provincia(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? strtoupper(trim($value)) : null
        );
    }

    /**
     * Auto-maiuscolo per provincia operativa
     */
    protected function provinciaOperativa(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? strtoupper(trim($value)) : null
        );
    }

    // === SCOPES ===

    /**
     * Solo vettori attivi
     */
    public function scopeAttivi(Builder $query): Builder
    {
        return $query->where('attivo', true);
    }

    /**
     * Filtro per classe vettore
     */
    public function scopeClasse(Builder $query, string $classe): Builder
    {
        return $query->where('classe_vettore', $classe);
    }

    /**
     * Filtro per tipo vettore
     */
    public function scopeTipoVettore(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo_vettore', $tipo);
    }

    /**
     * Ricerca full-text enterprise
     */
    public function scopeRicerca(Builder $query, string $termine): Builder
    {
        $termine = "%{$termine}%";
        
        return $query->where(function ($q) use ($termine) {
            $q->where('ragione_sociale', 'like', $termine)
              ->orWhere('nome_commerciale', 'like', $termine)
              ->orWhere('codice_vettore', 'like', $termine)
              ->orWhere('nome', 'like', $termine)
              ->orWhere('cognome', 'like', $termine)
              ->orWhere('email', 'like', $termine)
              ->orWhere('telefono', 'like', $termine)
              ->orWhere('citta', 'like', $termine);
        });
    }

    /**
     * Filtro area geografica
     */
    public function scopeAreaGeografica(Builder $query, ?string $provincia = null, ?string $citta = null): Builder
    {
        if ($provincia) {
            $query->where('provincia', strtoupper($provincia));
        }
        if ($citta) {
            $query->where('citta', 'like', "%{$citta}%");
        }
        return $query;
    }

    /**
     * Vettori con rating minimo
     */
    public function scopeConRatingMinimo(Builder $query, float $rating): Builder
    {
        return $query->where('valutazione', '>=', $rating);
    }

    /**
     * Vettori che supportano un servizio specifico
     */
    public function scopeConServizio(Builder $query, string $servizio): Builder
    {
        return $query->whereJsonContains('servizi_offerti', $servizio);
    }

    // === BUSINESS METHODS ===

    /**
     * Verifica se il vettore è persona fisica
     */
    public function isPersonaFisica(): bool
    {
        return $this->tipo_soggetto === 'persona_fisica';
    }

    /**
     * Verifica se il vettore è ente pubblico
     */
    public function isEntePublico(): bool
    {
        return $this->tipo_soggetto === 'ente_pubblico';
    }

    /**
     * Validazione algoritmo Partita IVA italiana
     */
    public function isPartitaIvaValida(): bool
    {
        if (!$this->partita_iva || strlen($this->partita_iva) !== 11) {
            return false;
        }

        if (!ctype_digit($this->partita_iva)) {
            return false;
        }

        // Algoritmo validazione P.IVA
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 === 0) {
                $sum += (int) $this->partita_iva[$i];
            } else {
                $temp = (int) $this->partita_iva[$i] * 2;
                $sum += ($temp > 9) ? ($temp - 9) : $temp;
            }
        }

        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit == (int) $this->partita_iva[10];
    }

    /**
     * Calcola costo spedizione per peso specifico
     */
    public function calcolaCostoSpedizione(float $peso, string $servizio = 'standard'): ?float
    {
        if (!$this->costo_base_kg) return null;

        $costo = $peso * $this->costo_base_kg;

        // Applica costo minimo
        if ($this->costo_minimo_spedizione && $costo < $this->costo_minimo_spedizione) {
            $costo = $this->costo_minimo_spedizione;
        }

        // Supplementi per servizi speciali
        if ($servizio === 'express' && isset($this->supplementi['express'])) {
            $costo += $this->supplementi['express'];
        }

        return round($costo, 2);
    }

    /**
     * Verifica se spedizione è gratuita per importo
     */
    public function isSpedizioneGratuita(float $importoOrdine): bool
    {
        return $this->soglia_franco && $importoOrdine >= $this->soglia_franco;
    }

    /**
     * Aggiorna timestamp verifica dati
     */
    public function aggiornaVerifica(): void
    {
        $this->update([
            'ultima_verifica_dati' => now(),
            'verificato' => true
        ]);
    }

    /**
     * Aggiorna valutazione media
     */
    public function aggiornaValutazione(float $nuovaValutazione): void
    {
        $totaleValutazioni = $this->numero_valutazioni;
        $valutazioneAttuale = $this->valutazione ?: 0;

        $nuovaMedia = (($valutazioneAttuale * $totaleValutazioni) + $nuovaValutazione) / ($totaleValutazioni + 1);

        $this->update([
            'valutazione' => round($nuovaMedia, 2),
            'numero_valutazioni' => $totaleValutazioni + 1
        ]);
    }

    /**
     * URL tracking completo per spedizione
     */
    public function getTrackingUrl(string $numeroSpedizione): ?string
    {
        if (!$this->api_tracking_url) return null;
        
        return str_replace('{tracking}', $numeroSpedizione, $this->api_tracking_url);
    }

    // === EVENTI MODEL ===

    /**
     * Eventi del model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-genera codice vettore se non specificato
        static::creating(function ($vettore) {
            if (!$vettore->codice_vettore) {
                $vettore->codice_vettore = 'VET' . str_pad(
                    static::count() + 1, 
                    4, 
                    '0', 
                    STR_PAD_LEFT
                );
            }
        });

        // Audit trail automatico
        static::creating(function ($vettore) {
            $vettore->created_by = auth()->id();
        });

        static::updating(function ($vettore) {
            $vettore->updated_by = auth()->id();
        });
    }

    // === RELATIONSHIPS ===

    /**
     * Utente che ha creato il vettore
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Utente che ha aggiornato il vettore
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Spedizioni gestite da questo vettore
     */
    public function spedizioni()
    {
        return $this->hasMany(Spedizione::class, 'vettore_id');
    }
}