<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Storage;
use App\Models\Prodotto;
use App\Models\Magazzino;

class QRCodeService
{
    private $storeId;
    private $logoPath;
    
    public function __construct()
    {
        $this->storeId = config('app.store_id', 'STORE001');
        $this->logoPath = public_path('images/logo-small.png');
    }
    
    /**
     * Genera QR Code per prodotto principale
     */
    public function generateProductQR(Prodotto $prodotto): string
    {
        // Usa il codice etichetta se esiste, altrimenti usa codice prodotto
        $code = $prodotto->codice_etichetta ?? $prodotto->codice_prodotto;
        $qrData = $this->buildQRData($code);
        
        // Sintassi corretta per versione 6.0.9 con named parameters
        $qrCode = new QrCode(
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $filename = "qr_codes/products/{$prodotto->codice_prodotto}.png";
        Storage::disk('public')->put($filename, $result->getString());
        
        // Aggiorna database
        $prodotto->update([
            'qr_code' => $qrData,
            'qr_code_path' => $filename
        ]);
        
        return $filename;
    }
    
    /**
     * Genera QR Code per variante (taglia/colore)
     */
    public function generateVariantQR(Magazzino $magazzino): string
    {
        $qrData = $magazzino->codice_variante;
        
        // Sintassi corretta per versione 6.0.9 con named parameters
        $qrCode = new QrCode(
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 250,
            margin: 8,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $filename = "qr_codes/variants/{$magazzino->codice_variante}.png";
        Storage::disk('public')->put($filename, $result->getString());
        
        // Aggiorna database
        $magazzino->update([
            'variant_qr_code' => $qrData,
            'variant_qr_path' => $filename
        ]);
        
        return $filename;
    }
    
    /**
     * Costruisce i dati del QR Code
     */
    private function buildQRData(string $code): string
    {
        return $this->storeId . '-' . $code;
    }
    
    /**
     * Decodifica QR Code
     */
    public function decodeQR(string $qrData): array
    {
        $parts = explode('-', $qrData);
        
        return [
            'store_id' => $parts[0] ?? null,
            'product_code' => $parts[1] ?? null,
            'size' => $parts[2] ?? null,
            'color' => $parts[3] ?? null,
            'full_code' => $qrData
        ];
    }
    
    /**
     * Trova prodotto da QR Code
     */
    public function findProductByQR(string $qrData): ?Prodotto
    {
        $decoded = $this->decodeQR($qrData);
        
        // Cerca prima per codice etichetta, poi per codice prodotto
        return Prodotto::where('codice_etichetta', $decoded['full_code'])
            ->orWhere('codice_prodotto', $decoded['product_code'])
            ->first();
    }
    
    /**
     * Trova variante da QR Code
     */
    public function findVariantByQR(string $qrData): ?Magazzino
    {
        return Magazzino::where('codice_variante', $qrData)->first();
    }
    
    /**
     * Verifica se QR Code esiste
     */
    public function qrCodeExists(Prodotto $prodotto): bool
    {
        return !empty($prodotto->qr_code) && 
               !empty($prodotto->qr_code_path) && 
               Storage::disk('public')->exists($prodotto->qr_code_path);
    }
    
    /**
     * Verifica se QR Code variante esiste
     */
    public function variantQRExists(Magazzino $magazzino): bool
    {
        return !empty($magazzino->variant_qr_code) && 
               !empty($magazzino->variant_qr_path) && 
               Storage::disk('public')->exists($magazzino->variant_qr_path);
    }
}