<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CalculatorChatController extends Controller
{
    public function calculate(Request $request): JsonResponse
    {
        try {
            $message = trim($request->input('message', ''));
            
            if (empty($message)) {
                return response()->json([
                    'error' => 'Messaggio vuoto. Prova a chiedere qualcosa!'
                ], 400);
            }

            // Normalizza il messaggio
            $message = strtolower($message);
            
            // === CALCOLI PERCENTUALI ===
            if ($this->isPercentageCalculation($message)) {
                return $this->calculatePercentage($message);
            }
            
            // === CALCOLI IVA ===
            if ($this->isVATCalculation($message)) {
                return $this->calculateVAT($message);
            }
            
            // === SCONTI ===
            if ($this->isDiscountCalculation($message)) {
                return $this->calculateDiscount($message);
            }
            
            // === INTERESSI SEMPLICI ===
            if ($this->isSimpleInterestCalculation($message)) {
                return $this->calculateSimpleInterest($message);
            }
            
            // === CALCOLI MATEMATICI GENERICI ===
            if ($this->isMathExpression($message)) {
                return $this->calculateMathExpression($message);
            }
            
            // === CONVERSIONI ===
            if ($this->isCurrencyConversion($message)) {
                return $this->convertCurrency($message);
            }

            // Messaggio di default se non riconosce il pattern
            return response()->json([
                'explanation' => 'Non ho capito la domanda. Prova con questi esempi:',
                'result' => '• "Calcola il 22% di 1000€"
• "Sconto del 15% su 250€"  
• "IVA 22% su 500€"
• "Interesse semplice: 1000€ al 5% per 3 anni"
• "45 + 67 * 2"
• "Converti 100€ in dollari"

💡 Tip: Scrivi in modo naturale, capisco molti modi di porre le domande!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Calculator Chat Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Errore nel calcolo. Riprova con una domanda più semplice.'
            ], 500);
        }
    }

    private function isPercentageCalculation(string $message): bool
    {
        return preg_match('/(?:calcola|quanto|dimmi).*?(\d+(?:\.\d+)?)%.*?(?:di|su).*?(\d+(?:\.\d+)?)/i', $message);
    }

    private function calculatePercentage(string $message): JsonResponse
    {
        preg_match('/(?:calcola|quanto|dimmi).*?(\d+(?:\.\d+)?)%.*?(?:di|su).*?(\d+(?:\.\d+)?)/i', $message, $matches);
        
        if (count($matches) >= 3) {
            $percentage = floatval($matches[1]);
            $number = floatval($matches[2]);
            $result = ($number * $percentage) / 100;
            
            return response()->json([
                'explanation' => "Ho calcolato il {$percentage}% di {$number}€",
                'result' => number_format($result, 2, ',', '.') . '€',
                'numeric_result' => $result
            ]);
        }
        
        return response()->json(['error' => 'Non riesco a estrarre i numeri dalla domanda'], 400);
    }

    private function isVATCalculation(string $message): bool
    {
        return preg_match('/iva|imposta|tassa|vat/i', $message) && 
               preg_match('/(\d+(?:\.\d+)?)%?.*?(\d+(?:\.\d+)?)/i', $message);
    }

    private function calculateVAT(string $message): JsonResponse
    {
        // Cerca pattern IVA con percentuale e importo
        if (preg_match('/iva.*?(\d+(?:\.\d+)?)%.*?(?:su|di).*?(\d+(?:\.\d+)?)/i', $message, $matches)) {
            $vatRate = floatval($matches[1]);
            $amount = floatval($matches[2]);
        } elseif (preg_match('/(\d+(?:\.\d+)?)%.*?iva.*?(\d+(?:\.\d+)?)/i', $message, $matches)) {
            $vatRate = floatval($matches[1]);
            $amount = floatval($matches[2]);
        } else {
            return response()->json(['error' => 'Pattern IVA non riconosciuto'], 400);
        }
        
        $vatAmount = ($amount * $vatRate) / 100;
        $totalWithVAT = $amount + $vatAmount;
        
        return response()->json([
            'explanation' => "IVA {$vatRate}% su {$amount}€",
            'result' => "IVA: " . number_format($vatAmount, 2, ',', '.') . "€\nTotale: " . number_format($totalWithVAT, 2, ',', '.') . "€",
            'numeric_result' => $totalWithVAT
        ]);
    }

    private function isDiscountCalculation(string $message): bool
    {
        return preg_match('/sconto|riduzione|ribasso/i', $message) &&
               preg_match('/(\d+(?:\.\d+)?)%.*?(?:su|di).*?(\d+(?:\.\d+)?)/i', $message);
    }

    private function calculateDiscount(string $message): JsonResponse
    {
        preg_match('/sconto.*?(\d+(?:\.\d+)?)%.*?(?:su|di).*?(\d+(?:\.\d+)?)/i', $message, $matches);
        
        if (count($matches) >= 3) {
            $discountRate = floatval($matches[1]);
            $originalPrice = floatval($matches[2]);
            $discountAmount = ($originalPrice * $discountRate) / 100;
            $finalPrice = $originalPrice - $discountAmount;
            
            return response()->json([
                'explanation' => "Sconto {$discountRate}% su {$originalPrice}€",
                'result' => "Sconto: -" . number_format($discountAmount, 2, ',', '.') . "€\nPrezzo finale: " . number_format($finalPrice, 2, ',', '.') . "€",
                'numeric_result' => $finalPrice
            ]);
        }
        
        return response()->json(['error' => 'Non riesco a calcolare lo sconto'], 400);
    }

    private function isSimpleInterestCalculation(string $message): bool
    {
        return preg_match('/interesse|rendimento/i', $message) && 
               preg_match('/(\d+(?:\.\d+)?).*?(\d+(?:\.\d+)?)%.*?(\d+(?:\.\d+)?)/i', $message);
    }

    private function calculateSimpleInterest(string $message): JsonResponse
    {
        // Pattern: "capitale al tasso% per anni"
        if (preg_match('/(\d+(?:\.\d+)?).*?(\d+(?:\.\d+)?)%.*?(\d+(?:\.\d+)?)/i', $message, $matches)) {
            $principal = floatval($matches[1]);
            $rate = floatval($matches[2]) / 100;
            $years = floatval($matches[3]);
            
            $interest = $principal * $rate * $years;
            $total = $principal + $interest;
            
            return response()->json([
                'explanation' => "Interesse semplice: {$principal}€ al " . ($rate * 100) . "% per {$years} anni",
                'result' => "Interessi: " . number_format($interest, 2, ',', '.') . "€\nTotale: " . number_format($total, 2, ',', '.') . "€",
                'numeric_result' => $total
            ]);
        }
        
        return response()->json(['error' => 'Pattern interesse non riconosciuto'], 400);
    }

    private function isMathExpression(string $message): bool
    {
        // Controlla se contiene operazioni matematiche
        return preg_match('/^[\d\s\+\-\*\/\(\)\.\,]+$/', $message) ||
               preg_match('/(\d+(?:\.\d+)?)\s*[\+\-\*\/]\s*(\d+(?:\.\d+)?)/i', $message);
    }

    private function calculateMathExpression(string $message): JsonResponse
    {
        try {
            // Pulisce l'espressione rimuovendo caratteri non numerici/operatori
            $expression = preg_replace('/[^\d\+\-\*\/\(\)\.\s]/', '', $message);
            $expression = str_replace(',', '.', $expression); // Sostituisce virgole con punti
            
            // Valuta l'espressione (ATTENZIONE: uso eval in modo sicuro)
            if (preg_match('/^[\d\s\+\-\*\/\(\)\.]+$/', $expression)) {
                $result = eval("return $expression;");
                
                return response()->json([
                    'explanation' => "Calcolo: $expression",
                    'result' => number_format($result, 2, ',', '.'),
                    'numeric_result' => $result
                ]);
            }
        } catch (\Exception $e) {
            // Non loggare l'errore per evitare spam
        }
        
        return response()->json(['error' => 'Espressione matematica non valida'], 400);
    }

    private function isCurrencyConversion(string $message): bool
    {
        return preg_match('/converti|converti|cambio|dollari|euro/i', $message);
    }

    private function convertCurrency(string $message): JsonResponse
    {
        // Conversione semplificata EUR/USD (tasso fisso per demo)
        $eurToUsd = 1.10;
        $usdToEur = 0.91;
        
        if (preg_match('/(\d+(?:\.\d+)?)\s*€.*?dollari?/i', $message, $matches)) {
            $euros = floatval($matches[1]);
            $dollars = $euros * $eurToUsd;
            
            return response()->json([
                'explanation' => "Conversione EUR → USD (tasso: $eurToUsd)",
                'result' => number_format($euros, 2, ',', '.') . "€ = $" . number_format($dollars, 2, '.', ','),
                'numeric_result' => $dollars
            ]);
        }
        
        if (preg_match('/\$?(\d+(?:\.\d+)?).*?euro/i', $message, $matches)) {
            $dollars = floatval($matches[1]);
            $euros = $dollars * $usdToEur;
            
            return response()->json([
                'explanation' => "Conversione USD → EUR (tasso: $usdToEur)",
                'result' => "$" . number_format($dollars, 2, '.', ',') . " = " . number_format($euros, 2, ',', '.') . "€",
                'numeric_result' => $euros
            ]);
        }
        
        return response()->json(['error' => 'Pattern conversione non riconosciuto'], 400);
    }
}