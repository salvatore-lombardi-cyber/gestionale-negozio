<?php

namespace App\Services;

use App\Models\Prodotto;
use App\Models\Magazzino;
use Illuminate\Support\Facades\DB;

class LabelCodeService
{
    /**
     * Genera codice etichetta per prodotto
     * Formato: MAG001, CAM002, PAN003, etc.
     */
    public function generateProductCode(Prodotto $prodotto): string
    {
        // Se ha già un codice, lo restituisce
        if (!empty($prodotto->codice_etichetta)) {
            return $prodotto->codice_etichetta;
        }

        $categoryPrefix = $this->getCategoryPrefix($prodotto->categoria);
        $progressivo = $this->getNextProgressive($prodotto->categoria);
        
        $codiceEtichetta = $categoryPrefix . str_pad($progressivo, 3, '0', STR_PAD_LEFT);
        
        // Aggiorna il prodotto
        $prodotto->update([
            'codice_etichetta' => $codiceEtichetta,
            'progressivo_categoria' => $progressivo
        ]);
        
        return $codiceEtichetta;
    }

    /**
     * Genera codice variante completo
     * Formato: MAG001-L-ROSSO
     */
    public function generateVariantCode(Magazzino $magazzino): string
    {
        // Se ha già un codice, lo restituisce
        if (!empty($magazzino->codice_variante)) {
            return $magazzino->codice_variante;
        }

        $prodotto = $magazzino->prodotto;
        
        // Assicura che il prodotto abbia un codice etichetta
        $productCode = $this->generateProductCode($prodotto);
        
        // Normalizza taglia e colore
        $taglia = $this->normalizeTaglia($magazzino->taglia);
        $colore = $this->normalizeColore($magazzino->colore);
        
        $codiceVariante = $productCode . '-' . $taglia . '-' . $colore;
        
        // Aggiorna il magazzino
        $magazzino->update([
            'codice_variante' => $codiceVariante
        ]);
        
        return $codiceVariante;
    }

    /**
     * Ottiene il prefisso della categoria (3 lettere)
     */
    private function getCategoryPrefix(string $categoria): string
    {
        $prefixes = [
            'magliette' => 'MAG',
            'camicie' => 'CAM', 
            'pantaloni' => 'PAN',
            'giacche' => 'GIA',
            'cappotti' => 'CAP',
            'gonne' => 'GON',
            'vestiti' => 'VES',
            'felpe' => 'FEL',
            'jeans' => 'JEA',
            'scarpe' => 'SCA',
            'accessori' => 'ACC',
            'intimo' => 'INT',
            'costumi' => 'COS',
            'borse' => 'BOR',
            'cinture' => 'CIN'
        ];
        
        $categoriaLower = strtolower($categoria);
        
        return $prefixes[$categoriaLower] ?? 'GEN'; // GEN = Generico
    }

    /**
     * Ottiene il prossimo numero progressivo per la categoria
     */
    private function getNextProgressive(string $categoria): int
    {
        $maxProgressivo = Prodotto::where('categoria', $categoria)
            ->whereNotNull('progressivo_categoria')
            ->max('progressivo_categoria');
            
        return ($maxProgressivo ?? 0) + 1;
    }

    /**
     * Normalizza la taglia (maiuscole, senza spazi)
     */
    private function normalizeTaglia(string $taglia): string
    {
        return strtoupper(trim($taglia));
    }

    /**
     * Normalizza il colore (maiuscole, senza spazi)
     */
    private function normalizeColore(string $colore): string
    {
        return strtoupper(trim($colore));
    }

    /**
     * Trova prodotto da codice etichetta
     */
    public function findProductByCode(string $code): ?Prodotto
    {
        return Prodotto::where('codice_etichetta', $code)->first();
    }

    /**
     * Trova variante da codice completo
     */
    public function findVariantByCode(string $code): ?Magazzino
    {
        return Magazzino::where('codice_variante', $code)->first();
    }

    /**
     * Decodifica un codice variante
     * Esempio: MAG001-L-ROSSO -> [product: MAG001, size: L, color: ROSSO]
     */
    public function decodeVariantCode(string $code): array
    {
        $parts = explode('-', $code);
        
        return [
            'product_code' => $parts[0] ?? null,
            'size' => $parts[1] ?? null,
            'color' => $parts[2] ?? null,
            'is_variant' => count($parts) >= 3
        ];
    }

    /**
     * Genera tutti i codici per un prodotto e le sue varianti
     */
    public function generateAllCodes(Prodotto $prodotto): array
    {
        $results = [];
        
        // Codice prodotto
        $results['product_code'] = $this->generateProductCode($prodotto);
        
        // Codici varianti
        $results['variants'] = [];
        
        foreach ($prodotto->magazzino as $magazzino) {
            $variantCode = $this->generateVariantCode($magazzino);
            $results['variants'][] = [
                'id' => $magazzino->id,
                'taglia' => $magazzino->taglia,
                'colore' => $magazzino->colore,
                'code' => $variantCode
            ];
        }
        
        return $results;
    }

    /**
     * Verifica se un codice esiste già
     */
    public function codeExists(string $code): bool
    {
        $productExists = Prodotto::where('codice_etichetta', $code)->exists();
        $variantExists = Magazzino::where('codice_variante', $code)->exists();
        
        return $productExists || $variantExists;
    }
}