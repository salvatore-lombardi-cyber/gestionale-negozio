<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Categorie Clienti (Card #6)
 * Segmentazione clientela
 */
class CustomerCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('code')->orderBy('description');
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
                'unique:customer_categories,code' . ($id ? ',' . $id : '')
            ],
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'active' => [
                'boolean'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono clienti associati a questa categoria
        // return !$this->customers()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        // Rimosso created_by perché il campo non esiste più nella tabella semplificata
    }

    // Relazioni future (quando sarà implementata la gestione clienti)
    // public function customers()
    // {
    //     return $this->hasMany(Customer::class, 'categoria_cliente');
    // }
}