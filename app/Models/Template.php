<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'created_by',
        'modified_at',
    ];

    protected $casts = [
        'modified_at' => 'datetime',
    ];

    /**
     * Get the user who created this template
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Format modified_at for display
     */
    public function getFormattedModifiedAtAttribute()
    {
        return $this->modified_at ? $this->modified_at->format('d/m/Y H:i') : 'N/A';
    }
}