<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
         'organization_id',
        'invoice_number',
        'invoice_date',

        // customer
        'customer_id',
        'customer_name',
        'customer_mobile',
        'customer_address',

        // amount
        'sub_total',
        'discount',
        'tax_amount',
        'grand_total',

        // payment
        'payment_type',
        'paid_amount',
        'due_amount',

        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'sub_total'    => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax_amount'   => 'decimal:2',
        'grand_total'  => 'decimal:2',
        'paid_amount'  => 'decimal:2',
        'due_amount'   => 'decimal:2',
    ];

    /* ================= RELATIONS ================= */

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
}

