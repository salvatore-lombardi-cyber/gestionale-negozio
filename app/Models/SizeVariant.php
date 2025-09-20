<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Taglie Varianti con OWASP Security Best Practices 2025
 * Gestione completa taglie e sistemi di misurazione
 */
class SizeVariant extends Model
{
    use SoftDeletes;

    // OWASP: Mass Assignment Protection - Solo campi specifici
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

        // OWASP: Sanitizzazione della descrizione
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

    // OWASP: Validazione regole complete
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:size_variants,code|regex:/^[A-Z0-9_-]+$/',
            'name' => 'required|string|max:150|min:2',
            'description' => 'nullable|string|max:500',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:20|unique:size_variants,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }

    /**
     * Log delle modifiche per audit trail OWASP
     */
    protected static function booted()
    {
        // OWASP: Audit Trail
        static::creating(function ($model) {
            \Log::info('Creating SizeVariant', [
                'code' => $model->code,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating SizeVariant', [
                'id' => $model->id,
                'code' => $model->code,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting SizeVariant', [
                'id' => $model->id,
                'code' => $model->code,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });
    }
}
