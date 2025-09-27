<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model per Categorie Articoli - Versione Semplificata
 * Gestione categorie con solo codice e descrizione
 */
class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    // Campi fillable semplificati
    protected $fillable = [
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validazione semplificata
    public static function validationRules(): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:product_categories,code',
            'description' => 'required|string|max:500|min:1'
        ];
    }

    public static function validationRulesForUpdate(int $id): array
    {
        return [
            'code' => 'required|string|max:20|min:1|unique:product_categories,code,' . $id,
            'description' => 'required|string|max:500|min:1'
        ];
    }
}