<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model per Condizioni (Card #11)
 * Semplice tabella con solo descrizione
 */
class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('description');
    }

    public function scopeByDescription(Builder $query, string $description): Builder
    {
        return $query->where('description', 'LIKE', "%{$description}%");
    }

    // Validation rules per OWASP compliance
    public static function validationRules($id = null): array
    {
        return [
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:conditions,description' . ($id ? ',' . $id : '')
            ]
        ];
    }

    // Metodi business logic
    public function canBeDeleted(): bool
    {
        // Controlla se ci sono documenti associati a questa condizione
        // return !$this->documents()->exists();
        return true; // Per ora non controlliamo relazioni
    }

    // Relazioni future (quando sarà implementata la gestione documenti)
    // public function documents()
    // {
    //     return $this->hasMany(Document::class, 'condition_id');
    // }
}
