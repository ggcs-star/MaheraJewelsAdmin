<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Supplier 1 (Manufacturer)
        Supplier::firstOrCreate(
            ['email' => 'rajesh@abctextiles.com'],
            [
                'type' => 'manufacturer',
                'name' => 'Rajesh Kumar',
                'company_name' => 'ABC Textiles Manufacturing Pvt. Ltd.',
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
            ]
        );

        // Supplier 2 (Distributor)
        Supplier::firstOrCreate(
            ['email' => 'sales@xyzdistribution.com'],
            [
                'type' => 'distributor',
                'name' => 'Amit Sharma',
                'company_name' => 'XYZ Distribution Pvt. Ltd.',
                'phone' => '+91-9123456780',
                'address' => 'Warehouse Zone, Sector 9',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'pincode' => '110001',
                'gst_number' => '07AACFX1234Q1ZA',
                'pan_number' => 'AACFX1234Q',
                'commission_type' => 'fixed',
                'commission_value' => 50,
                'payment_terms' => 'Advance',
                'status' => 'active',
                'notes' => 'Electronics product distributor',
            ]
        );
    }
}
