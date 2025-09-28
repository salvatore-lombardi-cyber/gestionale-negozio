<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Zone con OWASP Security Best Practices 2025
 * Gestione zone geografiche e commerciali: Nord Italia, Europa, Asia-Pacifico, etc.
 */
class Zone extends Model
{
    use SoftDeletes;

    // OWASP: Mass Assignment Protection - SOLO campi zone
    protected $fillable = [
        'codice',
        'descrizione'
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

    // OWASP: Input Sanitization Methods per zone

    /**
     * Sanitizza il codice per prevenire XSS
     */
    public function setCodiceAttribute($value)
    {
        if ($value === null) {
            $this->attributes['codice'] = null;
            return;
        }

        // OWASP: Sanitizzazione del codice zona
        $sanitized = strtoupper(trim(strip_tags($value)));
        $sanitized = preg_replace('/[^A-Z0-9_-]/', '', $sanitized);
        $this->attributes['codice'] = substr($sanitized, 0, 20);
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

        // OWASP: Sanitizzazione della descrizione zona
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['descrizione'] = substr($sanitized, 0, 255);
    }

    // OWASP: Validazione regole semplificata per zone
    public static function validationRules(): array
    {
        return [
            'codice' => 'required|string|max:20|min:1|regex:/^[A-Z0-9_-]+$/',
            'descrizione' => 'required|string|max:255|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }


    /**
     * OWASP: Log delle modifiche per audit trail zone
     * Importante per tracciare modifiche a informazioni geografiche sensibili
     */
    protected static function booted()
    {
        // OWASP: Audit Trail per zone (dati geografici/commerciali sensibili)
        static::creating(function ($model) {
            \Log::info('Creating Zone', [
                'codice' => $model->codice,
                'descrizione' => $model->descrizione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating Zone', [
                'id' => $model->id,
                'codice' => $model->codice,
                'descrizione' => $model->descrizione,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting Zone', [
                'id' => $model->id,
                'codice' => $model->codice,
                'descrizione' => $model->descrizione,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}
