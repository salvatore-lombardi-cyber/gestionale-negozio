<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Listini
 * Gestione semplificata con solo descrizione e percentuale
 */
class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'discount_percentage'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
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

    public function scopeByPercentage(Builder $query, float $percentage): Builder
    {
        return $query->where('discount_percentage', $percentage);
    }

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'description' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:price_lists,description' . ($id ? ',' . $id : '')
            ],
            'discount_percentage' => [
                'required',
                'numeric',
                'between:-100,1000' // da -100% a +1000%
            ]
        ];
    }

    // Helper per formattazione percentuale
    public function getFormattedPercentageAttribute(): string
    {
        if ($this->discount_percentage > 0) {
            return '+' . number_format($this->discount_percentage, 2) . '%';
        } elseif ($this->discount_percentage < 0) {
            return number_format($this->discount_percentage, 2) . '%';
        }
        return '0.00%';
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono prodotti associati a questo listino
        // return !$this->products()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione prodotti)
    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'price_list_id');
    // }
}
