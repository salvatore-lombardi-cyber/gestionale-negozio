<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserFavoriteTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'table_objname',
        'sort_order',
        'click_count',
        'last_accessed_at'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'click_count' => 'integer',
        'last_accessed_at' => 'datetime',
        'user_id' => 'integer'
    ];

    /**
     * Boot model per gestire UUID automaticamente
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relazione con User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con SystemPagebuilder per ottenere i dettagli della tabella
     */
    public function systemTable()
    {
        return $this->hasOne(SystemPagebuilder::class, 'objname', 'table_objname');
    }

    /**
     * Scope per ottenere i preferiti di un utente ordinati
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope per ordinamento per utilizzo (click + recenza)
     */
    public function scopeOrderByUsage($query)
    {
        return $query->orderByDesc('click_count')
                     ->orderByDesc('last_accessed_at')
                     ->orderBy('sort_order');
    }

    /**
     * Incrementa il contatore di utilizzo
     */
    public function incrementUsage()
    {
        $this->increment('click_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Ottieni i preferiti con i dettagli delle tabelle
     */
    public static function getFavoritesWithDetails($userId, $limit = 6)
    {
        return static::forUser($userId)
            ->with('systemTable')
            ->orderByUsage()
            ->limit($limit)
            ->get();
    }
}