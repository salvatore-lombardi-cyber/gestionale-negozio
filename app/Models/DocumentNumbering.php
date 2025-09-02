<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentNumbering extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'current_number',
        'prefix',
        'suffix',
        'use_year',
        'use_month',
        'separator',
        'reset_frequency',
    ];

    protected $casts = [
        'use_year' => 'boolean',
        'use_month' => 'boolean',
    ];

    public function generateNextNumber()
    {
        $parts = [];
        
        if ($this->prefix) {
            $parts[] = $this->prefix;
        }
        
        $parts[] = str_pad($this->current_number, 4, '0', STR_PAD_LEFT);
        
        if ($this->use_month) {
            $parts[] = date('m');
        }
        
        if ($this->use_year) {
            $parts[] = date('Y');
        }
        
        if ($this->suffix) {
            $parts[] = $this->suffix;
        }
        
        return implode($this->separator, $parts);
    }

    public function incrementNumber()
    {
        $this->increment('current_number');
    }
}