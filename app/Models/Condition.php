<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SystemTableTrait;

class Condition extends Model
{
    use SystemTableTrait;
    
    protected $table = 'conditions';
}
