<?php

namespace App\Models;

use App\Traits\AuthorizationChecker;
use App\Traits\HasGravatar;
use App\Traits\QueryBuilderTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Farmer extends Authenticatable
{
    use AuthorizationChecker;
    use HasGravatar;
    use SoftDeletes;
    use QueryBuilderTrait;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'photo',
        'referral_code',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class, 'action_by');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function farms()
    {
        return $this->hasMany(Farm::class);
    }


}
