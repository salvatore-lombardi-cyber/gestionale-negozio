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
        'name',
        'description',
        'movement_type',
        'affects_cost',
        'requires_document',
        'auto_calculate_cost',
        'fiscal_relevant',
        'fiscal_code',
        'category',
        'priority_level',
        'approval_required',
        'notify_threshold',
        'color_hex',
        'icon',
        'default_location',
        'auto_assign_lot',
        'usage_count',
        'last_used_at',
        'created_by',
        'updated_by',
        'metadata',
        'compliance_notes',
        'active'
    ];

    protected $casts = [
        'affects_cost' => 'boolean',
        'requires_document' => 'boolean',
        'auto_calculate_cost' => 'boolean',
        'fiscal_relevant' => 'boolean',
        'approval_required' => 'boolean',
        'auto_assign_lot' => 'boolean',
        'active' => 'boolean',
        'notify_threshold' => 'decimal:2',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'metadata' => 'array',
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

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority_level', $priority);
    }

    public function scopeRequiresApproval(Builder $query): Builder
    {
        return $query->where('approval_required', true);
    }

    public function scopeRecentlyUsed(Builder $query, int $days = 30): Builder
    {
        return $query->where('last_used_at', '>=', now()->subDays($days));
    }

    public function scopeMostUsed(Builder $query, int $limit = 10): Builder
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
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

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'ORDINARY' => 'Ordinaria',
            'INVENTORY' => 'Inventario',
            'PRODUCTION' => 'Produzione',
            'LOSS' => 'Perdita',
            'TRANSFER' => 'Trasferimento',
            'RETURN' => 'Reso',
            'SAMPLE' => 'Campione',
            default => 'Sconosciuta'
        };
    }

    public function getPriorityLabel(): string
    {
        return match($this->priority_level) {
            'LOW' => 'Bassa',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'CRITICAL' => 'Critica',
            default => 'Non definita'
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match($this->priority_level) {
            'LOW' => 'badge bg-secondary',
            'MEDIUM' => 'badge bg-primary',
            'HIGH' => 'badge bg-warning',
            'CRITICAL' => 'badge bg-danger',
            default => 'badge bg-light text-dark'
        };
    }

    public function getCategoryBadgeClass(): string
    {
        return match($this->category) {
            'ORDINARY' => 'badge bg-primary',
            'INVENTORY' => 'badge bg-info',
            'PRODUCTION' => 'badge bg-success',
            'LOSS' => 'badge bg-danger',
            'TRANSFER' => 'badge bg-warning',
            'RETURN' => 'badge bg-secondary',
            'SAMPLE' => 'badge bg-dark',
            default => 'badge bg-light text-dark'
        };
    }

    // Validation rules per OWASP compliance
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:warehouse_causes,code|regex:/^[A-Z0-9_-]+$/',
            'description' => 'required|string|max:255|min:3',
            'movement_type' => 'required|in:in,out,adjustment',
            'affects_cost' => 'boolean',
            'requires_document' => 'boolean',
            'auto_calculate_cost' => 'boolean',
            'fiscal_relevant' => 'boolean',
            'fiscal_code' => 'nullable|string|max:10|regex:/^[A-Z0-9]+$/',
            'category' => 'required|in:ORDINARY,INVENTORY,PRODUCTION,LOSS,TRANSFER,RETURN,SAMPLE',
            'priority_level' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'approval_required' => 'boolean',
            'notify_threshold' => 'nullable|numeric|min:0|max:999999.99',
            'color_hex' => 'required|string|size:7|regex:/^#[A-Fa-f0-9]{6}$/',
            'icon' => 'required|string|max:50|regex:/^[a-z0-9-]+$/',
            'default_location' => 'nullable|string|max:100',
            'auto_assign_lot' => 'boolean',
            'compliance_notes' => 'nullable|string|max:500',
            'active' => 'boolean'
        ];
    }

    // Validation rules per update (ignora unique per ID corrente)
    public static function validationRulesForUpdate(int $id): array
    {
        $rules = self::validationRules();
        $rules['code'] = 'required|string|max:20|unique:warehouse_causes,code,' . $id . '|regex:/^[A-Z0-9_-]+$/';
        return $rules;
    }

    // Incrementa contatore utilizzi con audit trail
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    // Verifica se richiede approvazione basata su soglia
    public function requiresApprovalForAmount(float $amount): bool
    {
        if (!$this->approval_required) {
            return false;
        }

        if ($this->notify_threshold === null) {
            return true;
        }

        return $amount >= $this->notify_threshold;
    }

    // Relazioni per audit trail
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}