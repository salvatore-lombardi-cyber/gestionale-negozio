<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Banche (Card #4)
 * Anagrafica istituti bancari e coordinate
 */
class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
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
        'active'
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

    // Accessors
    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address}, {$this->postal_code} {$this->city} ({$this->country})");
    }
}