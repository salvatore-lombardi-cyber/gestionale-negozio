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
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'active'
    ];

    // OWASP: Campi protetti da mass assignment
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query) 
    {
        return $query->orderBy('name');
    }

    // OWASP: Input Sanitization Methods per valute

    /**
     * Sanitizza il codice valuta per prevenire injection attacks
     */
    public function setCodeAttribute($value)
    {
        // OWASP: Sanitizzazione rigorosa del codice valuta (ISO 4217)
        $sanitized = strtoupper(trim($value));
        $sanitized = preg_replace('/[^A-Z0-9]/', '', $sanitized);
        $this->attributes['code'] = substr($sanitized, 0, 3); // ISO 4217 = 3 chars
    }

    /**
     * Sanitizza il nome valuta per prevenire XSS
     */
    public function setNameAttribute($value)
    {
        // OWASP: Sanitizzazione del nome valuta
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['name'] = substr($sanitized, 0, 150);
    }

    /**
     * Sanitizza il simbolo valuta per prevenire XSS
     */
    public function setSymbolAttribute($value)
    {
        if ($value === null) {
            $this->attributes['symbol'] = null;
            return;
        }

        // OWASP: Sanitizzazione del simbolo valuta
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['symbol'] = substr($sanitized, 0, 10);
    }

    /**
     * Validazione tasso di cambio
     */
    public function setExchangeRateAttribute($value)
    {
        if ($value === null) {
            $this->attributes['exchange_rate'] = 1.000000;
            return;
        }

        // OWASP: Validazione numerica rigorosa per tassi
        $rate = floatval($value);
        
        if ($rate <= 0) {
            $rate = 1.000000;
        } elseif ($rate > 999999.999999) {
            $rate = 999999.999999;
        }

        $this->attributes['exchange_rate'] = $rate;
    }

    // OWASP: Validazione regole complete per valute
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:3|min:3|unique:currencies,code|regex:/^[A-Z]{3}$/',
            'name' => 'required|string|max:150|min:2',
            'symbol' => 'nullable|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001|max:999999.999999',
            'active' => 'boolean'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:3|min:3|unique:currencies,code,' . $id . '|regex:/^[A-Z]{3}$/';
        return $rules;
    }

    // Accessors per visualizzazione formattata
    public function getFormattedActiveAttribute(): string
    {
        return $this->active ? 'Attiva' : 'Inattiva';
    }

    public function getFormattedExchangeRateAttribute(): string
    {
        return number_format($this->exchange_rate, 6);
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
                'code' => $model->code,
                'name' => $model->name,
                'exchange_rate' => $model->exchange_rate,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating Currency', [
                'id' => $model->id,
                'code' => $model->code,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting Currency', [
                'id' => $model->id,
                'code' => $model->code,
                'name' => $model->name,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}