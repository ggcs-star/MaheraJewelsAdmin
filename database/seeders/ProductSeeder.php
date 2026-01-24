<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('slug', 'electronics')->first();
        $mobiles = Category::where('slug', 'mobile-phones')->first();

        $supplier1 = Supplier::first();
        $supplier2 = Supplier::skip(1)->first(); // second supplier

        Product::firstOrCreate(
            ['sku' => 'TSH-WOM-001'],
            [
                'name' => "Women's Premium Cotton T-Shirt",
                'slug' => 'womens-premium-cotton-t-shirt',
                'description' => 'Soft and comfortable cotton t-shirt.',
                'short_description' => 'Premium cotton t-shirt for women',
                'category_id' => $electronics?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'FashionHub',
                'cost_price' => 200,
                'base_selling_price' => 350,
                'image_url' => '/images/products/tsh-wom-001-main.jpg',
                'gallery_images' => [
                    '/images/products/tsh-wom-001-1.jpg',
                    '/images/products/tsh-wom-001-2.jpg',
                ],
                'meta_title' => "Women's Cotton T-Shirt",
                'meta_description' => 'Buy premium women t-shirt',
                'meta_keywords' => 'women t-shirt',
                'is_featured' => true,
                'status' => 'active',
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'ELEC-MOB-001'],
            [
                'name' => 'Wireless Bluetooth Mouse',
                'slug' => 'wireless-bluetooth-mouse',
                'description' => 'Ergonomic wireless mouse',
                'short_description' => 'Wireless Bluetooth mouse',
                'category_id' => $mobiles?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'TechPro',
                'cost_price' => 500,
                'base_selling_price' => 799,
                'image_url' => '/images/products/elec-mob-001-main.jpg',
                'gallery_images' => [
                    '/images/products/elec-mob-001-1.jpg',
                ],
                'meta_title' => 'Wireless Mouse',
                'meta_description' => 'Buy wireless mouse',
                'meta_keywords' => 'wireless mouse',
                'status' => 'active',
            ]
        );
    }
}
