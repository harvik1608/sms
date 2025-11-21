<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;    
    protected $fillable = ['user_id','plan_id','duration','amount','payment_status','whatsapp','paid_date','razorpay_order_id','razorpay_payment_id','razorpay_signature','is_active','is_multiple_file_allow'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
