<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model per Tipo di Pagamento Rateizzato - Sistema V2 Semplificato
 * Gestione piani di pagamento con rateizzazione (1-12 rate + percentuali)
 */
class PaymentType extends Model
{
    use SoftDeletes;

    protected $table = 'payment_types';

    // Campi semplificati per rateizzazione
    protected $fillable = [
        'code',
        'description', 
        'total_installments',
        'percentage_1', 'percentage_2', 'percentage_3', 'percentage_4',
        'percentage_5', 'percentage_6', 'percentage_7', 'percentage_8', 
        'percentage_9', 'percentage_10', 'percentage_11', 'percentage_12',
        'end_payment'
    ];

    protected $casts = [
        'total_installments' => 'integer',
        'percentage_1' => 'decimal:2', 'percentage_2' => 'decimal:2', 'percentage_3' => 'decimal:2', 'percentage_4' => 'decimal:2',
        'percentage_5' => 'decimal:2', 'percentage_6' => 'decimal:2', 'percentage_7' => 'decimal:2', 'percentage_8' => 'decimal:2',
        'percentage_9' => 'decimal:2', 'percentage_10' => 'decimal:2', 'percentage_11' => 'decimal:2', 'percentage_12' => 'decimal:2',
        'end_payment' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|min:1',
            'description' => 'required|string|max:255|min:1',
            'total_installments' => 'required|integer|min:1|max:12',
            'percentage_1' => 'nullable|numeric|min:0|max:100',
            'percentage_2' => 'nullable|numeric|min:0|max:100',
            'percentage_3' => 'nullable|numeric|min:0|max:100',
            'percentage_4' => 'nullable|numeric|min:0|max:100',
            'percentage_5' => 'nullable|numeric|min:0|max:100',
            'percentage_6' => 'nullable|numeric|min:0|max:100',
            'percentage_7' => 'nullable|numeric|min:0|max:100',
            'percentage_8' => 'nullable|numeric|min:0|max:100',
            'percentage_9' => 'nullable|numeric|min:0|max:100',
            'percentage_10' => 'nullable|numeric|min:0|max:100',
            'percentage_11' => 'nullable|numeric|min:0|max:100',
            'percentage_12' => 'nullable|numeric|min:0|max:100',
            'end_payment' => 'nullable|boolean'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:20|min:1|unique:payment_types,code,' . $id;
        return $rules;
    }

    // Helper per calcolare totale percentuali
    public function getTotalPercentageAttribute(): float
    {
        $total = 0;
        for ($i = 1; $i <= 12; $i++) {
            $total += $this->{"percentage_$i"} ?? 0;
        }
        return $total;
    }

    // Helper per verificare se le percentuali sono bilanciate
    public function getIsBalancedAttribute(): bool
    {
        return abs($this->total_percentage - 100) < 0.01;
    }
}