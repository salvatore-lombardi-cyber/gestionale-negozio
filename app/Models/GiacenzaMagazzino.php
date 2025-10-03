<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiacenzaMagazzino extends Model
{
    use HasFactory;

    protected $table = 'giacenze_magazzino';

    protected $fillable = [
        'prodotto_id',
        'deposito_id',
        'quantita_attuale',
        'giacenza_minima',
        'giacenza_massima',
        'ultimo_aggiornamento'
    ];

    protected $casts = [
        'quantita_attuale' => 'decimal:3',
        'giacenza_minima' => 'decimal:3',
        'giacenza_massima' => 'decimal:3',
        'ultimo_aggiornamento' => 'datetime'
    ];

    /**
     * Relazioni
     */
    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    /**
     * Scopes
     */
    public function scopeConGiacenza($query)
    {
        return $query->where('quantita_attuale', '>', 0);
    }

    public function scopeSottoScorta($query)
    {
        return $query->whereColumn('quantita_attuale', '<', 'giacenza_minima')
                    ->whereNotNull('giacenza_minima');
    }

    public function scopeSovraScorta($query)
    {
        return $query->whereColumn('quantita_attuale', '>', 'giacenza_massima')
                    ->whereNotNull('giacenza_massima');
    }

    public function scopePerDeposito($query, $depositoId)
    {
        return $query->where('deposito_id', $depositoId);
    }

    public function scopePerProdotto($query, $prodottoId)
    {
        return $query->where('prodotto_id', $prodottoId);
    }

    /**
     * Metodi di business
     */
    public function getStatoScortaAttribute()
    {
        if ($this->giacenza_minima && $this->quantita_attuale < $this->giacenza_minima) {
            return 'sottoscorta';
        }
        
        if ($this->giacenza_massima && $this->quantita_attuale > $this->giacenza_massima) {
            return 'sovrascorta';
        }
        
        return 'normale';
    }

    public function getValoreGiacenzaAttribute()
    {
        if (!$this->prodotto) {
            return 0;
        }
        
        return $this->quantita_attuale * ($this->prodotto->prezzo_costo ?? 0);
    }

    public function getQuantitaFormattataAttribute()
    {
        $numero = number_format($this->quantita_attuale, 3);
        $unitaMisura = $this->prodotto->unita_misura ?? 'PZ';
        
        return $numero . ' ' . $unitaMisura;
    }

    public function isDisponibile($quantitaRichiesta = 1)
    {
        return $this->quantita_attuale >= $quantitaRichiesta;
    }

    public function getMovimentiRecenti($giorni = 30)
    {
        return MovimentoMagazzino::where('prodotto_id', $this->prodotto_id)
            ->where('deposito_id', $this->deposito_id)
            ->where('data_movimento', '>=', now()->subDays($giorni))
            ->orderBy('data_movimento', 'desc')
            ->get();
    }

    /**
     * Calcola giacenza storica a una data specifica
     */
    public function calcolaGiacenzaStorica($dataRiferimento)
    {
        $movimenti = MovimentoMagazzino::where('prodotto_id', $this->prodotto_id)
            ->where('deposito_id', $this->deposito_id)
            ->where('data_movimento', '<=', $dataRiferimento)
            ->get();

        $giacenza = 0;
        foreach ($movimenti as $movimento) {
            switch ($movimento->tipo_movimento) {
                case 'carico':
                case 'trasferimento_ingresso':
                    $giacenza += $movimento->quantita;
                    break;
                case 'scarico':
                case 'trasferimento_uscita':
                    $giacenza -= $movimento->quantita;
                    break;
            }
        }

        return $giacenza;
    }

    /**
     * Metodi statici di utilità
     */
    public static function getGiacenzeTotali()
    {
        return self::selectRaw('
            SUM(quantita_attuale) as quantita_totale,
            COUNT(*) as articoli_totali,
            COUNT(CASE WHEN quantita_attuale > 0 THEN 1 END) as articoli_disponibili,
            COUNT(CASE WHEN quantita_attuale < giacenza_minima AND giacenza_minima IS NOT NULL THEN 1 END) as articoli_sottoscorta
        ')->first();
    }

    public static function getValoreTotale()
    {
        return self::join('prodottos', 'giacenze_magazzino.prodotto_id', '=', 'prodottos.id')
            ->selectRaw('SUM(giacenze_magazzino.quantita_attuale * prodottos.prezzo_costo) as valore_totale')
            ->value('valore_totale') ?? 0;
    }
}