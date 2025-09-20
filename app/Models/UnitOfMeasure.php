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
        'code',
        'name', 
        'description',
        'active',
        'sort_order'
    ];

    // OWASP: Campi protetti da mass assignment
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query) 
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // OWASP: Input Sanitization Methods per unità di misura

    /**
     * Sanitizza il codice unità di misura per prevenire injection attacks
     */
    public function setCodeAttribute($value)
    {
        // OWASP: Sanitizzazione rigorosa del codice unità di misura
        $sanitized = strtoupper(trim($value));
        $sanitized = preg_replace('/[^A-Z0-9_-]/', '', $sanitized);
        $this->attributes['code'] = substr($sanitized, 0, 20);
    }

    /**
     * Sanitizza il nome unità di misura per prevenire XSS
     */
    public function setNameAttribute($value)
    {
        // OWASP: Sanitizzazione del nome unità di misura
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\'&]/', '', $sanitized);
        $this->attributes['name'] = substr($sanitized, 0, 150);
    }

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
        $this->attributes['description'] = substr($sanitized, 0, 500);
    }

    /**
     * Validazione ordine
     */
    public function setSortOrderAttribute($value)
    {
        if ($value === null) {
            $this->attributes['sort_order'] = 0;
            return;
        }

        // OWASP: Validazione numerica rigorosa
        $order = intval($value);
        
        if ($order < 0) {
            $order = 0;
        } elseif ($order > 9999) {
            $order = 9999;
        }

        $this->attributes['sort_order'] = $order;
    }

    // OWASP: Validazione regole complete per unità di misura
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:unit_of_measures,code|regex:/^[A-Z0-9_-]+$/',
            'name' => 'required|string|max:150|min:1',
            'description' => 'nullable|string|max:500',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:20|unique:unit_of_measures,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }

    // Accessors per visualizzazione formattata
    public function getFormattedActiveAttribute(): string
    {
        return $this->active ? 'Attiva' : 'Inattiva';
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
                'code' => $model->code,
                'name' => $model->name,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating UnitOfMeasure', [
                'id' => $model->id,
                'code' => $model->code,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting UnitOfMeasure', [
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
