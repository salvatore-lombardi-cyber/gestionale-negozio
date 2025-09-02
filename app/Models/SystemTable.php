<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_name',
        'code',
        'description',
        'extra_data',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'extra_data' => 'json',
    ];

    public static function getByTableName($tableName)
    {
        return static::where('table_name', $tableName)
                    ->where('active', true)
                    ->orderBy('sort_order')
                    ->get();
    }
}