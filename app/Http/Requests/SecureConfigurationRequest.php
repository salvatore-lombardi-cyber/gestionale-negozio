<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Request per validazioni avanzate delle configurazioni
 * Implementa controlli di sicurezza OWASP
 */
class SecureConfigurationRequest extends FormRequest
{
    /**
     * Determina se l'utente è autorizzato a fare questa request
     */
    public function authorize(): bool
    {
        return Auth::check() && $this->hasConfigurationAccess();
    }

    /**
     * Regole di validazione comuni
     */
    public function rules(): array
    {
        return [
            // Regole base che possono essere override nei controller
        ];
    }

    /**
     * Messaggi di errore personalizzati
     */
    public function messages(): array
    {
        return [
            'regex' => 'Il formato del campo :attribute non è valido.',
            'unique' => 'Il valore del campo :attribute è già presente nel sistema.',
            'email' => 'L\'indirizzo email deve essere valido e verificabile.',
            'url' => 'L\'URL deve essere valido e utilizzare HTTPS quando possibile.',
            'image' => 'Il file deve essere un\'immagine valida.',
            'mimes' => 'Il file deve essere di tipo: :values.',
            'max' => [
                'file' => 'Il file non deve essere più grande di :max kilobyte.',
                'string' => 'Il campo non può contenere più di :max caratteri.',
                'numeric' => 'Il valore non può essere maggiore di :max.',
            ],
            'min' => [
                'numeric' => 'Il valore deve essere almeno :min.',
                'string' => 'Il campo deve contenere almeno :min caratteri.',
            ],
            'required' => 'Il campo :attribute è obbligatorio.',
            'size' => [
                'string' => 'Il campo deve essere esattamente di :size caratteri.',
            ],
            'before' => 'La data deve essere precedente al :date.',
            'numeric' => 'Il campo deve essere un numero.',
            'boolean' => 'Il campo deve essere vero o falso.',
            'in' => 'Il valore selezionato per :attribute non è valido.',
        ];
    }

    /**
     * Attributi personalizzati per i messaggi di errore
     */
    public function attributes(): array
    {
        return [
            'ragione_sociale' => 'ragione sociale',
            'partita_iva' => 'partita IVA',
            'codice_attivita_iva' => 'codice attività IVA',
            'sito_web' => 'sito web',
            'data_nascita' => 'data di nascita',
            'capitale_sociale' => 'capitale sociale',
            'nome_banca' => 'nome banca',
            'conto_corrente' => 'conto corrente',
            'exchange_rate' => 'tasso di cambio',
        ];
    }

    /**
     * Prepara i dati per la validazione
     */
    protected function prepareForValidation(): void
    {
        // Sanitizzazione input base
        $input = $this->all();
        
        // Trim automatico su tutti i campi stringa
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = trim($value);
                // Rimuove caratteri di controllo pericolosi
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
            }
        });

        $this->replace($input);
    }

    /**
     * Gestisce fallimento validazione
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Rate limiting per tentativi di validazione falliti
        $rateLimiterKey = 'failed-validation:' . $this->ip();
        RateLimiter::hit($rateLimiterKey, 3600); // Track per un'ora
        
        if (RateLimiter::attempts($rateLimiterKey) > 50) {
            abort(429, 'Troppi tentativi di validazione falliti. IP temporaneamente bloccato.');
        }

        parent::failedValidation($validator);
    }

    /**
     * Verifica se l'utente ha accesso alle configurazioni
     */
    private function hasConfigurationAccess(): bool
    {
        $user = Auth::user();
        
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('admin') || $user->hasRole('configuratore');
        }
        
        if (isset($user->role)) {
            return in_array($user->role, ['admin', 'configuratore', 'super_admin']);
        }

        return true; // Fallback temporaneo
    }

    /**
     * Regole di validazione per codici alfanumerici sicuri
     */
    public static function secureCodeRules(int $maxLength = 10): string
    {
        return "required|string|max:{$maxLength}|regex:/^[A-Z0-9_-]+$/";
    }

    /**
     * Regole di validazione per nomi e descrizioni
     */
    public static function nameRules(int $maxLength = 255): string
    {
        return "required|string|max:{$maxLength}|regex:/^[\\p{L}\\p{N}\\s\\-\\.\\&\\(\\)]+$/u";
    }

    /**
     * Regole di validazione per valori numerici decimali
     */
    public static function decimalRules(int $integerDigits = 6, int $decimalDigits = 6): string
    {
        return "required|numeric|min:0|regex:/^\\d{1,{$integerDigits}}(\\.\\d{1,{$decimalDigits}})?$/";
    }

    /**
     * Regole di validazione per IBAN italiano
     */
    public static function ibanRules(): array
    {
        return [
            'required',
            'string',
            'size:27',
            'regex:/^IT[0-9]{2}[A-Z][0-9]{5}[0-9]{5}[A-Z0-9]{12}$/'
        ];
    }

    /**
     * Regole di validazione per codici SWIFT/BIC
     */
    public static function swiftRules(): array
    {
        return [
            'nullable',
            'string',
            'size:11',
            'regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/'
        ];
    }
}