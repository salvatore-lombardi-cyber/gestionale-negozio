<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Tipo di Taglie con OWASP Security Best Practices 2025
 * Gestione sistemi di classificazione taglie (EU, US, UK, IT, Numeric, Letter, etc.)
 */
class SizeType extends Model
{
    use SoftDeletes;

    // OWASP: Mass Assignment Protection - Solo campi specifici
    protected $fillable = [
        'code',
        'name', 
        'description',
        'category',
        'measurement_unit',
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


    // OWASP: Input Sanitization Methods
    
    /**
     * Sanitizza il codice per prevenire injection attacks
     */
    public function setCodeAttribute($value)
    {
        // OWASP: Sanitizzazione rigorosa del codice
        $sanitized = strtoupper(trim($value));
        $sanitized = preg_replace('/[^A-Z0-9_-]/', '', $sanitized);
        $this->attributes['code'] = substr($sanitized, 0, 20);
    }

    /**
     * Sanitizza il nome per prevenire XSS
     */
    public function setNameAttribute($value)
    {
        // OWASP: Sanitizzazione del nome
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\']/', '', $sanitized);
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

        // OWASP: Sanitizzazione della descrizione
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\']/', '', $sanitized);
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

    // OWASP: Validazione regole complete
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:size_types,code|regex:/^[A-Z0-9_-]+$/',
            'name' => 'required|string|max:150|min:2',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|in:clothing,shoes,children,accessories,underwear,sportswear,formal,casual',
            'measurement_unit' => 'nullable|string|in:cm,inches,mixed,numeric,letter',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:20|unique:size_types,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }

    // Accessors per visualizzazione formattata
    public function getFormattedCategoryAttribute(): string
    {
        return match($this->category) {
            'clothing' => 'Abbigliamento',
            'shoes' => 'Calzature',
            'children' => 'Bambini',
            'accessories' => 'Accessori',
            'underwear' => 'Intimo',
            'sportswear' => 'Sportivo',
            'formal' => 'Formale',
            'casual' => 'Casual',
            default => 'Non specificato'
        };
    }

    public function getFormattedMeasurementUnitAttribute(): string
    {
        return match($this->measurement_unit) {
            'cm' => 'Centimetri',
            'inches' => 'Pollici',
            'mixed' => 'Misto',
            'numeric' => 'Numerico',
            'letter' => 'Lettere',
            default => 'Non specificato'
        };
    }

    public function getFormattedActiveAttribute(): string
    {
        return $this->active ? 'Attivo' : 'Inattivo';
    }

    /**
     * Log delle modifiche per audit trail OWASP
     */
    protected static function booted()
    {
        // OWASP: Audit Trail
        static::creating(function ($model) {
            \Log::info('Creating SizeType', [
                'code' => $model->code,
                'name' => $model->name,
                'category' => $model->category,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating SizeType', [
                'id' => $model->id,
                'code' => $model->code,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting SizeType', [
                'id' => $model->id,
                'code' => $model->code,
                'name' => $model->name,
                'category' => $model->category,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });
    }
}
