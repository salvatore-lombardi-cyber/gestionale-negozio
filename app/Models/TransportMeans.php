<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Trasporto a Mezzo - Sistema V2 Semplificato
 * Gestione modalità di trasporto a mezzo (nave, aereo, treno, etc.)
 */
class TransportMeans extends Model
{
    use SoftDeletes;

    protected $table = 'transport_means';

    protected $fillable = [
        'description',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // OWASP: Input Sanitization Methods
    
    /**
     * Sanitizza la descrizione per prevenire XSS
     */
    public function setDescriptionAttribute($value)
    {
        if ($value === null) {
            $this->attributes['description'] = null;
            return;
        }

        // OWASP: Sanitizzazione della descrizione trasporto a mezzo
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

        // OWASP: Sanitizzazione del commento trasporto a mezzo
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['comment'] = substr($sanitized, 0, 500);
    }

    // OWASP: Validazione regole semplificata per trasporto a mezzo
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
     * OWASP: Log delle modifiche per audit trail trasporti a mezzo
     * Importante per tracciare modifiche a informazioni logistiche sensibili
     */
    protected static function booted()
    {
        // OWASP: Audit Trail per trasporti a mezzo (dati sensibili)
        static::creating(function ($model) {
            \Log::info('Creating TransportMeans', [
                'description' => $model->description,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating TransportMeans', [
                'id' => $model->id,
                'description' => $model->description,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting TransportMeans', [
                'id' => $model->id,
                'description' => $model->description,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}