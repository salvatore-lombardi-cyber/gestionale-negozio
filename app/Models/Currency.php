<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Valute con OWASP Security Best Practices 2025
 * Gestione valute internazionali: EUR, USD, GBP, JPY, etc.
 */
class Currency extends Model
{
    use HasFactory;

    // OWASP: Mass Assignment Protection - SOLO campi valute
    protected $fillable = [
        'valuta',
        'conversione',
        'descrizione'
    ];

    // OWASP: Campi protetti da mass assignment
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'conversione' => 'decimal:6',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // OWASP: Input Sanitization Methods per valute

    /**
     * Sanitizza la valuta per prevenire XSS
     */
    public function setValutaAttribute($value)
    {
        if ($value === null) {
            $this->attributes['valuta'] = null;
            return;
        }

        // OWASP: Sanitizzazione della valuta
        $sanitized = strtoupper(trim(strip_tags($value)));
        $sanitized = preg_replace('/[^A-Z0-9]/', '', $sanitized);
        $this->attributes['valuta'] = substr($sanitized, 0, 10);
    }

    /**
     * Sanitizza il tasso di conversione per prevenire injection
     */
    public function setConversioneAttribute($value)
    {
        if ($value === null) {
            $this->attributes['conversione'] = null;
            return;
        }

        // OWASP: Validazione numerica rigorosa per tasso di cambio
        $conversion = floatval($value);
        
        if ($conversion < 0) {
            $conversion = 0;
        } elseif ($conversion > 999999.999999) {
            $conversion = 999999.999999;
        }

        $this->attributes['conversione'] = $conversion;
    }

    /**
     * Sanitizza la descrizione per prevenire XSS
     */
    public function setDescrizioneAttribute($value)
    {
        if ($value === null) {
            $this->attributes['descrizione'] = null;
            return;
        }

        // OWASP: Sanitizzazione della descrizione valuta
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['descrizione'] = substr($sanitized, 0, 255);
    }

    // OWASP: Validazione regole semplificata per valute
    public static function validationRules(): array
    {
        return [
            'valuta' => 'required|string|max:10|min:1|regex:/^[A-Z0-9]+$/',
            'conversione' => 'required|numeric|min:0|max:999999.999999',
            'descrizione' => 'required|string|max:255|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }

    // Accessors per visualizzazione formattata
    public function getFormattedConversioneAttribute(): string
    {
        return number_format($this->conversione, 6);
    }

    /**
     * OWASP: Log delle modifiche per audit trail valute
     * Importante per tracciare modifiche a tassi di cambio sensibili
     */
    protected static function booted()
    {
        // OWASP: Audit Trail per valute (dati finanziari sensibili)
        static::creating(function ($model) {
            \Log::info('Creating Currency', [
                'valuta' => $model->valuta,
                'conversione' => $model->conversione,
                'descrizione' => $model->descrizione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating Currency', [
                'id' => $model->id,
                'valuta' => $model->valuta,
                'conversione' => $model->conversione,
                'descrizione' => $model->descrizione,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting Currency', [
                'id' => $model->id,
                'valuta' => $model->valuta,
                'conversione' => $model->conversione,
                'descrizione' => $model->descrizione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}