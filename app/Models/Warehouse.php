<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'manager_name',
        'manager_phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
    ];


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->pincode,
        ])->filter()->implode(', ');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
