<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;    
    protected $fillable = ['name','amount','duration','whatsapp','note','is_multiple_file_allow','is_active'];
}
