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
        'active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'level' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
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

    // Validazioni OWASP
    public static function validationRules($id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_-]+$/',
                'unique:product_categories,code' . ($id ? ',' . $id : '')
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-_àèéìíîòóùúÀÈÉÌÍÎÒÓÙÚ]+$/'
            ],
            'description' => [
                'nullable',
                'string',
                'max:255'
            ],
            'parent_id' => [
                'nullable',
                'exists:product_categories,id'
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999'
            ],
            'color_hex' => [
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6})$/'
            ],
            'icon' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z0-9\-]+$/'
            ],
            'active' => [
                'boolean'
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        return $this->children()->count() === 0;
    }

    public function updatePath(): void
    {
        $path = collect();
        
        if ($this->parent) {
            $path->push($this->parent->getFullPath());
        }
        
        $path->push($this->name);
        
        // Usa updateQuietly per evitare loop infiniti negli eventi
        $this->updateQuietly([
            'path' => $path->implode('/'),
            'level' => $this->parent ? $this->parent->level + 1 : 0
        ]);
    }

    public function getIndentedName(): string
    {
        return str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $this->level) . $this->name;
    }

    public function hasCircularReference($parent_id): bool
    {
        if (!$parent_id) {
            return false;
        }

        $parent = ProductCategory::find($parent_id);
        
        while ($parent) {
            if ($parent->id === $this->id) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (auth()->check()) {
                $category->created_by = auth()->id();
            }
        });

        static::updating(function ($category) {
            if (auth()->check()) {
                $category->updated_by = auth()->id();
            }
        });

        static::saved(function ($category) {
            if ($category->isDirty('name') || $category->isDirty('parent_id')) {
                $category->updatePath();
                
                // Aggiorna anche i figli
                foreach ($category->allChildren as $child) {
                    $child->updatePath();
                }
            }
        });
    }
}