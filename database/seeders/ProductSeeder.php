<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get Categories
        $electronics = Category::where('slug', 'electronics')->first();
        $mobile = Category::where('slug', 'mobile')->first();
        $laptop = Category::where('slug', 'laptop')->first();
        $tshirts = Category::where('slug', 't-shirts')->first();
        $men = Category::where('slug', 'men')->first();
        $women = Category::where('slug', 'women')->first();
        $books = Category::where('slug', 'books')->first();
        $home = Category::where('slug', 'home-furniture')->first();
        $beauty = Category::where('slug', 'beauty-personal-care')->first();

        // Get Suppliers
        $supplier1 = Supplier::where('email', 'rajesh@abctextiles.com')->first();
        $supplier2 = Supplier::where('email', 'sales@xyzdistribution.com')->first();

        // ============================================
        // T-SHIRTS - WOMEN CATEGORY
        // ============================================
        
        // Product 1: Women's Cotton T-Shirt
        $product1 = Product::firstOrCreate(
            ['sku' => 'TSH-WOM-001'],
            [
                'name' => "Women's Premium Cotton T-Shirt",
                'slug' => 'womens-premium-cotton-t-shirt',
                'description' => 'Soft and comfortable 100% cotton t-shirt for women. Perfect for casual wear. Available in multiple colors and sizes.',
                'short_description' => 'Premium cotton t-shirt for women - Comfortable & Stylish',
                'category_id' => $women?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'FashionHub',
                'cost_price' => 200,
                'base_selling_price' => 350,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => "Women's Cotton T-Shirt - Premium Quality",
                'meta_description' => 'Buy premium women t-shirt online. 100% cotton, multiple colors and sizes available.',
                'meta_keywords' => 'women t-shirt, cotton t-shirt, womens wear, casual wear',
                'is_featured' => true,
                'is_top_selling' => true,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Variants for Product 1: Color + Size combinations
        if ($product1) {
            $variants1 = [
                ['color' => 'Red', 'size' => 'S', 'qty' => 15, 'purchase' => 180, 'selling' => 320],
                ['color' => 'Red', 'size' => 'M', 'qty' => 25, 'purchase' => 190, 'selling' => 330],
                ['color' => 'Red', 'size' => 'L', 'qty' => 20, 'purchase' => 200, 'selling' => 350],
                ['color' => 'Red', 'size' => 'XL', 'qty' => 10, 'purchase' => 210, 'selling' => 370],
                ['color' => 'Blue', 'size' => 'S', 'qty' => 12, 'purchase' => 180, 'selling' => 320],
                ['color' => 'Blue', 'size' => 'M', 'qty' => 30, 'purchase' => 190, 'selling' => 330],
                ['color' => 'Blue', 'size' => 'L', 'qty' => 18, 'purchase' => 200, 'selling' => 350],
                ['color' => 'Blue', 'size' => 'XL', 'qty' => 8, 'purchase' => 210, 'selling' => 370],
                ['color' => 'Black', 'size' => 'S', 'qty' => 20, 'purchase' => 185, 'selling' => 325],
                ['color' => 'Black', 'size' => 'M', 'qty' => 35, 'purchase' => 195, 'selling' => 340],
                ['color' => 'Black', 'size' => 'L', 'qty' => 22, 'purchase' => 205, 'selling' => 360],
                ['color' => 'Black', 'size' => 'XL', 'qty' => 12, 'purchase' => 215, 'selling' => 380],
            ];

            foreach ($variants1 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product1->id,
                        'variant_type' => 'Color',
                        'variant_value' => $variant['color'] . ' - ' . $variant['size'],
                    ],
                    [
                        'sku_suffix' => '-' . strtoupper($variant['color']) . '-' . $variant['size'],
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // Product 2: Women's V-Neck T-Shirt
        $product2 = Product::firstOrCreate(
            ['sku' => 'TSH-WOM-002'],
            [
                'name' => "Women's V-Neck Casual T-Shirt",
                'slug' => 'womens-v-neck-casual-t-shirt',
                'description' => 'Stylish V-neck t-shirt for women. Made from premium cotton blend. Perfect for everyday wear.',
                'short_description' => 'V-Neck casual t-shirt - Trendy & Comfortable',
                'category_id' => $women?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'StyleWear',
                'cost_price' => 220,
                'base_selling_price' => 380,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => "Women's V-Neck T-Shirt",
                'meta_description' => 'Buy stylish V-neck t-shirt for women online.',
                'meta_keywords' => 'v-neck t-shirt, womens casual, trendy wear',
                'is_featured' => false,
                'is_top_selling' => false,
                'sort_order' => 2,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product2) {
            $variants2 = [
                ['color' => 'Pink', 'size' => 'M', 'qty' => 18, 'purchase' => 200, 'selling' => 360],
                ['color' => 'Pink', 'size' => 'L', 'qty' => 15, 'purchase' => 210, 'selling' => 380],
                ['color' => 'White', 'size' => 'S', 'qty' => 10, 'purchase' => 190, 'selling' => 340],
                ['color' => 'White', 'size' => 'M', 'qty' => 25, 'purchase' => 200, 'selling' => 360],
                ['color' => 'White', 'size' => 'L', 'qty' => 20, 'purchase' => 210, 'selling' => 380],
            ];

            foreach ($variants2 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product2->id,
                        'variant_type' => 'Color',
                        'variant_value' => $variant['color'] . ' - ' . $variant['size'],
                    ],
                    [
                        'sku_suffix' => '-' . strtoupper($variant['color']) . '-' . $variant['size'],
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // T-SHIRTS - MEN CATEGORY
        // ============================================

        // Product 3: Men's Classic T-Shirt
        $product3 = Product::firstOrCreate(
            ['sku' => 'TSH-MEN-001'],
            [
                'name' => "Men's Classic Cotton T-Shirt",
                'slug' => 'mens-classic-cotton-t-shirt',
                'description' => 'Classic fit cotton t-shirt for men. Durable and comfortable. Available in multiple colors.',
                'short_description' => 'Classic men t-shirt - Premium Quality',
                'category_id' => $men?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'ManStyle',
                'cost_price' => 180,
                'base_selling_price' => 320,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => "Men's Classic T-Shirt",
                'meta_description' => 'Buy classic men t-shirt online. Premium cotton quality.',
                'meta_keywords' => 'mens t-shirt, cotton t-shirt, mens wear',
                'is_featured' => true,
                'is_top_selling' => true,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product3) {
            $variants3 = [
                ['color' => 'Navy Blue', 'size' => 'M', 'qty' => 30, 'purchase' => 170, 'selling' => 300],
                ['color' => 'Navy Blue', 'size' => 'L', 'qty' => 25, 'purchase' => 180, 'selling' => 320],
                ['color' => 'Navy Blue', 'size' => 'XL', 'qty' => 20, 'purchase' => 190, 'selling' => 340],
                ['color' => 'Gray', 'size' => 'M', 'qty' => 28, 'purchase' => 170, 'selling' => 300],
                ['color' => 'Gray', 'size' => 'L', 'qty' => 22, 'purchase' => 180, 'selling' => 320],
                ['color' => 'Gray', 'size' => 'XL', 'qty' => 15, 'purchase' => 190, 'selling' => 340],
                ['color' => 'White', 'size' => 'M', 'qty' => 35, 'purchase' => 175, 'selling' => 310],
                ['color' => 'White', 'size' => 'L', 'qty' => 30, 'purchase' => 185, 'selling' => 330],
            ];

            foreach ($variants3 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product3->id,
                        'variant_type' => 'Color',
                        'variant_value' => $variant['color'] . ' - ' . $variant['size'],
                    ],
                    [
                        'sku_suffix' => '-' . strtoupper(str_replace(' ', '', $variant['color'])) . '-' . $variant['size'],
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // ELECTRONICS - MOBILE CATEGORY
        // ============================================

        // Product 4: Smartphone
        $product4 = Product::firstOrCreate(
            ['sku' => 'MOB-001'],
            [
                'name' => 'Smartphone Pro Max 128GB',
                'slug' => 'smartphone-pro-max-128gb',
                'description' => 'Latest smartphone with 128GB storage, 6.7 inch display, triple camera setup, and fast charging.',
                'short_description' => 'Premium smartphone with advanced features',
                'category_id' => $mobile?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'TechPro',
                'cost_price' => 25000,
                'base_selling_price' => 32999,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Smartphone Pro Max 128GB - Latest Model',
                'meta_description' => 'Buy latest smartphone with 128GB storage and premium features.',
                'meta_keywords' => 'smartphone, mobile phone, 128gb, pro max',
                'is_featured' => true,
                'is_top_selling' => true,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product4) {
            $variants4 = [
                ['storage' => '128GB', 'color' => 'Black', 'qty' => 15, 'purchase' => 24500, 'selling' => 31999],
                ['storage' => '128GB', 'color' => 'Blue', 'qty' => 12, 'purchase' => 24500, 'selling' => 31999],
                ['storage' => '128GB', 'color' => 'White', 'qty' => 10, 'purchase' => 24500, 'selling' => 31999],
                ['storage' => '256GB', 'color' => 'Black', 'qty' => 8, 'purchase' => 28000, 'selling' => 36999],
                ['storage' => '256GB', 'color' => 'Blue', 'qty' => 6, 'purchase' => 28000, 'selling' => 36999],
            ];

            foreach ($variants4 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product4->id,
                        'variant_type' => 'Storage',
                        'variant_value' => $variant['storage'] . ' - ' . $variant['color'],
                    ],
                    [
                        'sku_suffix' => '-' . $variant['storage'] . '-' . strtoupper($variant['color']),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // Product 5: Budget Smartphone
        $product5 = Product::firstOrCreate(
            ['sku' => 'MOB-002'],
            [
                'name' => 'Budget Smartphone 64GB',
                'slug' => 'budget-smartphone-64gb',
                'description' => 'Affordable smartphone with 64GB storage, 6.1 inch display, dual camera, and long battery life.',
                'short_description' => 'Affordable smartphone with great features',
                'category_id' => $mobile?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'ValueTech',
                'cost_price' => 12000,
                'base_selling_price' => 15999,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Budget Smartphone 64GB - Best Price',
                'meta_description' => 'Buy affordable smartphone with 64GB storage.',
                'meta_keywords' => 'budget smartphone, affordable mobile, 64gb',
                'is_featured' => false,
                'is_top_selling' => true,
                'sort_order' => 2,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product5) {
            $variants5 = [
                ['storage' => '64GB', 'color' => 'Black', 'qty' => 25, 'purchase' => 11800, 'selling' => 15499],
                ['storage' => '64GB', 'color' => 'Blue', 'qty' => 20, 'purchase' => 11800, 'selling' => 15499],
                ['storage' => '128GB', 'color' => 'Black', 'qty' => 15, 'purchase' => 13500, 'selling' => 17999],
            ];

            foreach ($variants5 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product5->id,
                        'variant_type' => 'Storage',
                        'variant_value' => $variant['storage'] . ' - ' . $variant['color'],
                    ],
                    [
                        'sku_suffix' => '-' . $variant['storage'] . '-' . strtoupper($variant['color']),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // ELECTRONICS - LAPTOP CATEGORY
        // ============================================

        // Product 6: Gaming Laptop
        $product6 = Product::firstOrCreate(
            ['sku' => 'LAP-001'],
            [
                'name' => 'Gaming Laptop 16GB RAM 512GB SSD',
                'slug' => 'gaming-laptop-16gb-512gb',
                'description' => 'High-performance gaming laptop with 16GB RAM, 512GB SSD, dedicated graphics card, and 15.6 inch display.',
                'short_description' => 'Powerful gaming laptop for gamers',
                'category_id' => $laptop?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'GameTech',
                'cost_price' => 65000,
                'base_selling_price' => 84999,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Gaming Laptop 16GB RAM - Best for Gaming',
                'meta_description' => 'Buy gaming laptop with 16GB RAM and dedicated graphics.',
                'meta_keywords' => 'gaming laptop, 16gb ram, gaming pc, laptop',
                'is_featured' => true,
                'is_top_selling' => false,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product6) {
            $variants6 = [
                ['ram' => '16GB', 'storage' => '512GB SSD', 'qty' => 5, 'purchase' => 64000, 'selling' => 83999],
                ['ram' => '16GB', 'storage' => '1TB SSD', 'qty' => 3, 'purchase' => 72000, 'selling' => 94999],
                ['ram' => '32GB', 'storage' => '512GB SSD', 'qty' => 2, 'purchase' => 75000, 'selling' => 99999],
            ];

            foreach ($variants6 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product6->id,
                        'variant_type' => 'Configuration',
                        'variant_value' => $variant['ram'] . ' - ' . $variant['storage'],
                    ],
                    [
                        'sku_suffix' => '-' . str_replace(' ', '', $variant['ram']) . '-' . str_replace(' ', '', $variant['storage']),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // Product 7: Business Laptop
        $product7 = Product::firstOrCreate(
            ['sku' => 'LAP-002'],
            [
                'name' => 'Business Laptop 8GB RAM 256GB SSD',
                'slug' => 'business-laptop-8gb-256gb',
                'description' => 'Lightweight business laptop with 8GB RAM, 256GB SSD, 14 inch display, and long battery life.',
                'short_description' => 'Professional business laptop',
                'category_id' => $laptop?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'ProBook',
                'cost_price' => 35000,
                'base_selling_price' => 44999,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Business Laptop 8GB RAM - Professional',
                'meta_description' => 'Buy business laptop with 8GB RAM for professionals.',
                'meta_keywords' => 'business laptop, professional laptop, 8gb ram',
                'is_featured' => false,
                'is_top_selling' => true,
                'sort_order' => 2,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product7) {
            $variants7 = [
                ['ram' => '8GB', 'storage' => '256GB SSD', 'color' => 'Silver', 'qty' => 12, 'purchase' => 34000, 'selling' => 43999],
                ['ram' => '8GB', 'storage' => '256GB SSD', 'color' => 'Black', 'qty' => 10, 'purchase' => 34000, 'selling' => 43999],
                ['ram' => '16GB', 'storage' => '512GB SSD', 'color' => 'Silver', 'qty' => 8, 'purchase' => 42000, 'selling' => 54999],
            ];

            foreach ($variants7 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product7->id,
                        'variant_type' => 'Configuration',
                        'variant_value' => $variant['ram'] . ' - ' . $variant['storage'] . ' - ' . $variant['color'],
                    ],
                    [
                        'sku_suffix' => '-' . str_replace(' ', '', $variant['ram']) . '-' . str_replace(' ', '', $variant['storage']) . '-' . strtoupper($variant['color']),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // BOOKS CATEGORY
        // ============================================

        // Product 8: Programming Book
        $product8 = Product::firstOrCreate(
            ['sku' => 'BOOK-001'],
            [
                'name' => 'Complete Guide to Laravel Development',
                'slug' => 'complete-guide-laravel-development',
                'description' => 'Comprehensive guide to Laravel framework. Covers all aspects from basics to advanced topics.',
                'short_description' => 'Complete Laravel development guide',
                'category_id' => $books?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'TechBooks',
                'cost_price' => 450,
                'base_selling_price' => 599,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Laravel Development Guide - Complete Book',
                'meta_description' => 'Buy complete guide to Laravel development.',
                'meta_keywords' => 'laravel book, programming book, php framework',
                'is_featured' => false,
                'is_top_selling' => false,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product8) {
            $variants8 = [
                ['edition' => 'Paperback', 'language' => 'English', 'qty' => 50, 'purchase' => 430, 'selling' => 579],
                ['edition' => 'Hardcover', 'language' => 'English', 'qty' => 20, 'purchase' => 550, 'selling' => 749],
                ['edition' => 'E-Book', 'language' => 'English', 'qty' => 100, 'purchase' => 350, 'selling' => 499],
            ];

            foreach ($variants8 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product8->id,
                        'variant_type' => 'Edition',
                        'variant_value' => $variant['edition'] . ' - ' . $variant['language'],
                    ],
                    [
                        'sku_suffix' => '-' . strtoupper(str_replace('-', '', $variant['edition'])) . '-' . strtoupper($variant['language']),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // BEAUTY & PERSONAL CARE
        // ============================================

        // Product 9: Face Wash
        $product9 = Product::firstOrCreate(
            ['sku' => 'BEAU-001'],
            [
                'name' => 'Gentle Face Wash 100ml',
                'slug' => 'gentle-face-wash-100ml',
                'description' => 'Gentle face wash suitable for all skin types. Contains natural ingredients. 100ml bottle.',
                'short_description' => 'Gentle face wash for all skin types',
                'category_id' => $beauty?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'PureSkin',
                'cost_price' => 120,
                'base_selling_price' => 199,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Gentle Face Wash 100ml - All Skin Types',
                'meta_description' => 'Buy gentle face wash for all skin types.',
                'meta_keywords' => 'face wash, skincare, beauty products',
                'is_featured' => false,
                'is_top_selling' => true,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product9) {
            $variants9 = [
                ['size' => '100ml', 'skin_type' => 'All Skin Types', 'qty' => 100, 'purchase' => 115, 'selling' => 189],
                ['size' => '200ml', 'skin_type' => 'All Skin Types', 'qty' => 60, 'purchase' => 210, 'selling' => 349],
                ['size' => '100ml', 'skin_type' => 'Oily Skin', 'qty' => 80, 'purchase' => 120, 'selling' => 199],
            ];

            foreach ($variants9 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product9->id,
                        'variant_type' => 'Size',
                        'variant_value' => $variant['size'] . ' - ' . $variant['skin_type'],
                    ],
                    [
                        'sku_suffix' => '-' . $variant['size'] . '-' . strtoupper(str_replace(' ', '', $variant['skin_type'])),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }

        // ============================================
        // HOME & FURNITURE
        // ============================================

        // Product 10: Study Table
        $product10 = Product::firstOrCreate(
            ['sku' => 'FURN-001'],
            [
                'name' => 'Wooden Study Table with Storage',
                'slug' => 'wooden-study-table-storage',
                'description' => 'Solid wood study table with storage drawers. Dimensions: 120cm x 60cm x 75cm. Assembly required.',
                'short_description' => 'Solid wood study table with storage',
                'category_id' => $home?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'HomeStyle',
                'cost_price' => 3500,
                'base_selling_price' => 4999,
                'image_url' => null,
                'gallery_images' => null,
                'meta_title' => 'Wooden Study Table - With Storage',
                'meta_description' => 'Buy wooden study table with storage drawers.',
                'meta_keywords' => 'study table, wooden table, furniture, home furniture',
                'is_featured' => false,
                'is_top_selling' => false,
                'sort_order' => 1,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        if ($product10) {
            $variants10 = [
                ['color' => 'Brown', 'material' => 'Solid Wood', 'qty' => 8, 'purchase' => 3400, 'selling' => 4799],
                ['color' => 'White', 'material' => 'Solid Wood', 'qty' => 5, 'purchase' => 3600, 'selling' => 5199],
                ['color' => 'Brown', 'material' => 'Engineered Wood', 'qty' => 12, 'purchase' => 2800, 'selling' => 3999],
            ];

            foreach ($variants10 as $index => $variant) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product10->id,
                        'variant_type' => 'Color',
                        'variant_value' => $variant['color'] . ' - ' . $variant['material'],
                    ],
                    [
                        'sku_suffix' => '-' . strtoupper($variant['color']) . '-' . strtoupper(str_replace(' ', '', $variant['material'])),
                        'quantity' => $variant['qty'],
                        'purchase_price' => $variant['purchase'],
                        'selling_price' => $variant['selling'],
                        'total_price' => $variant['qty'] * $variant['purchase'],
                        'sort_order' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
