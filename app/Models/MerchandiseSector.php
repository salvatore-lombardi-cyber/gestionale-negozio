<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchandiseSector extends Model
{
    use SoftDeletes;
    
    // OWASP: Specifica tabella corretta
    protected $table = 'merchandise_sectors';

    // OWASP: Mass Assignment Protection - Solo campi specifici
    protected $fillable = [
        'code',
        'name', 
        'description',
        'category',
        'requires_certifications',
        'certifications',
        'average_margin',
        'risk_level',
        'seasonal',
        'active',
        'sort_order'
    ];

    // OWASP: Campi protetti da mass assignment
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'requires_certifications' => 'boolean',
        'certifications' => 'array',
        'average_margin' => 'decimal:2',
        'seasonal' => 'boolean',
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByRiskLevel($query, $riskLevel)
    {
        return $query->where('risk_level', $riskLevel);
    }

    public function scopeSeasonal($query, $seasonal = true)
    {
        return $query->where('seasonal', $seasonal);
    }

    public function scopeRequiresCertifications($query, $requires = true)
    {
        return $query->where('requires_certifications', $requires);
    }

    public function getFormattedCategoryAttribute()
    {
        return match($this->category) {
            'alimentare' => 'Alimentare e Bevande',
            'moda' => 'Moda e Abbigliamento',
            'elettronica' => 'Elettronica e Tecnologia',
            'casa' => 'Casa e Arredamento',
            'salute' => 'Salute e Farmaceutici',
            'bellezza' => 'Bellezza e Cosmetici',
            'sport' => 'Sport e Fitness',
            'tempo_libero' => 'Tempo Libero',
            'automotive' => 'Automotive',
            'servizi' => 'Servizi',
            'industriale' => 'Industriale',
            'generale' => 'Generale',
            default => ucfirst($this->category)
        };
    }

    public function getFormattedRiskLevelAttribute()
    {
        return match($this->risk_level) {
            'basso' => 'Rischio Basso',
            'medio' => 'Rischio Medio', 
            'alto' => 'Rischio Alto',
            default => 'Non specificato'
        };
    }

    public function getFormattedMarginAttribute()
    {
        return $this->average_margin ? number_format($this->average_margin, 2) . '%' : 'N/D';
    }

    public function getCertificationsListAttribute()
    {
        return $this->certifications ? implode(', ', $this->certifications) : 'Nessuna';
    }

    public function getRiskColorAttribute()
    {
        return match($this->risk_level) {
            'basso' => 'success',
            'medio' => 'warning',
            'alto' => 'danger',
            default => 'secondary'
        };
    }

    // OWASP: Input Sanitization Methods
    
    /**
     * Sanitizza il codice per prevenire injection attacks
     */
    public function setCodeAttribute($value)
    {
        // OWASP: Sanitizzazione rigorosa del codice
        $sanitized = strtoupper(trim($value));
        $sanitized = preg_replace('/[^A-Z0-9_-]/', '', $sanitized);
        $this->attributes['code'] = substr($sanitized, 0, 20);
    }

    /**
     * Sanitizza il nome per prevenire XSS
     */
    public function setNameAttribute($value)
    {
        // OWASP: Sanitizzazione del nome
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\']/', '', $sanitized);
        $this->attributes['name'] = substr($sanitized, 0, 150);
    }

    /**
     * Sanitizza la descrizione per prevenire XSS
     */
    public function setDescriptionAttribute($value)
    {
        if ($value === null) {
            $this->attributes['description'] = null;
            return;
        }

        // OWASP: Sanitizzazione della descrizione
        $sanitized = trim(strip_tags($value));
        $sanitized = preg_replace('/[<>"\']/', '', $sanitized);
        $this->attributes['description'] = substr($sanitized, 0, 1000);
    }

    /**
     * Validazione rigorosa della categoria
     */
    public function setCategoryAttribute($value)
    {
        $allowedCategories = [
            'alimentare', 'moda', 'elettronica', 'casa', 'salute',
            'bellezza', 'sport', 'tempo_libero', 'automotive',
            'servizi', 'industriale', 'generale'
        ];

        // OWASP: Whitelist validation
        if (in_array($value, $allowedCategories, true)) {
            $this->attributes['category'] = $value;
        } else {
            $this->attributes['category'] = 'generale'; // Default sicuro
        }
    }

    /**
     * Sanitizza array certificazioni
     */
    public function setCertificationsAttribute($value)
    {
        if (!is_array($value)) {
            $this->attributes['certifications'] = json_encode([]);
            return;
        }

        // OWASP: Sanitizzazione array certificazioni
        $sanitizedCerts = [];
        $maxCertifications = 10;
        $count = 0;

        foreach ($value as $cert) {
            if ($count >= $maxCertifications) break;
            
            $sanitized = strtoupper(trim(strip_tags($cert)));
            $sanitized = preg_replace('/[^A-Z0-9\s\-_]/', '', $sanitized);
            
            if (!empty($sanitized) && strlen($sanitized) <= 100) {
                $sanitizedCerts[] = $sanitized;
                $count++;
            }
        }

        $this->attributes['certifications'] = json_encode($sanitizedCerts);
    }

    /**
     * Validazione margine medio
     */
    public function setAverageMarginAttribute($value)
    {
        if ($value === null) {
            $this->attributes['average_margin'] = null;
            return;
        }

        // OWASP: Validazione numerica rigorosa
        $margin = floatval($value);
        
        if ($margin < 0) {
            $margin = 0;
        } elseif ($margin > 99.99) {
            $margin = 99.99;
        }

        $this->attributes['average_margin'] = round($margin, 2);
    }

    /**
     * Validazione livello rischio
     */
    public function setRiskLevelAttribute($value)
    {
        $allowedLevels = ['basso', 'medio', 'alto'];
        
        // OWASP: Whitelist validation
        if (in_array($value, $allowedLevels, true)) {
            $this->attributes['risk_level'] = $value;
        } else {
            $this->attributes['risk_level'] = 'medio'; // Default sicuro
        }
    }

    // OWASP: Security Helper Methods

    /**
     * Verifica se i dati sono stati modificati di recente (anti-tampering)
     */
    public function isRecentlyModified($minutes = 5)
    {
        return $this->updated_at->diffInMinutes(now()) <= $minutes;
    }

    /**
     * Log delle modifiche per audit trail
     */
    protected static function booted()
    {
        // OWASP: Audit Trail
        static::creating(function ($model) {
            \Log::info('Creating MerchandiseSector', [
                'code' => $model->code,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::updating(function ($model) {
            \Log::info('Updating MerchandiseSector', [
                'id' => $model->id,
                'code' => $model->code,
                'changes' => $model->getDirty(),
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });

        static::deleting(function ($model) {
            \Log::warning('Deleting MerchandiseSector', [
                'id' => $model->id,
                'code' => $model->code,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
        });
    }
}
