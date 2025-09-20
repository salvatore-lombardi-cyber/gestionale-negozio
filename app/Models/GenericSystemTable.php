<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SystemTableTrait;

class GenericSystemTable extends Model
{
    use SystemTableTrait;
    
    protected $table;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Imposta la tabella dinamicamente
        if (isset($attributes['table'])) {
            $this->table = $attributes['table'];
        }
    }
    
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
}