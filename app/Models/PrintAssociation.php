<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Model per le associazioni template-documenti
 * Collega i template di stampa ai tipi di documento
 */
class PrintAssociation extends Model
{
    use HasFactory;

    protected $table = 'associazionestampe';

    protected $fillable = [
        'uuid',
        'document_type_id',
        'template_id',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot del model per generare UUID automatico
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relazione con DocumentType
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * Relazione con Template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Scope per associazioni attive
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Trova template associato a un tipo documento
     */
    public static function getTemplateForDocument($documentTypeId)
    {
        return static::where('document_type_id', $documentTypeId)
            ->where('active', true)
            ->with('template')
            ->first()?->template;
    }

    /**
     * Verifica se esiste associazione per tipo documento
     */
    public static function hasAssociationForDocument($documentTypeId)
    {
        return static::where('document_type_id', $documentTypeId)
            ->where('active', true)
            ->exists();
    }
}