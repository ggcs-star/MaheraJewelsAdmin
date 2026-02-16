<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::updateOrCreate(
            ['email' => 'demo@company.com'],
            [
                'name'    => 'Demo Company',
                'email'   => 'demo@company.com',
                'mobile'  => '9999999999',
                'website' => 'https://demo-company.com',

                'address' => 'Demo Street, Business Park',
                'city'    => 'Ahmedabad',
                'state'   => 'Gujarat',
                'country' => 'India',
                'pincode' => '380001',
                'logo_path' => null,
                'is_active' => true,
            ]
        );
    }
}
