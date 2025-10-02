<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Relazione con PrintAssociation
     */
    public function printAssociations(): HasMany
    {
        return $this->hasMany(PrintAssociation::class, 'template_id');
    }

    /**
     * Format modified_at for display
     */
    public function getFormattedModifiedAtAttribute()
    {
        return $this->modified_at ? $this->modified_at->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Verifica se è associato a qualche tipo documento
     */
    public function hasAssociatedDocuments()
    {
        return $this->printAssociations()->where('active', true)->exists();
    }
}