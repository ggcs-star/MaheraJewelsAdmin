<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [

        'po_number',

        'supplier_id',

        

        'invoice_number',

        'purchase_date',

        'payment_method',

        'invoice_file',

        'subtotal',

        'tax_amount',

        'grand_total',

        'notes',

        'status',

    ];

    protected $casts = [

        'purchase_date' => 'date',

        'subtotal' => 'decimal:2',

        'tax_amount' => 'decimal:2',

        'grand_total' => 'decimal:2',

    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}