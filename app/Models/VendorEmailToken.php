<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorEmailToken extends Model
{
    protected $table = 'vendor_email_tokens';

    protected $fillable = [
        'vendor_id',
        'access_token',
        'refresh_token',
        'email',
    ];

    protected $casts = [
        'access_token' => 'string',
        'refresh_token' => 'string',
        'email' => 'string',
    ];

    /**
     * Relation: Each token belongs to one vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
