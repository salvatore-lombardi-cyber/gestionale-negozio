<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Condizioni (Card #11)
 * Semplice gestione condizioni di pagamento e vendita
 */
class Condition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
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
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Validazione semplice ma sicura
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:conditions,code|regex:/^[A-Z0-9_-]+$/',
            'name' => 'required|string|max:255|min:2',
            'description' => 'nullable|string|max:500',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:50|unique:conditions,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }
}
