<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Causali di Magazzino (Card #9)
 * Semplice tabella con codice e descrizione
 */
class WarehouseCause extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('code');
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', 'LIKE', "%{$code}%");
    }

    public function scopeByDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'LIKE', "%{$description}%");
    }

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:warehouse_causes,code' . ($id ? ',' . $id : '')
            ],
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono movimenti associati a questa causale
        // return !$this->movements()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione movimenti)
    // public function movements()
    // {
    //     return $this->hasMany(WarehouseMovement::class, 'cause_id');
    // }
}