<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model per i tipi di documento
 * Rappresenta i diversi tipi di documento (DDT, Fatture, etc.)
 */
class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'tipidocumenti';

    protected $fillable = [
        'codice',
        'tipo_documento',
        'descrizione',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relazione con PrintAssociation
     */
    public function printAssociations(): HasMany
    {
        return $this->hasMany(PrintAssociation::class, 'document_type_id');
    }

    /**
     * Relazione con Template associato
     */
    public function associatedTemplate()
    {
        return $this->hasOneThrough(
            Template::class,
            PrintAssociation::class,
            'document_type_id', // Foreign key su print_associations
            'id', // Foreign key su templates
            'id', // Local key su document_types
            'template_id' // Local key su print_associations
        )->where('associazionestampe.active', true);
    }

    /**
     * Scope per tipi documento attivi
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope per ordinamento
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('codice');
    }

    /**
     * Ottieni template associato a questo tipo documento
     */
    public function getAssociatedTemplateAttribute()
    {
        return PrintAssociation::getTemplateForDocument($this->id);
    }

    /**
     * Verifica se ha template associato
     */
    public function hasAssociatedTemplate()
    {
        return PrintAssociation::hasAssociationForDocument($this->id);
    }
}