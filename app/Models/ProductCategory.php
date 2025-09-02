<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Categorie Articoli (Card #5)
 * Classificazione prodotti per categoria
 */
class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'parent_id',
        'level',
        'path',
        'sort_order',
        'color_hex',
        'icon',
        'active'
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'level' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relazioni gerarchiche
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeRootLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeByLevel(Builder $query, int $level): Builder
    {
        return $query->where('level', $level);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Metodi per gerarchia
    public function getAncestors()
    {
        $ancestors = collect();
        $category = $this;
        
        while ($category->parent) {
            $category = $category->parent;
            $ancestors->prepend($category);
        }
        
        return $ancestors;
    }

    public function getFullPath(): string
    {
        return $this->path ?: $this->name;
    }

    public function isParentOf(ProductCategory $category): bool
    {
        return $category->path && str_starts_with($category->path, $this->path . '/');
    }
}