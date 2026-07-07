<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageImpression extends Model
{
    protected $fillable = [
        'page_name',
        'impression_count',
    ];
}