<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'type',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'gst_number',
        'pan_number',
        'commission_type',
        'commission_value',
        'payment_terms',
        'status',
        'notes',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
    ];

  

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeManufacturer($query)
    {
        return $query->where('type', 'manufacturer');
    }

    public function scopeDistributor($query)
    {
        return $query->where('type', 'distributor');
    }


  public function calculateCommission(float $amount): float
{
    $commissionValue = (float) ($this->commission_value ?? 0);

    if ($this->commission_type === 'percentage') {
        return ($amount * $commissionValue) / 100;
    }

    return $commissionValue;
}

}
