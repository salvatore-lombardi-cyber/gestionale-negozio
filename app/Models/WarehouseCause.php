<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Causali di Magazzino (Card #9)
 * Motivi movimenti di magazzino
 */
class WarehouseCause extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'movement_type', // 'in', 'out', 'adjustment'
        'affects_cost',
        'requires_document',
        'auto_calculate_cost',
        'fiscal_relevant',
        'active'
    ];

    protected $casts = [
        'affects_cost' => 'boolean',
        'requires_document' => 'boolean',
        'auto_calculate_cost' => 'boolean',
        'fiscal_relevant' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeByMovementType(Builder $query, string $type): Builder
    {
        return $query->where('movement_type', $type);
    }

    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('movement_type', 'in');
    }

    public function scopeOutgoing(Builder $query): Builder
    {
        return $query->where('movement_type', 'out');
    }

    public function scopeAdjustments(Builder $query): Builder
    {
        return $query->where('movement_type', 'adjustment');
    }

    public function scopeFiscalRelevant(Builder $query): Builder
    {
        return $query->where('fiscal_relevant', true);
    }

    // Accessors
    public function getMovementTypeLabel(): string
    {
        return match($this->movement_type) {
            'in' => 'Entrata',
            'out' => 'Uscita',
            'adjustment' => 'Rettifica',
            default => 'Sconosciuto'
        };
    }

    public function getMovementIcon(): string
    {
        return match($this->movement_type) {
            'in' => 'bi-arrow-down-circle-fill text-success',
            'out' => 'bi-arrow-up-circle-fill text-danger',
            'adjustment' => 'bi-arrow-repeat text-warning',
            default => 'bi-question-circle'
        };
    }
}