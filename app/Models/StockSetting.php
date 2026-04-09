<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSetting extends Model
{
    protected $fillable = ['threshold', 'admin_email'];
}