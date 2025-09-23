<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Denominazioni Prezzi Fissi
 * Gestione semplificata con solo descrizione e commento
 */
class FixedPriceDenomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('description');
    }

    public function scopeByDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'LIKE', "%{$description}%");
    }

    public function scopeByComment(Builder $query, string $comment): Builder
    {
        return $query->where('comment', 'LIKE', "%{$comment}%");
    }

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:fixed_price_denominations,description' . ($id ? ',' . $id : '')
            ],
            'comment' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono elementi associati a questa denominazione
        // return !$this->relatedElements()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione documenti/prezzi)
    // public function priceElements()
    // {
    //     return $this->hasMany(PriceElement::class, 'denomination_id');
    // }
}
