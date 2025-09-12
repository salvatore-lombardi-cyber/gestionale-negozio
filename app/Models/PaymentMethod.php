<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Modalità di Pagamento (Card #15)
 * Gestione semplice modalità di pagamento per PMI italiane
 */
class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'electronic_invoice_code',
        'type',
        'default_due_days',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'default_due_days' => 'integer',
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

    public function scopeImmediate(Builder $query): Builder
    {
        return $query->where('type', 'immediate');
    }

    public function scopeDeferred(Builder $query): Builder
    {
        return $query->where('type', 'deferred');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('description');
    }

    // Validazione sicura OWASP
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:10|unique:payment_methods,code|regex:/^[A-Z0-9_-]+$/',
            'description' => 'required|string|max:100|min:2',
            'electronic_invoice_code' => 'nullable|string|max:4|regex:/^MP[0-9]{2}$/',
            'type' => 'required|in:immediate,deferred,installment',
            'default_due_days' => 'nullable|integer|min:0|max:365',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:10|unique:payment_methods,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }

    // Helper per formattazione tipo
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'immediate' => 'Immediato',
            'deferred' => 'Dilazionato', 
            'installment' => 'Rateale',
            default => $this->type
        };
    }

    // Helper per scadenza
    public function getFormattedDueDaysAttribute(): string
    {
        if (!$this->default_due_days || $this->default_due_days == 0) {
            return 'Immediato';
        }
        return $this->default_due_days . ' giorni';
    }

    // Predefined payment methods italiane
    public static function getItalianDefaults(): array
    {
        return [
            [
                'code' => 'MP01',
                'description' => 'Contanti',
                'electronic_invoice_code' => 'MP01',
                'type' => 'immediate',
                'default_due_days' => 0,
                'sort_order' => 1
            ],
            [
                'code' => 'MP05', 
                'description' => 'Bonifico SEPA',
                'electronic_invoice_code' => 'MP05',
                'type' => 'deferred',
                'default_due_days' => 30,
                'sort_order' => 2
            ],
            [
                'code' => 'MP08',
                'description' => 'Carta di Credito/Debito',
                'electronic_invoice_code' => 'MP08', 
                'type' => 'immediate',
                'default_due_days' => 0,
                'sort_order' => 3
            ],
            [
                'code' => 'MP09',
                'description' => 'Addebito Diretto SEPA (SDD)',
                'electronic_invoice_code' => 'MP09',
                'type' => 'deferred',
                'default_due_days' => 14,
                'sort_order' => 4
            ],
            [
                'code' => 'MP02',
                'description' => 'Assegno',
                'electronic_invoice_code' => 'MP02',
                'type' => 'deferred', 
                'default_due_days' => 30,
                'sort_order' => 5
            ],
            [
                'code' => 'MP12',
                'description' => 'RiBA',
                'electronic_invoice_code' => 'MP12',
                'type' => 'deferred',
                'default_due_days' => 60,
                'sort_order' => 6
            ]
        ];
    }
}