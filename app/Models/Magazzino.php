<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazzino extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodotto_id',
        'taglia',
        'colore',
        'quantita',
        'scorta_minima',
        'codice_variante',
        'variant_qr_code',
        'variant_qr_path'
    ];

    // Ogni riga di magazzino appartiene a un prodotto
    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }

    /**
     * Verifica se ha QR Code variante
     */
    public function hasVariantQR(): bool
    {
        return !empty($this->variant_qr_code) && 
               !empty($this->variant_qr_path) && 
               \Storage::disk('public')->exists($this->variant_qr_path);
    }

    /**
     * URL del QR Code variante
     */
    public function getVariantQRUrl(): ?string
    {
        if ($this->hasVariantQR()) {
            return \Storage::disk('public')->url($this->variant_qr_path);
        }
        return null;
    }

    /**
     * Verifica se ha codice variante
     */
    public function hasVariantCode(): bool
    {
        return !empty($this->codice_variante);
    }

    /**
     * Genera codice variante se non esiste
     */
    public function generateVariantCode(): string
    {
        if ($this->hasVariantCode()) {
            return $this->codice_variante;
        }

        $labelService = new \App\Services\LabelCodeService();
        return $labelService->generateVariantCode($this);
    }

    /**
     * Genera descrizione completa variante
     */
    public function getFullDescription(): string
    {
        return $this->prodotto->nome . ' - ' . $this->taglia . ' - ' . $this->colore;
    }
}