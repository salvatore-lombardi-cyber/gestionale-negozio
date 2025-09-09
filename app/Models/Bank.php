<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * Model per Banche (Card #4)
 * Anagrafica istituti bancari e coordinate
 */
class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name', 
        'description',
        'abi_code',
        'bic_swift',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'is_italian',
        'active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_italian' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relazioni
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeItalian(Builder $query): Builder
    {
        return $query->where('is_italian', true);
    }

    public function scopeByAbi(Builder $query, string $abi): Builder
    {
        return $query->where('abi_code', $abi);
    }

    // OWASP: Validazioni server-side rigorose
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9_-]+$/', // Codice alfanumerico maiuscolo
                Rule::unique('banks')->ignore($id)
            ],
            'name' => [
                'required',
                'string',
                'max:100'
            ],
            'description' => [
                'nullable',
                'string', 
                'max:255'
            ],
            'abi_code' => [
                'nullable',
                'string',
                'size:5',
                'regex:/^\d{5}$/' // Solo 5 cifre per ABI italiano
            ],
            'bic_swift' => [
                'nullable',
                'string',
                'min:8',
                'max:11',
                'regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/' // Formato BIC/SWIFT
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:100'
            ],
            'website' => [
                'nullable',
                'url',
                'max:255'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],
            'is_italian' => 'boolean',
            'active' => 'boolean'
        ];
    }

    // OWASP: Sanitizzazione automatica input
    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim($value));
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim($value);
    }

    public function setAbiCodeAttribute($value): void
    {
        $this->attributes['abi_code'] = $value ? str_pad(trim($value), 5, '0', STR_PAD_LEFT) : null;
    }

    public function setBicSwiftAttribute($value): void
    {
        $this->attributes['bic_swift'] = $value ? strtoupper(trim($value)) : null;
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    // Relazioni per audit trail
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address}, {$this->postal_code} {$this->city} ({$this->country})");
    }

    // Metodi business logic
    public function isItalianBank(): bool
    {
        return $this->is_italian && !empty($this->abi_code);
    }

    // Override per audit automatico
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
        
        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}