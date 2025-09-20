<?php

namespace App\Services\GestioneTabelle\Validazione;

use App\Services\GestioneTabelle\Strategie\StrategiaTabella;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * Service enterprise per validazione dati tabelle di configurazione
 * Centralizza logiche di validazione con regole dinamiche
 */
class ValidazioneTabelle
{
    private array $strategieTabelle = [];
    private array $regoleGlobali = [];

    public function __construct()
    {
        $this->inizializzaRegoleGlobali();
    }

    /**
     * Registra strategia per validazione specifica
     */
    public function registraStrategia(string $nomeTabella, StrategiaTabella $strategia): void
    {
        $this->strategieTabelle[$nomeTabella] = $strategia;
    }

    /**
     * Valida dati per una tabella specifica
     */
    public function valida(string $nomeTabella, array $dati, ?int $id = null): array
    {
        try {
            $strategia = $this->ottieniStrategia($nomeTabella);
            
            // Ottieni regole di validazione
            $regole = $this->costruisciRegoleComplete($strategia, $id);
            $messaggi = $this->costruisciMessaggiCompleti($strategia);
            $attributi = $this->ottieniAttributiPersonalizzati($strategia);
            
            // Applica pre-validazioni personalizzate
            $datiPreparati = $this->applicaPreValidazioni($nomeTabella, $dati);
            
            // Esegui validazione principale
            $validator = Validator::make($datiPreparati, $regole, $messaggi, $attributi);
            
            // Validazioni personalizzate della strategia
            $this->applicaValidazioniPersonalizzate($validator, $strategia, $datiPreparati, $id);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            // Post-validazioni
            $datiValidati = $this->applicaPostValidazioni($nomeTabella, $validator->validated());
            
            Log::info("Validazione completata per {$nomeTabella}", [
                'campi_validati' => array_keys($datiValidati)
            ]);
            
            return $datiValidati;
            
        } catch (ValidationException $e) {
            Log::warning("Errori validazione per {$nomeTabella}", [
                'errori' => $e->errors()
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            Log::error("Errore sistema validazione {$nomeTabella}", [
                'errore' => $e->getMessage()
            ]);
            throw new \RuntimeException("Errore interno validazione: " . $e->getMessage());
        }
    }

    /**
     * Valida singolo campo
     */
    public function validaCampo(string $nomeTabella, string $nomeCampo, $valore, ?int $id = null): array
    {
        $strategia = $this->ottieniStrategia($nomeTabella);
        $regoleTabella = $strategia->ottieniRegoleValidazione($id);
        
        if (!isset($regoleTabella[$nomeCampo])) {
            return ['valido' => true, 'valore' => $valore];
        }
        
        $regola = $regoleTabella[$nomeCampo];
        $messaggi = $strategia->ottieniMessaggiValidazione();
        
        $validator = Validator::make(
            [$nomeCampo => $valore],
            [$nomeCampo => $regola],
            $messaggi
        );
        
        if ($validator->fails()) {
            return [
                'valido' => false,
                'errori' => $validator->errors()->get($nomeCampo)
            ];
        }
        
        return [
            'valido' => true,
            'valore' => $validator->validated()[$nomeCampo]
        ];
    }

    /**
     * Verifica unicità dinamica
     */
    public function verificaUnicita(string $nomeTabella, string $campo, $valore, ?int $excludeId = null): bool
    {
        try {
            $strategia = $this->ottieniStrategia($nomeTabella);
            
            // Usa metodo della strategia se disponibile
            if (method_exists($strategia, 'verificaUnicita')) {
                return $strategia->verificaUnicita($campo, $valore, $excludeId);
            }
            
            // Verifica generica
            $model = $strategia->creaModel();
            $query = $model->where($campo, $valore);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            return !$query->exists();
            
        } catch (\Exception $e) {
            Log::error("Errore verifica unicità {$nomeTabella}.{$campo}", [
                'errore' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Ottiene regole di validazione per form dinamico
     */
    public function ottieniRegoleForm(string $nomeTabella, ?int $id = null): array
    {
        $strategia = $this->ottieniStrategia($nomeTabella);
        $regole = $strategia->ottieniRegoleValidazione($id);
        
        return [
            'regole' => $regole,
            'messaggi' => $strategia->ottieniMessaggiValidazione(),
            'attributi' => $this->ottieniAttributiPersonalizzati($strategia)
        ];
    }

    /**
     * Valida array di elementi per operazioni bulk
     */
    public function validaBulk(string $nomeTabella, array $arrayDati): array
    {
        $risultati = [];
        $erroriGlobali = [];
        
        foreach ($arrayDati as $indice => $dati) {
            try {
                $risultati[] = $this->valida($nomeTabella, $dati);
            } catch (ValidationException $e) {
                $erroriGlobali["elemento_{$indice}"] = $e->errors();
            }
        }
        
        if (!empty($erroriGlobali)) {
            throw ValidationException::withMessages($erroriGlobali);
        }
        
        return $risultati;
    }

    /**
     * Costruisce regole complete con regole globali
     */
    private function costruisciRegoleComplete(StrategiaTabella $strategia, ?int $id): array
    {
        $regoleTabella = $strategia->ottieniRegoleValidazione($id);
        $regoleComplete = [];
        
        foreach ($regoleTabella as $campo => $regola) {
            $regoleComplete[$campo] = $this->applicaRegoleGlobali($campo, $regola);
        }
        
        return $regoleComplete;
    }

    /**
     * Costruisce messaggi completi
     */
    private function costruisciMessaggiCompleti(StrategiaTabella $strategia): array
    {
        $messaggiTabella = $strategia->ottieniMessaggiValidazione();
        return array_merge($this->ottieniMessaggiGlobali(), $messaggiTabella);
    }

    /**
     * Applica regole globali a un campo
     */
    private function applicaRegoleGlobali(string $campo, string $regola): string
    {
        $regoleArray = explode('|', $regola);
        
        // Aggiungi regole di sicurezza globali
        if (in_array($campo, ['email', 'website', 'url'])) {
            if (!in_array('filter:FILTER_SANITIZE_URL', $regoleArray)) {
                $regoleArray[] = 'filter:FILTER_SANITIZE_URL';
            }
        }
        
        if (str_contains($campo, 'password')) {
            if (!in_array('min:8', $regoleArray)) {
                $regoleArray[] = 'min:8';
            }
        }
        
        return implode('|', $regoleArray);
    }

    /**
     * Pre-validazioni personalizzate
     */
    private function applicaPreValidazioni(string $nomeTabella, array $dati): array
    {
        // Sanitizzazione base
        array_walk_recursive($dati, function(&$item) {
            if (is_string($item)) {
                $item = trim($item);
                // Rimuovi caratteri potenzialmente pericolosi
                $item = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $item);
            }
        });
        
        // Applica normalizzazioni specifiche per tabella
        return $this->applicaNormalizzazioni($nomeTabella, $dati);
    }

    /**
     * Post-validazioni
     */
    private function applicaPostValidazioni(string $nomeTabella, array $datiValidati): array
    {
        // Applica trasformazioni finali
        if (isset($datiValidati['code'])) {
            $datiValidati['code'] = strtoupper(trim($datiValidati['code']));
        }
        
        if (isset($datiValidati['email'])) {
            $datiValidati['email'] = strtolower(trim($datiValidati['email']));
        }
        
        // Aggiungi timestamp se necessario
        if (!isset($datiValidati['updated_at'])) {
            $datiValidati['updated_at'] = now();
        }
        
        return $datiValidati;
    }

    /**
     * Validazioni personalizzate della strategia
     */
    private function applicaValidazioniPersonalizzate(
        \Illuminate\Validation\Validator $validator, 
        StrategiaTabella $strategia, 
        array $dati, 
        ?int $id
    ): void {
        // Verifica duplicati
        $validator->after(function ($validator) use ($strategia, $dati, $id) {
            if ($errore = $strategia->verificaDuplicati($dati, $id)) {
                $validator->errors()->add('duplicato', $errore);
            }
        });
        
        // Validazioni di business logic specifiche
        $this->applicaValidazioniBusiness($validator, $strategia, $dati);
    }

    /**
     * Validazioni business logic
     */
    private function applicaValidazioniBusiness(
        \Illuminate\Validation\Validator $validator,
        StrategiaTabella $strategia,
        array $dati
    ): void {
        // Esempio: validazione coerenza date
        if (isset($dati['data_inizio']) && isset($dati['data_fine'])) {
            $validator->after(function ($validator) use ($dati) {
                if (strtotime($dati['data_inizio']) > strtotime($dati['data_fine'])) {
                    $validator->errors()->add('data_fine', 'La data fine deve essere successiva alla data inizio.');
                }
            });
        }
        
        // Validazione relazioni
        $this->validaRelazioni($validator, $dati);
    }

    /**
     * Validazione relazioni tra entità
     */
    private function validaRelazioni(\Illuminate\Validation\Validator $validator, array $dati): void
    {
        // Implementa validazioni di integrità referenziale se necessario
    }

    /**
     * Normalizzazioni specifiche per tabella
     */
    private function applicaNormalizzazioni(string $nomeTabella, array $dati): array
    {
        switch ($nomeTabella) {
            case 'banche':
                if (isset($dati['iban'])) {
                    $dati['iban'] = strtoupper(str_replace(' ', '', $dati['iban']));
                }
                if (isset($dati['bic_swift'])) {
                    $dati['bic_swift'] = strtoupper(trim($dati['bic_swift']));
                }
                break;
                
            case 'clienti':
                if (isset($dati['codice_fiscale'])) {
                    $dati['codice_fiscale'] = strtoupper(str_replace(' ', '', $dati['codice_fiscale']));
                }
                break;
        }
        
        return $dati;
    }

    /**
     * Ottiene strategia per tabella
     */
    private function ottieniStrategia(string $nomeTabella): StrategiaTabella
    {
        if (!isset($this->strategieTabelle[$nomeTabella])) {
            throw new \InvalidArgumentException("Strategia validazione non trovata per: {$nomeTabella}");
        }
        
        return $this->strategieTabelle[$nomeTabella];
    }

    /**
     * Inizializza regole globali di sicurezza
     */
    private function inizializzaRegoleGlobali(): void
    {
        $this->regoleGlobali = [
            'xss_protection' => 'regex:/^[^<>"\'\&]*$/',
            'sql_injection_protection' => 'regex:/^(?!.*(\bSELECT\b|\bUNION\b|\bINSERT\b|\bDELETE\b|\bDROP\b)).*$/i',
            'no_javascript' => 'regex:/^(?!.*javascript:).*$/i'
        ];
    }

    /**
     * Ottiene messaggi globali
     */
    private function ottieniMessaggiGlobali(): array
    {
        return [
            'required' => 'Il campo :attribute è obbligatorio.',
            'string' => 'Il campo :attribute deve essere una stringa.',
            'max' => 'Il campo :attribute non può superare :max caratteri.',
            'min' => 'Il campo :attribute deve essere di almeno :min caratteri.',
            'email' => 'Il campo :attribute deve essere un indirizzo email valido.',
            'url' => 'Il campo :attribute deve essere un URL valido.',
            'regex' => 'Il formato del campo :attribute non è valido.',
            'unique' => 'Il valore del campo :attribute è già in uso.',
            'numeric' => 'Il campo :attribute deve essere un numero.',
            'integer' => 'Il campo :attribute deve essere un numero intero.',
            'boolean' => 'Il campo :attribute deve essere vero o falso.',
            'date' => 'Il campo :attribute deve essere una data valida.'
        ];
    }

    /**
     * Ottiene attributi personalizzati per messaggi
     */
    private function ottieniAttributiPersonalizzati(StrategiaTabella $strategia): array
    {
        $campiVisibili = $strategia->ottieniCampiVisibili();
        $attributi = [];
        
        foreach ($campiVisibili as $campo => $label) {
            $attributi[$campo] = strtolower($label);
        }
        
        return $attributi;
    }
}