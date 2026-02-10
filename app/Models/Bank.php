<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

   public function coupons()
{
    return $this->hasMany(Coupon::class);
}

}

