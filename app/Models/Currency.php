<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];
}