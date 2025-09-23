<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Colori Varianti (Card #10)
 * Semplice tabella con solo descrizione
 */
class ColorVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
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

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:color_variants,description' . ($id ? ',' . $id : '')
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono prodotti associati a questo colore
        // return !$this->products()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione prodotti)
    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'color_variant_id');
    // }
}