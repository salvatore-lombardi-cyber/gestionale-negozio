<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAssistantService
{
    public function ask($question, $context = [])
{
    try {
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

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-8b-8192',
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

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
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