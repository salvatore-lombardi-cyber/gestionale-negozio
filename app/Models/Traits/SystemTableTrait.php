<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait SystemTableTrait
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name', 
        'description',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Methods
    public static function isCodeUnique(string $code, ?int $excludeId = null): bool
    {
        $query = static::where('code', $code);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->count() === 0;
    }

    public static function createSystemEntry(array $data): static
    {
        return static::create($data);
    }
}