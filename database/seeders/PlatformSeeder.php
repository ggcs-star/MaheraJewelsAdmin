<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platform;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        Platform::firstOrCreate(['name' => 'amazon'], [
            'display_name' => 'Amazon India',
            'region' => 'us-west-2',
            'marketplace_id' => 'A21TJRUUN4KGV',
            'status' => 'active',
            'is_enabled' => true,
        ]);

        Platform::firstOrCreate(['name' => 'flipkart'], [
            'display_name' => 'Flipkart',
            'region' => 'india',
            'status' => 'active',
            'is_enabled' => true,
        ]);

        Platform::firstOrCreate(['name' => 'own_website'], [
            'display_name' => 'Our Website',
            'region' => 'india',
            'status' => 'active',
            'is_enabled' => true,
        ]);
    }
}
