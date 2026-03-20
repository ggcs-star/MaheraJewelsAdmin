<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // ✅ add this

class ReelComment extends Model
{
    protected $fillable = [
        'reel_id',
        'user_id',
        'comment'
    ];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    // 🔥 THIS IS MISSING (MAIN FIX)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}