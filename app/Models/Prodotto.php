<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descrizione',
        'prezzo',
        'categoria',
        'brand',
        'codice_prodotto',
        'attivo',
        'codice_etichetta',
        'qr_code',
        'qr_code_path',
        'qr_enabled',
        'progressivo_categoria'
    ];

    // Un prodotto può avere molte righe di magazzino
    public function magazzino()
    {
        return $this->hasMany(Magazzino::class);
    }

    // Un prodotto può essere in molti dettagli vendita
    public function dettagliVendita()
    {
        return $this->hasMany(DettaglioVendita::class);
    }

    /**
     * Verifica se ha QR Code
     */
    public function hasQRCode(): bool
    {
        return !empty($this->qr_code) && 
               !empty($this->qr_code_path) && 
               \Storage::disk('public')->exists($this->qr_code_path);
    }

    /**
     * URL del QR Code
     */
    public function getQRCodeUrl(): ?string
    {
        if ($this->hasQRCode()) {
            return \Storage::disk('public')->url($this->qr_code_path);
        }
        return null;
    }

    /**
     * Verifica se ha codice etichetta
     */
    public function hasLabelCode(): bool
    {
        return !empty($this->codice_etichetta);
    }

    /**
     * Genera codice etichetta se non esiste
     */
    public function generateLabelCode(): string
    {
        if ($this->hasLabelCode()) {
            return $this->codice_etichetta;
        }

        $labelService = new \App\Services\LabelCodeService();
        return $labelService->generateProductCode($this);
    }
}