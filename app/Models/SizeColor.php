<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Taglie e Colori (Card #8)
 * Gestione varianti prodotto
 */
class SizeColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'hex_value',
        'rgb_value',
        'pantone_code',
        'size_category',
        'size_system',
        'numeric_value',
        'eu_size',
        'us_size',
        'uk_size',
        'chest_cm',
        'waist_cm',
        'hip_cm',
        'seasonal',
        'season_tags',
        'price_modifier',
        'barcode_prefix',
        'sku_suffix',
        'default_stock_level',
        'icon',
        'css_class',
        'requires_approval',
        'restricted_markets',
        'compliance_notes',
        'metadata',
        'last_used_at',
        'usage_count',
        'sort_order',
        'active'
    ];

    protected $casts = [
        'numeric_value' => 'decimal:2',
        'chest_cm' => 'integer',
        'waist_cm' => 'integer',
        'hip_cm' => 'integer',
        'seasonal' => 'boolean',
        'season_tags' => 'array',
        'price_modifier' => 'decimal:2',
        'default_stock_level' => 'integer',
        'requires_approval' => 'boolean',
        'restricted_markets' => 'array',
        'metadata' => 'array',
        'last_used_at' => 'datetime',
        'usage_count' => 'integer',
        'sort_order' => 'integer',
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

    public function scopeSizes(Builder $query): Builder
    {
        return $query->where('type', 'size');
    }

    public function scopeColors(Builder $query): Builder
    {
        return $query->where('type', 'color');
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('size_category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getIsColorAttribute(): bool
    {
        return $this->type === 'color';
    }

    public function getIsSizeAttribute(): bool
    {
        return $this->type === 'size';
    }

    public function getColorSwatchAttribute(): ?string
    {
        if ($this->is_color && $this->hex_value) {
            return "<div style='width:20px;height:20px;background:{$this->hex_value};border:1px solid #ccc;display:inline-block;border-radius:3px;'></div>";
        }
        return null;
    }

    public function getTypeTranslatedAttribute(): string
    {
        return $this->type === 'size' ? 'Taglia' : 'Colore';
    }

    public function getSizeCategoryTranslatedAttribute(): string
    {
        $translations = [
            'NUMERIC' => 'Numerico',
            'LETTER' => 'Letterale (XS-XXL)',
            'EU' => 'Sistema Europeo',
            'US' => 'Sistema Americano',
            'UK' => 'Sistema Britannico',
            'IT' => 'Sistema Italiano',
            'FR' => 'Sistema Francese',
            'CUSTOM' => 'Personalizzato'
        ];
        
        return $translations[$this->size_category] ?? $this->size_category ?? 'N/A';
    }

    public function getFormattedPriceModifierAttribute(): string
    {
        if (!$this->price_modifier || $this->price_modifier == 0) {
            return 'Nessun modificatore';
        }
        
        $sign = $this->price_modifier > 0 ? '+' : '';
        return $sign . '€ ' . number_format($this->price_modifier, 2);
    }

    public function getSeasonTagsFormattedAttribute(): string
    {
        if (!$this->season_tags || !is_array($this->season_tags)) {
            return 'Sempre disponibile';
        }
        
        $seasonTranslations = [
            'spring' => 'Primavera',
            'summer' => 'Estate', 
            'autumn' => 'Autunno',
            'winter' => 'Inverno',
            'fall' => 'Autunno'
        ];
        
        $translatedSeasons = array_map(function($season) use ($seasonTranslations) {
            return $seasonTranslations[strtolower($season)] ?? ucfirst($season);
        }, $this->season_tags);
        
        return implode(', ', $translatedSeasons);
    }

    public function getMeasurementsFormattedAttribute(): string
    {
        if (!$this->is_size) {
            return 'N/A';
        }
        
        $measurements = [];
        
        if ($this->chest_cm) {
            $measurements[] = "Petto: {$this->chest_cm}cm";
        }
        
        if ($this->waist_cm) {
            $measurements[] = "Vita: {$this->waist_cm}cm";
        }
        
        if ($this->hip_cm) {
            $measurements[] = "Fianchi: {$this->hip_cm}cm";
        }
        
        return !empty($measurements) ? implode(', ', $measurements) : 'Misure non specificate';
    }

    public function getInternationalSizesAttribute(): array
    {
        return [
            'EU' => $this->eu_size ?? 'N/A',
            'US' => $this->us_size ?? 'N/A',
            'UK' => $this->uk_size ?? 'N/A'
        ];
    }

    // Business Logic Methods
    public function isAvailableInSeason(string $season): bool
    {
        if (!$this->seasonal || !$this->season_tags) {
            return true; // Sempre disponibile
        }
        
        return in_array(strtolower($season), array_map('strtolower', $this->season_tags));
    }

    public function requiresSpecialHandling(): bool
    {
        return $this->requires_approval || 
               !empty($this->restricted_markets) || 
               !empty($this->compliance_notes);
    }

    public function getEffectivePrice(float $basePrice): float
    {
        return $basePrice + ($this->price_modifier ?? 0);
    }

    public function generateBarcode(?string $productCode = null): string
    {
        $prefix = $this->barcode_prefix ?? 'SC';
        $suffix = $this->sku_suffix ?? str_pad($this->id, 4, '0', STR_PAD_LEFT);
        
        return $productCode ? "{$productCode}-{$prefix}{$suffix}" : "{$prefix}{$suffix}";
    }

    public function updateUsageStats(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    // OWASP Security Validations
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:size_colors,code' . ($id ? ",$id" : '')
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ\/]+$/'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'type' => [
                'required',
                'in:size,color'
            ],
            'hex_value' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
                'required_if:type,color'
            ],
            'rgb_value' => [
                'nullable',
                'regex:/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/'
            ],
            'pantone_code' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z0-9\s-]+$/i'
            ],
            'size_category' => [
                'nullable',
                'in:NUMERIC,LETTER,EU,US,UK,IT,FR,CUSTOM',
                'required_if:type,size'
            ],
            'size_system' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\s\-_]+$/'
            ],
            'numeric_value' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99'
            ],
            'eu_size' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Z0-9\/\-]+$/i'
            ],
            'us_size' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Z0-9\/\-]+$/i'
            ],
            'uk_size' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Z0-9\/\-]+$/i'
            ],
            'chest_cm' => [
                'nullable',
                'integer',
                'min:1',
                'max:300'
            ],
            'waist_cm' => [
                'nullable',
                'integer',
                'min:1',
                'max:300'
            ],
            'hip_cm' => [
                'nullable',
                'integer',
                'min:1',
                'max:300'
            ],
            'price_modifier' => [
                'nullable',
                'numeric',
                'min:-999999.99',
                'max:999999.99'
            ],
            'barcode_prefix' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/'
            ],
            'sku_suffix' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Z0-9_-]+$/'
            ],
            'default_stock_level' => [
                'nullable',
                'integer',
                'min:0',
                'max:99999'
            ],
            'icon' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^bi-[a-z0-9-]+$/'
            ],
            'css_class' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_]+$/'
            ],
            'compliance_notes' => [
                'nullable',
                'string',
                'max:500'
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999'
            ]
        ];
    }

    // Scopes aggiuntivi per fashion retail
    public function scopeSeasonal(Builder $query): Builder
    {
        return $query->where('seasonal', true);
    }

    public function scopeNonSeasonal(Builder $query): Builder
    {
        return $query->where('seasonal', false);
    }

    public function scopeRequiresApproval(Builder $query): Builder
    {
        return $query->where('requires_approval', true);
    }

    public function scopeByPantone(Builder $query, string $pantone): Builder
    {
        return $query->where('pantone_code', 'like', "%{$pantone}%");
    }

    public function scopeByHex(Builder $query, string $hex): Builder
    {
        return $query->where('hex_value', $hex);
    }

    public function scopeByMeasurements(Builder $query, ?int $chest = null, ?int $waist = null, ?int $hip = null): Builder
    {
        if ($chest) {
            $query->where('chest_cm', '>=', $chest - 5)->where('chest_cm', '<=', $chest + 5);
        }
        
        if ($waist) {
            $query->where('waist_cm', '>=', $waist - 5)->where('waist_cm', '<=', $waist + 5);
        }
        
        if ($hip) {
            $query->where('hip_cm', '>=', $hip - 5)->where('hip_cm', '<=', $hip + 5);
        }
        
        return $query;
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->where('usage_count', '>', 10)->orderBy('usage_count', 'desc');
    }

    public function scopeRecentlyUsed(Builder $query): Builder
    {
        return $query->whereNotNull('last_used_at')
                    ->where('last_used_at', '>=', now()->subDays(30))
                    ->orderBy('last_used_at', 'desc');
    }
}