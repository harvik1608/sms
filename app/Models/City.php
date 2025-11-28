<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;    
    protected $fillable = ['name','state_id','is_active'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
