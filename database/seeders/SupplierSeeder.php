<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'type' => 'manufacturer',
            'name' => 'Rajesh Kumar',
            'company_name' => 'ABC Textiles Manufacturing Pvt. Ltd.',
            'email' => 'rajesh@abctextiles.com',
            'phone' => '+91-9876543210',
            'address' => 'Industrial Area, Sector 5',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'pincode' => '400001',
            'gst_number' => '27AABCU9603R1ZX',
            'pan_number' => 'AABCU9603R',
            'commission_type' => 'percentage',
            'commission_value' => 12.50,
            'payment_terms' => 'Net 30 days',
            'status' => 'active',
            'notes' => 'Primary manufacturer for clothing line',
        ]);
    }
}
