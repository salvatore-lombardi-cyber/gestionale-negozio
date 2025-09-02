<?php

namespace App\Models\SystemTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Classe base per tutte le tabelle di sistema
 * Implementa funzionalità comuni e pattern enterprise
 */
abstract class BaseSystemTable extends Model
{
    use HasFactory, SoftDeletes;

    // Campi comuni a tutte le tabelle di sistema
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'active'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Scopes comuni
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc');
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }

    // Cache key standard
    public function getCacheKey(): string
    {
        return strtolower(class_basename($this)) . "_{$this->id}";
    }

    // Validazione codice univoco
    public static function isCodeUnique(string $code, ?int $excludeId = null): bool
    {
        $query = static::where('code', $code)->where('active', true);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->doesntExist();
    }

    // Ottieni prossimo sort_order
    public static function getNextSortOrder(): int
    {
        return static::max('sort_order') + 1;
    }

    // Metodo factory per creazione rapida
    public static function createSystemEntry(array $data): static
    {
        $data['sort_order'] = $data['sort_order'] ?? static::getNextSortOrder();
        $data['active'] = $data['active'] ?? true;
        
        return static::create($data);
    }
}

// Modelli specifici usando la classe base
class Condition extends BaseSystemTable
{
    protected $table = 'conditions';
}

class FixedPriceDenomination extends BaseSystemTable
{
    protected $table = 'fixed_price_denominations';
    
    protected $fillable = [
        'code', 'name', 'description', 'currency_id', 'base_amount', 'sort_order', 'active'
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'currency_id' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class Deposit extends BaseSystemTable
{
    protected $table = 'deposits';
    
    protected $fillable = [
        'code', 'name', 'description', 'address', 'city', 'postal_code', 
        'phone', 'email', 'manager', 'capacity_m3', 'is_primary', 'sort_order', 'active'
    ];

    protected $casts = [
        'capacity_m3' => 'decimal:2',
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class PriceList extends BaseSystemTable
{
    protected $table = 'price_lists';
    
    protected $fillable = [
        'code', 'name', 'description', 'currency_id', 'valid_from', 'valid_to',
        'discount_percentage', 'is_default', 'customer_category_id', 'sort_order', 'active'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'discount_percentage' => 'decimal:2',
        'is_default' => 'boolean',
        'currency_id' => 'integer',
        'customer_category_id' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class ShippingTerm extends BaseSystemTable
{
    protected $table = 'shipping_terms';
    
    protected $fillable = [
        'code', 'name', 'description', 'incoterm', 'responsibility', 'insurance_required',
        'sort_order', 'active'
    ];

    protected $casts = [
        'insurance_required' => 'boolean',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class MerchandisingSector extends BaseSystemTable
{
    protected $table = 'merchandising_sectors';
}

class SizeVariant extends BaseSystemTable
{
    protected $table = 'size_variants';
    
    protected $fillable = [
        'code', 'name', 'description', 'size_category', 'numeric_value', 
        'international_equivalent', 'sort_order', 'active'
    ];

    protected $casts = [
        'numeric_value' => 'decimal:2',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class SizeType extends BaseSystemTable
{
    protected $table = 'size_types';
    
    protected $fillable = [
        'code', 'name', 'description', 'measurement_unit', 'category',
        'min_value', 'max_value', 'sort_order', 'active'
    ];

    protected $casts = [
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class PaymentType extends BaseSystemTable
{
    protected $table = 'payment_types';
    
    protected $fillable = [
        'code', 'name', 'description', 'requires_bank_details', 'default_days',
        'is_immediate', 'sort_order', 'active'
    ];

    protected $casts = [
        'requires_bank_details' => 'boolean',
        'default_days' => 'integer',
        'is_immediate' => 'boolean',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class Transport extends BaseSystemTable
{
    protected $table = 'transports';
    
    protected $fillable = [
        'code', 'name', 'description', 'transport_type', 'estimated_days',
        'tracking_available', 'insurance_included', 'sort_order', 'active'
    ];

    protected $casts = [
        'estimated_days' => 'integer',
        'tracking_available' => 'boolean',
        'insurance_included' => 'boolean',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class TransportCarrier extends BaseSystemTable
{
    protected $table = 'transport_carriers';
    
    protected $fillable = [
        'code', 'name', 'description', 'company_name', 'vat_number', 'phone',
        'email', 'website', 'tracking_url', 'sort_order', 'active'
    ];
}

class Location extends BaseSystemTable
{
    protected $table = 'locations';
    
    protected $fillable = [
        'code', 'name', 'description', 'address', 'city', 'postal_code',
        'country', 'latitude', 'longitude', 'timezone', 'sort_order', 'active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class UnitOfMeasure extends BaseSystemTable
{
    protected $table = 'unit_of_measures';
    
    protected $fillable = [
        'code', 'name', 'description', 'symbol', 'category', 'base_unit_id',
        'conversion_factor', 'decimal_places', 'sort_order', 'active'
    ];

    protected $casts = [
        'base_unit_id' => 'integer',
        'conversion_factor' => 'decimal:6',
        'decimal_places' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}

class Zone extends BaseSystemTable
{
    protected $table = 'zones';
    
    protected $fillable = [
        'code', 'name', 'description', 'zone_type', 'parent_zone_id',
        'tax_rate_id', 'shipping_cost', 'sort_order', 'active'
    ];

    protected $casts = [
        'parent_zone_id' => 'integer',
        'tax_rate_id' => 'integer',
        'shipping_cost' => 'decimal:2',
        'sort_order' => 'integer',
        'active' => 'boolean'
    ];
}