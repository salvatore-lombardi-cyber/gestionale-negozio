<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Depositi
 * Gestione semplificata depositi e ubicazioni magazzino
 */
class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'address',
        'city',
        'state',
        'province',
        'postal_code',
        'phone',
        'fax'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('code')->orderBy('description');
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', 'LIKE', "%{$code}%");
    }

    public function scopeByDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'LIKE', "%{$description}%");
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:deposits,code' . ($id ? ',' . $id : '')
            ],
            'description' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'address' => [
                'nullable',
                'string',
                'max:255'
            ],
            'city' => [
                'nullable',
                'string',
                'max:100'
            ],
            'state' => [
                'nullable',
                'string',
                'max:100'
            ],
            'province' => [
                'nullable',
                'string',
                'max:5',
                'regex:/^[A-Z]{2}$/'
            ],
            'postal_code' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[0-9]{5}$/'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\s\+\-\(\)]+$/'
            ],
            'fax' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\s\+\-\(\)]+$/'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono movimenti di magazzino associati a questo deposito
        // return !$this->warehouseMovements()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione magazzino)
    // public function warehouseMovements()
    // {
    //     return $this->hasMany(WarehouseMovement::class, 'deposit_id');
    // }
}
