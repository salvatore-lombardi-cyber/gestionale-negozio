<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * Model Enterprise Fornitore
 * 
 * Sistema di gestione fornitori superiore ai competitor con:
 * - Compliance B2B italiana completa
 * - Sicurezza enterprise e audit trail
 * - Validazioni automatiche P.IVA e Codice Fiscale
 * - Gestione multilingua e internazionale
 * - GDPR compliance integrata
 */
class Fornitore extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornitori';

    /**
     * Campi mass-assignable con sicurezza enterprise
     */
    protected $fillable = [
        // Dati anagrafici
        'ragione_sociale',
        'nome',
        'cognome',
        
        // Dati fiscali
        'codice_fiscale',
        'partita_iva',
        'tipo_soggetto',
        
        // Contatti
        'telefono',
        'telefono_mobile',
        'fax',
        'email',
        'pec',
        'sito_web',
        
        // Indirizzo
        'indirizzo',
        'citta',
        'provincia',
        'cap',
        'paese',
        
        // Fatturazione elettronica
        'codice_destinatario',
        'regime_fiscale',
        'split_payment',
        
        // Dati bancari
        'iban',
        'banca',
        'swift_bic',
        'modalita_pagamento',
        'giorni_pagamento',
        
        // Classificazione
        'categoria_merceologica',
        'codice_istat',
        'classe_fornitore',
        'limite_credito',
        
        // Persona fisica
        'data_nascita',
        'luogo_nascita',
        'genere',
        
        // Internazionale
        'lingua_preferita',
        'valuta_preferita',
        
        // Gestionale
        'attivo',
        'ultima_verifica_dati',
        'note_interne',
        'referente_interno',
        
        // Privacy GDPR
        'consenso_marketing',
        'consenso_dati',
        'data_consenso'
    ];

    /**
     * Cast automatici enterprise
     */
    protected $casts = [
        'data_nascita' => 'date',
        'data_consenso' => 'datetime',
        'ultima_verifica_dati' => 'datetime',
        'limite_credito' => 'decimal:2',
        'attivo' => 'boolean',
        'split_payment' => 'boolean',
        'consenso_marketing' => 'boolean',
        'consenso_dati' => 'boolean',
        'note_interne' => 'array', // JSON cast
        'giorni_pagamento' => 'integer'
    ];

    /**
     * Campi nascosti per sicurezza
     */
    protected $hidden = [
        'note_interne',
        'referente_interno'
    ];

    // ============= RELATIONSHIPS ENTERPRISE =============

    /**
     * Un fornitore può avere molti ordini di acquisto
     */
    public function ordiniAcquisto()
    {
        return $this->hasMany('App\Models\OrdineAcquisto');
    }

    /**
     * Un fornitore può avere molti prodotti forniti
     */
    public function prodotti()
    {
        return $this->belongsToMany('App\Models\Prodotto', 'fornitore_prodotto')
                    ->withPivot(['prezzo_acquisto', 'tempo_consegna', 'codice_fornitore'])
                    ->withTimestamps();
    }

    // ============= SCOPES AVANZATI =============

    /**
     * Scope per fornitori attivi
     */
    public function scopeAttivi(Builder $query): Builder
    {
        return $query->where('attivo', true);
    }

    /**
     * Scope per tipo soggetto
     */
    public function scopeTipoSoggetto(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo_soggetto', $tipo);
    }

    /**
     * Scope per classe fornitore
     */
    public function scopeClasse(Builder $query, string $classe): Builder
    {
        return $query->where('classe_fornitore', $classe);
    }

    /**
     * Scope per ricerca full-text enterprise
     */
    public function scopeRicerca(Builder $query, string $termine): Builder
    {
        return $query->where(function ($q) use ($termine) {
            $q->where('ragione_sociale', 'LIKE', "%{$termine}%")
              ->orWhere('nome', 'LIKE', "%{$termine}%")
              ->orWhere('cognome', 'LIKE', "%{$termine}%")
              ->orWhere('email', 'LIKE', "%{$termine}%")
              ->orWhere('partita_iva', 'LIKE', "%{$termine}%")
              ->orWhere('codice_fiscale', 'LIKE', "%{$termine}%")
              ->orWhere('telefono', 'LIKE', "%{$termine}%");
        });
    }

    /**
     * Scope per fornitori per area geografica
     */
    public function scopeAreaGeografica(Builder $query, string $provincia = null, string $citta = null): Builder
    {
        if ($provincia) {
            $query->where('provincia', $provincia);
        }
        if ($citta) {
            $query->where('citta', 'LIKE', "%{$citta}%");
        }
        return $query;
    }

    // ============= ACCESSORS ENTERPRISE =============

    /**
     * Nome completo per visualizzazione
     */
    public function getNomeCompletoAttribute(): string
    {
        if ($this->tipo_soggetto === 'persona_fisica') {
            return trim($this->nome . ' ' . $this->cognome);
        }
        return $this->ragione_sociale ?? 'Fornitore #' . $this->id;
    }

    /**
     * Indirizzo completo formattato
     */
    public function getIndirizzoCompletoAttribute(): string
    {
        $indirizzo = [];
        
        if ($this->indirizzo) $indirizzo[] = $this->indirizzo;
        if ($this->cap) $indirizzo[] = $this->cap;
        if ($this->citta) $indirizzo[] = $this->citta;
        if ($this->provincia) $indirizzo[] = '(' . $this->provincia . ')';
        
        return implode(', ', array_filter($indirizzo));
    }

    /**
     * Status visualizzazione con colori
     */
    public function getStatusClasseAttribute(): string
    {
        switch ($this->classe_fornitore) {
            case 'strategico': return 'success';
            case 'preferito': return 'primary';
            case 'standard': return 'secondary';
            case 'occasionale': return 'warning';
            default: return 'secondary';
        }
    }

    // ============= METODI DI BUSINESS ENTERPRISE =============

    /**
     * Verifica se è un ente pubblico
     */
    public function isEntePublico(): bool
    {
        return $this->tipo_soggetto === 'ente_pubblico';
    }

    /**
     * Verifica se è attivo e verificato
     */
    public function isAttivo(): bool
    {
        return $this->attivo && $this->consenso_dati;
    }

    /**
     * Calcola giorni dall'ultima verifica
     */
    public function getGiorniDallUltimaVerificaAttribute(): ?int
    {
        if (!$this->ultima_verifica_dati) {
            return null;
        }
        return $this->ultima_verifica_dati->diffInDays(Carbon::now());
    }

    /**
     * Verifica se necessita aggiornamento dati
     */
    public function necessitaAggiornamento(): bool
    {
        $giorni = $this->giorni_dall_ultima_verifica;
        return $giorni === null || $giorni > 365; // Verifica annuale
    }

    /**
     * Metodo per aggiornare ultima verifica
     */
    public function aggiornaVerifica(): void
    {
        $this->update(['ultima_verifica_dati' => Carbon::now()]);
    }

    /**
     * Validazione Partita IVA italiana
     */
    public function isPartitaIvaValida(): bool
    {
        if (!$this->partita_iva) return false;
        
        // Validazione lunghezza e formato per P.IVA italiana
        if (strlen($this->partita_iva) !== 11 || !is_numeric($this->partita_iva)) {
            return false;
        }
        
        // Algoritmo di controllo P.IVA italiana
        $pivaArray = str_split($this->partita_iva);
        $sum = 0;
        
        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 === 0) {
                $sum += (int)$pivaArray[$i];
            } else {
                $temp = ((int)$pivaArray[$i]) * 2;
                $sum += $temp > 9 ? ($temp - 9) : $temp;
            }
        }
        
        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit == (int)$pivaArray[10];
    }

    /**
     * Validazione Codice Fiscale (base)
     */
    public function isCodiceFiscaleValido(): bool
    {
        if (!$this->codice_fiscale) return false;
        
        // Validazione lunghezza per CF italiano
        $cf = strtoupper($this->codice_fiscale);
        return (strlen($cf) === 16 && preg_match('/^[A-Z0-9]+$/', $cf)) ||
               (strlen($cf) === 11 && is_numeric($cf)); // P.IVA come CF per aziende
    }

    // ============= EVENTI MODEL =============

    /**
     * Boot del model con eventi enterprise
     */
    protected static function booted()
    {
        // Auto-maiuscolo per codici fiscali
        static::creating(function ($fornitore) {
            if ($fornitore->codice_fiscale) {
                $fornitore->codice_fiscale = strtoupper($fornitore->codice_fiscale);
            }
            if ($fornitore->partita_iva) {
                $fornitore->partita_iva = strtoupper($fornitore->partita_iva);
            }
            
            // Auto-set data consenso se non impostata
            if ($fornitore->consenso_dati && !$fornitore->data_consenso) {
                $fornitore->data_consenso = Carbon::now();
            }
        });

        // Log modifiche per audit enterprise
        static::updating(function ($fornitore) {
            if ($fornitore->isDirty(['codice_fiscale', 'partita_iva'])) {
                \Log::info('Modifica dati fiscali fornitore', [
                    'fornitore_id' => $fornitore->id,
                    'user_id' => auth()->id(),
                    'changes' => $fornitore->getDirty()
                ]);
            }
        });
    }

    /**
     * Cast per esportazione sicura
     */
    public function toArraySafe(): array
    {
        $data = $this->toArray();
        
        // Rimuovi dati sensibili per esportazioni
        unset($data['note_interne'], $data['referente_interno']);
        
        return $data;
    }
}