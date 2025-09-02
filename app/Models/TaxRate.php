<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'rate',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'rate' => 'decimal:2',
    ];
}