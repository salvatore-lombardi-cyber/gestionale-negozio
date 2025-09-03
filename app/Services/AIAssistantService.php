<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAssistantService
{
    public function ask($question, $context = [])
    {
        try {
            // Se non c'è API key, usa la modalità offline
            if (empty(env('GROQ_API_KEY'))) {
                return $this->offlineResponse($question, $context);
            }

            // Costruisci il prompt con i dati reali
            $systemPrompt = 'Sei un assistente per gestionale negozio. Rispondi in italiano usando SOLO i dati forniti. Non inventare numeri.';
            
            $contextText = '';
            if (!empty($context)) {
                $contextText = "\n\nDATI REALI DEL GESTIONALE:\n";
                foreach ($context as $key => $value) {
                    $contextText .= "- " . ucfirst(str_replace('_', ' ', $key)) . ": " . $value . "\n";
                }
            }
            
            $fullPrompt = $question . $contextText;

            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $fullPrompt]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'response' => $data['choices'][0]['message']['content']
                ];
            }

            // Se l'API fallisce, usa la modalità offline
            Log::warning('GROQ API failed with status: ' . $response->status());
            return $this->offlineResponse($question, $context);

        } catch (\Exception $e) {
            // Se c'è un errore, usa la modalità offline
            Log::warning('GROQ API error: ' . $e->getMessage());
            return $this->offlineResponse($question, $context);
        }
    }

    /**
     * Modalità offline con risposte predefinite basate sui dati reali
     */
    private function offlineResponse($question, $context = [])
    {
        $question = strtolower($question);
        
        // Analizza le parole chiave nella domanda
        if (strpos($question, 'vendite') !== false || strpos($question, 'incasso') !== false) {
            $vendite = $context['vendite_totali'] ?? 0;
            $oggi = $context['vendite_oggi'] ?? 0;
            return [
                'success' => true,
                'response' => "📊 **Vendite**: Hai registrato **{$vendite} vendite totali** nel sistema. Oggi sono state effettuate **{$oggi} vendite**. Le vendite sembrano procedere bene!"
            ];
        }
        
        if (strpos($question, 'prodotti') !== false || strpos($question, 'magazzino') !== false) {
            $prodotti = $context['prodotti_totali'] ?? 0;
            $scorte = $context['scorte_basse'] ?? 0;
            $message = "📦 **Prodotti**: Hai **{$prodotti} prodotti** nel tuo catalogo.";
            if ($scorte > 0) {
                $message .= " Attenzione: ci sono **{$scorte} prodotti** con scorte basse da rifornire.";
            } else {
                $message .= " Le scorte sembrano sotto controllo!";
            }
            return [
                'success' => true,
                'response' => $message
            ];
        }
        
        if (strpos($question, 'clienti') !== false) {
            $clienti = $context['clienti_totali'] ?? 0;
            return [
                'success' => true,
                'response' => "👥 **Clienti**: Hai **{$clienti} clienti** registrati nel database. È importante mantenere buoni rapporti con i tuoi clienti per fidelizzarli!"
            ];
        }
        
        // Risposta generica con tutti i dati
        $prodotti = $context['prodotti_totali'] ?? 0;
        $clienti = $context['clienti_totali'] ?? 0;
        $vendite = $context['vendite_totali'] ?? 0;
        $oggi = $context['vendite_oggi'] ?? 0;
        
        return [
            'success' => true,
            'response' => "📈 **Riepilogo Gestionale**:\n\n🛍️ **Prodotti**: {$prodotti}\n👥 **Clienti**: {$clienti}\n💰 **Vendite Totali**: {$vendite}\n📅 **Vendite Oggi**: {$oggi}\n\nIl tuo gestionale sta funzionando bene! Posso aiutarti con domande specifiche su vendite, prodotti o clienti."
        ];
    }

    public function isAvailable()
    {
        return !empty(env('GROQ_API_KEY'));
    }

    public function analyzeBusinessData($type = 'general')
    {
        return $this->ask("Analizza i dati del negozio.");
    }
}