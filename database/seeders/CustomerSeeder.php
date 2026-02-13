<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ Local / Dev only
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Customer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ✅ Get first active organization (required)
        $organization = Organization::where('is_active', true)->first();

        if (! $organization) {
            $this->command->error('❌ No active organization found. Please seed organizations first.');
            return;
        }

        $customers = [
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul.sharma@example.com',
                'mobile' => '9876543210',
                'address_line_1' => 'Flat 12, Green Heights',
                'address_line_2' => 'Near City Mall',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'country' => 'India',
                'zip_code' => '380015',
                'is_active' => true,
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'priya.verma@example.com',
                'mobile' => '9123456789',
                'address_line_1' => '221B Baker Street',
                'address_line_2' => null,
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'zip_code' => '400001',
                'is_active' => true,
            ],
            [
                'name' => 'Amit Patel',
                'email' => null, // optional email test
                'mobile' => '9988776655',
                'address_line_1' => 'Patel Society',
                'address_line_2' => 'Ring Road',
                'city' => 'Surat',
                'state' => 'Gujarat',
                'country' => 'India',
                'zip_code' => '395002',
                'is_active' => false,
            ],
            [
                'name' => 'Neha Singh',
                'email' => 'neha.singh@example.com',
                'mobile' => '9012345678',
                'address_line_1' => 'Sector 62',
                'address_line_2' => 'Near Metro Station',
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'country' => 'India',
                'zip_code' => '201301',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create([
                'organization_id' => $organization->id, // ✅ MOST IMPORTANT
                ...$customer,
            ]);
        }

        $this->command->info('✅ Customers seeded successfully.');
    }
}
