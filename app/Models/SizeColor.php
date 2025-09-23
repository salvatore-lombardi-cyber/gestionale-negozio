<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Taglie e Colori (Card #8)
 * Gestione varianti prodotto semplificata
 */
class SizeColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeSizes(Builder $query): Builder
    {
        return $query->where('type', 'TAGLIA');
    }

    public function scopeColors(Builder $query): Builder
    {
        return $query->where('type', 'COLORE');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('type')->orderBy('name');
    }

    // Accessors
    public function getIsColorAttribute(): bool
    {
        return $this->type === 'COLORE';
    }

    public function getIsSizeAttribute(): bool
    {
        return $this->type === 'TAGLIA';
    }

    public function getTypeTranslatedAttribute(): string
    {
        return $this->type === 'TAGLIA' ? 'Taglia' : 'Colore';
    }

    // Validazioni OWASP
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:size_colors,code' . ($id ? ',' . $id : '')
            ],
            'name' => [
                'required',
                'string',
                'min:1',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'type' => [
                'required',
                'in:TAGLIA,COLORE'
            ],
            'active' => [
                'boolean'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono prodotti associati a questa taglia/colore
        // return !$this->products()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        // Rimosso created_by perché il campo non esiste più nella tabella semplificata
    }

    // Relazioni future (quando sarà implementata la gestione prodotti)
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_variants');
    // }
}