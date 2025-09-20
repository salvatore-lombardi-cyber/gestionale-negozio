<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingTerm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name', 
        'description',
        'incoterm_code',
        'type',
        'customer_pays_shipping',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'customer_pays_shipping' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query) 
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getFormattedTypeAttribute()
    {
        return match($this->type) {
            'factory' => 'Ritiro in fabbrica',
            'delivery' => 'Consegna a domicilio',
            'mixed' => 'Termini misti',
            default => 'Non specificato'
        };
    }
}
