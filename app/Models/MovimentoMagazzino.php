<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MovimentoMagazzino extends Model
{
    use HasFactory;

    protected $table = 'movimenti_magazzino';

    protected $fillable = [
        'uuid',
        'prodotto_id',
        'deposito_id',
        'causale_id',
        'tipo_movimento',
        'quantita',
        'data_movimento',
        'riferimento',
        'deposito_sorgente_id',
        'deposito_destinazione_id',
        'movimento_collegato_uuid',
        'cliente_id',
        'fornitore_id',
        'user_id',
        'data_registrazione',
        'note'
    ];

    protected $casts = [
        'data_movimento' => 'date',
        'data_registrazione' => 'datetime',
        'quantita' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Genera UUID automaticamente
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });

        // Aggiorna giacenze dopo creazione movimento
        static::created(function ($movimento) {
            $movimento->aggiornaGiacenze();
        });
    }

    /**
     * Relazioni
     */
    public function prodotto()
    {
        return $this->belongsTo(Anagrafica::class, 'prodotto_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    public function causale()
    {
        return $this->belongsTo(CausaleMagazzino::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Anagrafica::class, 'cliente_id');
    }

    public function fornitore()
    {
        return $this->belongsTo(Anagrafica::class, 'fornitore_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depositoSorgente()
    {
        return $this->belongsTo(Deposito::class, 'deposito_sorgente_id');
    }

    public function depositoDestinazione()
    {
        return $this->belongsTo(Deposito::class, 'deposito_destinazione_id');
    }

    /**
     * Scopes
     */
    public function scopeCarichi($query)
    {
        return $query->where('tipo_movimento', 'carico');
    }

    public function scopeScarichi($query)
    {
        return $query->where('tipo_movimento', 'scarico');
    }

    public function scopeTrasferimenti($query)
    {
        return $query->whereIn('tipo_movimento', ['trasferimento_uscita', 'trasferimento_ingresso']);
    }

    public function scopePerDeposito($query, $depositoId)
    {
        return $query->where('deposito_id', $depositoId);
    }

    public function scopePerProdotto($query, $prodottoId)
    {
        return $query->where('prodotto_id', $prodottoId);
    }

    public function scopePerPeriodo($query, $dataInizio, $dataFine = null)
    {
        $query->where('data_movimento', '>=', $dataInizio);
        if ($dataFine) {
            $query->where('data_movimento', '<=', $dataFine);
        }
        return $query;
    }

    /**
     * Metodi di business
     */
    
    /**
     * Crea movimento di carico
     */
    public static function creaCarico($prodottoId, $depositoId, $quantita, $causaleId, $dataMovimento, $options = [])
    {
        return self::create([
            'prodotto_id' => $prodottoId,
            'deposito_id' => $depositoId,
            'causale_id' => $causaleId,
            'tipo_movimento' => 'carico',
            'quantita' => $quantita,
            'data_movimento' => $dataMovimento,
            'riferimento' => $options['riferimento'] ?? null,
            'fornitore_id' => $options['fornitore_id'] ?? null,
            'user_id' => auth()->id(),
            'note' => $options['note'] ?? null
        ]);
    }

    /**
     * Crea movimento di scarico
     */
    public static function creaScarico($prodottoId, $depositoId, $quantita, $causaleId, $dataMovimento, $options = [])
    {
        return self::create([
            'prodotto_id' => $prodottoId,
            'deposito_id' => $depositoId,
            'causale_id' => $causaleId,
            'tipo_movimento' => 'scarico',
            'quantita' => $quantita,
            'data_movimento' => $dataMovimento,
            'riferimento' => $options['riferimento'] ?? null,
            'cliente_id' => $options['cliente_id'] ?? null,
            'user_id' => auth()->id(),
            'note' => $options['note'] ?? null
        ]);
    }

    /**
     * Crea trasferimento tra depositi (genera 2 movimenti collegati)
     */
    public static function creaTrasferimento($prodottoId, $depositoSorgenteId, $depositoDestinazioneId, 
                                          $quantita, $causaleId, $dataMovimento, $options = [])
    {
        $uuidCollegamento = Str::uuid();

        // Movimento di uscita dal deposito sorgente
        $movimentoUscita = self::create([
            'prodotto_id' => $prodottoId,
            'deposito_id' => $depositoSorgenteId,
            'causale_id' => $causaleId,
            'tipo_movimento' => 'trasferimento_uscita',
            'quantita' => $quantita,
            'data_movimento' => $dataMovimento,
            'deposito_sorgente_id' => $depositoSorgenteId,
            'deposito_destinazione_id' => $depositoDestinazioneId,
            'movimento_collegato_uuid' => $uuidCollegamento,
            'riferimento' => $options['riferimento'] ?? null,
            'user_id' => auth()->id(),
            'note' => $options['note'] ?? null
        ]);

        // Movimento di ingresso nel deposito destinazione
        $movimentoIngresso = self::create([
            'prodotto_id' => $prodottoId,
            'deposito_id' => $depositoDestinazioneId,
            'causale_id' => $causaleId,
            'tipo_movimento' => 'trasferimento_ingresso',
            'quantita' => $quantita,
            'data_movimento' => $dataMovimento,
            'deposito_sorgente_id' => $depositoSorgenteId,
            'deposito_destinazione_id' => $depositoDestinazioneId,
            'movimento_collegato_uuid' => $uuidCollegamento,
            'riferimento' => $options['riferimento'] ?? null,
            'user_id' => auth()->id(),
            'note' => $options['note'] ?? null
        ]);

        return [$movimentoUscita, $movimentoIngresso];
    }

    /**
     * Aggiorna giacenze in tempo reale
     */
    public function aggiornaGiacenze()
    {
        $giacenza = GiacenzaMagazzino::firstOrCreate([
            'prodotto_id' => $this->prodotto_id,
            'deposito_id' => $this->deposito_id
        ], [
            'quantita_attuale' => 0
        ]);

        // Calcola variazione quantità
        $variazione = 0;
        switch ($this->tipo_movimento) {
            case 'carico':
            case 'trasferimento_ingresso':
                $variazione = $this->quantita;
                break;
            case 'scarico':
            case 'trasferimento_uscita':
                $variazione = -$this->quantita;
                break;
        }

        // Aggiorna giacenza
        $giacenza->quantita_attuale += $variazione;
        $giacenza->ultimo_aggiornamento = now();
        $giacenza->save();
    }

    /**
     * Verifica se il movimento è modificabile
     */
    public function isModificabile()
    {
        // Logica di business: movimenti oltre X giorni non modificabili
        return $this->created_at->diffInDays(now()) <= 7;
    }

    /**
     * Verifica disponibilità per scarico
     */
    public static function verificaDisponibilita($prodottoId, $depositoId, $quantitaRichiesta)
    {
        $giacenza = GiacenzaMagazzino::where('prodotto_id', $prodottoId)
            ->where('deposito_id', $depositoId)
            ->first();

        return $giacenza && $giacenza->quantita_attuale >= $quantitaRichiesta;
    }

    /**
     * Ottieni movimenti collegati (per trasferimenti)
     */
    public function movimentiCollegati()
    {
        if (!$this->movimento_collegato_uuid) {
            return collect();
        }

        return self::where('movimento_collegato_uuid', $this->movimento_collegato_uuid)
            ->where('id', '!=', $this->id)
            ->get();
    }

    /**
     * Attributi calcolati
     */
    public function getEffettoGiacenzeAttribute()
    {
        switch ($this->tipo_movimento) {
            case 'carico':
            case 'trasferimento_ingresso':
                return '+';
            case 'scarico':
            case 'trasferimento_uscita':
                return '-';
            default:
                return '=';
        }
    }

    public function getQuantitaFormattataAttribute()
    {
        return $this->effetto_giacenze . number_format($this->quantita, 3);
    }
}