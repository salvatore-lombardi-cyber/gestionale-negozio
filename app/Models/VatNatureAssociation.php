<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Associazioni Nature IVA (Card #1 - Configuratore Speciale)
 * Gestisce le associazioni tra aliquote IVA e nature fiscali
 */
class VatNatureAssociation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'nome_associazione',
        'name',
        'descrizione',
        'description',
        'tax_rate_id',
        'vat_nature_id', 
        'is_default',
        'active',
        'created_by'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'created_by' => 'integer'
    ];

    // Relazioni
    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function vatNature()
    {
        return $this->belongsTo(VatNature::class);
    }

    // Scopes per query ottimizzate
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    // Relazione con utente creatore
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    

    // Accessor per nome visualizzato
    public function getDisplayNameAttribute(): string
    {
        return $this->nome_associazione ?? 
               ($this->taxRate?->code . ' + ' . $this->vatNature?->code) ?? 
               'Associazione #' . $this->id;
    }

    // Cache key per performance
    public function getCacheKey(): string
    {
        return "vat_nature_association_{$this->uuid}";
    }
    
    // Verifica univocità associazione
    public static function isAssociationUnique($taxRateId, $vatNatureId, $excludeId = null): bool
    {
        $query = static::where('tax_rate_id', $taxRateId)
                      ->where('vat_nature_id', $vatNatureId)
                      ->where('active', true);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->doesntExist();
    }
    
    // Imposta come default (rimuove default da altre)
    public function setAsDefault(): bool
    {
        \DB::transaction(function () {
            // Rimuovi default da altre associazioni con stessa aliquota
            static::where('tax_rate_id', $this->tax_rate_id)
                  ->where('id', '!=', $this->id)
                  ->update(['is_default' => false]);
            
            // Imposta questa come default
            $this->update(['is_default' => true]);
        });
        
        return true;
    }
    
    /**
     * Boot model per gestire UUID e audit trail automaticamente
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

    }
}