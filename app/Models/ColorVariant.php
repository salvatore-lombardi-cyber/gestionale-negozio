<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Colori Varianti (Card #10)
 * Palette colori per varianti
 */
class ColorVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'hex_primary',
        'hex_secondary',
        'pantone_code',
        'rgb_values',
        'cmyk_values',
        'season',
        'trend_level',
        'active'
    ];

    protected $casts = [
        'rgb_values' => 'array',
        'cmyk_values' => 'array',
        'trend_level' => 'integer',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeBySeason(Builder $query, string $season): Builder
    {
        return $query->where('season', $season);
    }

    public function getColorPreviewAttribute(): string
    {
        $gradient = $this->hex_secondary 
            ? "background: linear-gradient(45deg, {$this->hex_primary}, {$this->hex_secondary});"
            : "background: {$this->hex_primary};";
            
        return "<div style='width:30px;height:30px;{$gradient}border:1px solid #ccc;border-radius:4px;display:inline-block;'></div>";
    }
}