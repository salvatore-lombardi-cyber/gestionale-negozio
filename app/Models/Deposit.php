<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Depositi (Card #13)
 * Semplice gestione depositi e ubicazioni magazzino
 */
class Deposit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'address',
        'city',
        'state',
        'province',
        'postal_code',
        'phone',
        'fax',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('code');
    }

    // Validazione semplice ma sicura
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:deposits,code|regex:/^[A-Z0-9_-]+$/',
            'description' => 'required|string|max:255|min:2',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:5',
            'postal_code' => 'nullable|string|max:10|regex:/^[0-9]{5}$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\+\-\(\)]+$/',
            'fax' => 'nullable|string|max:20|regex:/^[0-9\s\+\-\(\)]+$/',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:50|unique:deposits,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }
}
