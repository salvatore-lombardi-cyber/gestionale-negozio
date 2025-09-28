<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Ubicazioni con OWASP Security Best Practices 2025
 * Gestione ubicazioni magazzino: scaffali, ripiani, zone, settori
 */
class Location extends Model
{
    use SoftDeletes;

    // OWASP: Mass Assignment Protection - SOLO campi ubicazioni
    protected $fillable = [
        'dep',
        'ubicazione'
    ];

    // OWASP: Campi protetti da mass assignment
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // OWASP: Input Sanitization Methods per ubicazioni

    /**
     * Sanitizza il deposito per prevenire XSS
     */
    public function setDepAttribute($value)
    {
        if ($value === null) {
            $this->attributes['dep'] = null;
            return;
        }

        // OWASP: Sanitizzazione del deposito
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['dep'] = substr($sanitized, 0, 255);
    }

    /**
     * Sanitizza l'ubicazione per prevenire XSS
     */
    public function setUbicazioneAttribute($value)
    {
        if ($value === null) {
            $this->attributes['ubicazione'] = null;
            return;
        }

        // OWASP: Sanitizzazione dell'ubicazione
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['ubicazione'] = substr($sanitized, 0, 255);
    }

    // OWASP: Validazione regole semplificata per ubicazioni
    public static function validationRules(): array
    {
        return [
            'dep' => 'required|string|max:255|min:1',
            'ubicazione' => 'required|string|max:255|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }


    /**
     * OWASP: Log delle modifiche per audit trail ubicazioni
     * Importante per tracciare modifiche a informazioni magazzino sensibili
     */
    protected static function booted()
    {
        // OWASP: Audit Trail per ubicazioni (dati magazzino sensibili)
        static::creating(function ($model) {
            \Log::info('Creating Location', [
                'dep' => $model->dep,
                'ubicazione' => $model->ubicazione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating Location', [
                'id' => $model->id,
                'dep' => $model->dep,
                'ubicazione' => $model->ubicazione,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting Location', [
                'id' => $model->id,
                'dep' => $model->dep,
                'ubicazione' => $model->ubicazione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}
