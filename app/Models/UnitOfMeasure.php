<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Unità di Misura con OWASP Security Best Practices 2025
 * Gestione unità di misura: kg, litri, metri, pezzi, etc.
 */
class UnitOfMeasure extends Model
{
    use SoftDeletes;

    // OWASP: Mass Assignment Protection - SOLO campi unità di misura
    protected $fillable = [
        'description',
        'comment'
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

    // OWASP: Input Sanitization Methods per unità di misura

    /**
     * Sanitizza la descrizione per prevenire XSS
     */
    public function setDescriptionAttribute($value)
    {
        if ($value === null) {
            $this->attributes['description'] = null;
            return;
        }

        // OWASP: Sanitizzazione della descrizione unità di misura
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['description'] = substr($sanitized, 0, 255);
    }

    /**
     * Sanitizza il commento per prevenire XSS
     */
    public function setCommentAttribute($value)
    {
        if ($value === null) {
            $this->attributes['comment'] = null;
            return;
        }

        // OWASP: Sanitizzazione del commento unità di misura
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['comment'] = substr($sanitized, 0, 500);
    }

    // OWASP: Validazione regole semplificata per unità di misura
    public static function validationRules(): array
    {
        return [
            'description' => 'required|string|max:255|min:1',
            'comment' => 'nullable|string|max:500'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return self::validationRules();
    }


    /**
     * OWASP: Log delle modifiche per audit trail unità di misura
     * Importante per tracciare modifiche a informazioni inventario sensibili
     */
    protected static function booted()
    {
        // OWASP: Audit Trail per unità di misura (dati inventario sensibili)
        static::creating(function ($model) {
            \Log::info('Creating UnitOfMeasure', [
                'description' => $model->description,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating UnitOfMeasure', [
                'id' => $model->id,
                'description' => $model->description,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting UnitOfMeasure', [
                'id' => $model->id,
                'description' => $model->description,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}
