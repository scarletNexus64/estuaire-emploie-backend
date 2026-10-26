<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRecoveryDelivery extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['payload'];
    protected $casts = [
        'payload' => 'encrypted:array', 'sent_at' => 'datetime',
        'expires_at' => 'datetime', 'cancelled_at' => 'datetime',
    ];
}
