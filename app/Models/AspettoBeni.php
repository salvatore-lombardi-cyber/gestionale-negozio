<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class AspettoBeni extends Model
{
    use SoftDeletes;

    protected $table = 'aspetto_beni';

    protected $fillable = [
        'codice_aspetto',
        'descrizione',
        'descrizione_estesa',
        'tipo_confezionamento',
        'utilizzabile_ddt',
        'utilizzabile_fatture',
        'attivo',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'utilizzabile_ddt' => 'boolean',
        'utilizzabile_fatture' => 'boolean',
        'attivo' => 'boolean',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // OWASP: Validazioni server-side rigorose
    public static function validationRules($id = null): array
    {
        return [
            'codice_aspetto' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9_-]+$/', // Solo caratteri alfanumerici maiuscoli, underscore e trattini
                Rule::unique('aspetto_beni')->ignore($id)
            ],
            'descrizione' => [
                'required',
                'string',
                'max:50'
            ],
            'descrizione_estesa' => [
                'nullable',
                'string',
                'max:255'
            ],
            'tipo_confezionamento' => [
                'required',
                'in:primario,secondario,terziario'
            ],
            'utilizzabile_ddt' => 'boolean',
            'utilizzabile_fatture' => 'boolean',
            'attivo' => 'boolean'
        ];
    }

    // OWASP: Sanitizzazione automatica input
    public function setCodiceAspettoAttribute($value): void
    {
        $this->attributes['codice_aspetto'] = strtoupper(trim($value));
    }

    public function setDescrizioneAttribute($value): void
    {
        $this->attributes['descrizione'] = trim($value);
    }

    public function setDescrizioneEstesaAttribute($value): void
    {
        $this->attributes['descrizione_estesa'] = $value ? trim($value) : null;
    }

    // Relazioni per audit trail
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope per query ottimizzate
    public function scopeAttivi($query)
    {
        return $query->where('attivo', true);
    }

    public function scopePerDdt($query)
    {
        return $query->where('utilizzabile_ddt', true);
    }

    public function scopePerFatture($query)
    {
        return $query->where('utilizzabile_fatture', true);
    }

    public function scopeByTipoConfezionamento($query, $tipo)
    {
        return $query->where('tipo_confezionamento', $tipo);
    }

    // Metodi business logic
    public function isUtilizzabilePer(string $documento): bool
    {
        return match($documento) {
            'ddt' => $this->utilizzabile_ddt && $this->attivo,
            'fattura' => $this->utilizzabile_fatture && $this->attivo,
            default => false
        };
    }

    public function getDescrizioneCompletaAttribute(): string
    {
        $desc = $this->descrizione;
        if ($this->descrizione_estesa) {
            $desc .= ' - ' . $this->descrizione_estesa;
        }
        return $desc;
    }

    // Override per audit automatico
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
        
        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
