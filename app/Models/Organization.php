<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\S3Helper;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'invoice_name',      // ✅ Add
        'email',
        'invoice_email',     // ✅ Add
        'mobile',
        'website',
        'logo_path',
        'invoice_logo',      // ✅ Add
        'business_hours',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ✅ Main Logo URL
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? S3Helper::url($this->logo_path)
            : null;
    }

    // ✅ Invoice Logo URL
    public function getInvoiceLogoUrlAttribute(): ?string
    {
        return $this->invoice_logo
            ? S3Helper::url($this->invoice_logo)
            : null;
    }

    // ✅ Invoice Name (Fallback)
    // public function getInvoiceNameAttribute(): ?string
    // {
    //     return $this->invoice_name ?? $this->name;
    // }

    // ✅ Invoice Email (Fallback)
    // public function getInvoiceEmailAttribute(): ?string
    // {
    //     return $this->invoice_email ?? $this->email;
    // }

    public function notificationEmails()
    {
        return $this->hasMany(OrganizationNotificationEmail::class);
    }
}