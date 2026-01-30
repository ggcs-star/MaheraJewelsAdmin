<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-AMD-01',
                'name' => 'Ahmedabad Main Warehouse',
                'address' => 'Odhav Industrial Area',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'country' => 'India',
                'pincode' => '382415',
                'manager_name' => 'Ramesh Patel',
                'manager_phone' => '9876543210',
                'status' => 'active',
                'notes' => 'Primary warehouse for Gujarat region',
            ],
            [
                'code' => 'WH-SRT-01',
                'name' => 'Surat Textile Warehouse',
                'address' => 'Ring Road',
                'city' => 'Surat',
                'state' => 'Gujarat',
                'country' => 'India',
                'pincode' => '395002',
                'manager_name' => 'Amit Shah',
                'manager_phone' => '9123456780',
                'status' => 'active',
                'notes' => 'Handles textile inventory',
            ],
            [
                'code' => 'WH-DEL-01',
                'name' => 'Delhi Central Warehouse',
                'address' => 'Okhla Phase 2',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'pincode' => '110020',
                'manager_name' => 'Sandeep Kumar',
                'manager_phone' => '9988776655',
                'status' => 'active',
                'notes' => 'North India distribution center',
            ],
            [
                'code' => 'WH-MUM-01',
                'name' => 'Mumbai Storage Unit',
                'address' => 'Bhiwandi',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'pincode' => '421302',
                'manager_name' => 'Rahul Mehta',
                'manager_phone' => '9012345678',
                'status' => 'inactive',
                'notes' => 'Temporarily closed',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['code' => $warehouse['code']], // unique key
                $warehouse
            );
        }
    }
}
