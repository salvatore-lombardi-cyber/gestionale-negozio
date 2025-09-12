<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Listini Prezzi (Card #14)
 * Gestione listini con date validità e percentuali sconto/maggiorazione
 */
class PriceList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description', 
        'discount_percentage',
        'valid_from',
        'valid_to',
        'is_default',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date', 
        'is_default' => 'boolean',
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

    public function scopeValid(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where('valid_from', '<=', $today)
            ->where(function($q) use ($today) {
                $q->whereNull('valid_to')
                  ->orWhere('valid_to', '>=', $today);
            });
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('description');
    }

    // Validazione sicura OWASP
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:price_lists,code|regex:/^[A-Z0-9_-]+$/',
            'description' => 'required|string|max:255|min:2',
            'discount_percentage' => 'required|numeric|between:-100,1000', // da -100% a +1000%
            'valid_from' => 'required|date|after_or_equal:today',
            'valid_to' => 'nullable|date|after:valid_from',
            'is_default' => 'boolean',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:50|unique:price_lists,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        $rules['valid_from'] = 'required|date'; // Per modifica, permettiamo date passate
        return $rules;
    }

    // Helper per formattazione percentuale
    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_percentage > 0) {
            return '+' . number_format($this->discount_percentage, 2) . '%';
        }
        return number_format($this->discount_percentage, 2) . '%';
    }

    // Helper per verifica validità
    public function getIsCurrentlyValidAttribute(): bool
    {
        $today = now()->toDate();
        $validFrom = $this->valid_from;
        $validTo = $this->valid_to;

        return $validFrom <= $today && (is_null($validTo) || $validTo >= $today);
    }
}
