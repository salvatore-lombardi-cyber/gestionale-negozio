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

    /**
     * Ottieni i preferiti con i dettagli delle tabelle V2 - SENZA LIMITE
     * Sistema migliorato per V2 del gestionale senza limiti fissi
     */
    public static function getFavoritesWithDetailsV2($userId)
    {
        return static::forUser($userId)
            ->orderByUsage()
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'uuid' => $favorite->uuid,
                    'table_objname' => $favorite->table_objname,
                    'click_count' => $favorite->click_count,
                    'last_accessed_at' => $favorite->last_accessed_at,
                    'sort_order' => $favorite->sort_order,
                    'is_frequent' => $favorite->click_count >= 5,
                    'url' => route('configurations.gestione-tabelle.tabella', $favorite->table_objname)
                ];
            })
            ->sortByDesc(function ($item) {
                // Ordinamento intelligente: frequenti prima, poi per click count, poi per recenza
                return ($item['is_frequent'] ? 10000 : 0) + $item['click_count'];
            })
            ->values();
    }

    /**
     * Ottieni le tabelle più utilizzate per statistiche
     */
    public static function getTopTablesV2($userId, $limit = 10)
    {
        return static::forUser($userId)
            ->orderByDesc('click_count')
            ->orderByDesc('last_accessed_at')
            ->limit($limit)
            ->get(['table_objname', 'click_count', 'last_accessed_at'])
            ->map(function ($item) {
                return [
                    'table' => $item->table_objname,
                    'clicks' => $item->click_count,
                    'last_used' => $item->last_accessed_at,
                    'is_frequent' => $item->click_count >= 5
                ];
            });
    }

    /**
     * Ottieni statistiche utilizzo complete
     */
    public static function getUserStatsV2($userId)
    {
        $stats = static::forUser($userId)
            ->selectRaw('
                COUNT(*) as total_tables,
                SUM(click_count) as total_clicks,
                AVG(click_count) as avg_clicks,
                COUNT(CASE WHEN click_count >= 5 THEN 1 END) as frequent_count,
                COUNT(CASE WHEN click_count >= 10 THEN 1 END) as very_frequent_count,
                MAX(click_count) as max_clicks,
                MAX(last_accessed_at) as last_activity
            ')
            ->first();

        return [
            'summary' => $stats,
            'activity_level' => static::calculateActivityLevel($stats->total_clicks, $stats->total_tables),
            'recommendations_count' => max(0, 3 - $stats->frequent_count) // Suggerisci tabelle se < 3 frequenti
        ];
    }

    /**
     * Calcola il livello di attività dell'utente
     */
    private static function calculateActivityLevel($totalClicks, $totalTables)
    {
        if ($totalClicks == 0) return 'nuovo';
        if ($totalClicks < 20) return 'base';
        if ($totalClicks < 50) return 'intermedio';
        if ($totalClicks < 100) return 'avanzato';
        return 'esperto';
    }
}