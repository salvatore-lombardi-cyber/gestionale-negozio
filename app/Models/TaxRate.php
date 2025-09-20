<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'description',
        'riferimento_normativo',
        'percentuale',
        'active',
        'sort_order',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'active' => 'boolean',
        'percentuale' => 'decimal:2',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    protected $hidden = [
        'created_by',
        'updated_by'
    ];

    /**
     * Verifica se il codice è unico
     */
    public static function isCodeUnique(string $code, ?int $excludeId = null): bool
    {
        $query = static::where('code', $code);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->doesntExist();
    }

    /**
     * Scope per aliquote attive
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope per ordinamento standard
     */
    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('sort_order')->orderBy('percentuale')->orderBy('name');
    }

    /**
     * Accessor per formattare la percentuale
     */
    public function getPercentualeFormattedAttribute(): string
    {
        return number_format($this->percentuale, 2) . '%';
    }

    /**
     * Accessor per retrocompatibilità con 'rate'
     */
    public function getRateAttribute()
    {
        return $this->percentuale;
    }

    /**
     * Mutator per retrocompatibilità con 'rate'
     */
    public function setRateAttribute($value)
    {
        $this->attributes['percentuale'] = $value;
    }

    /**
     * Relazione con utente creatore (audit trail)
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Relazione con utente che ha fatto l'ultimo aggiornamento (audit trail)
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Cache key per performance
     */
    public function getCacheKey(): string
    {
        return "tax_rate_{$this->uuid}";
    }

    /**
     * Boot model per gestire UUID automaticamente
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = \Illuminate\Support\Str::uuid()->toString();
            }
            if (auth()->check() && empty($model->created_by)) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }
}