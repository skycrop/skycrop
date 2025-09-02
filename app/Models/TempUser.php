<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
    protected $fillable = [
        'mobile_number',
        'otp',
        'otp_sent_at',
        'is_verified',
    ];

    protected $casts = [
        'otp_sent_at' => 'datetime',
    ];
}
