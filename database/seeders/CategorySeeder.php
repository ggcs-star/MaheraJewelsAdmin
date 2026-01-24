<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Category
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'], // check by unique field
            [
                'name' => 'Electronics',
                'description' => 'All kinds of electronic products',
                'parent_id' => null,
                'image_url' => 'categories/electronics.png',
                'meta_title' => 'Electronics Products',
                'meta_description' => 'Buy latest electronics items',
                'meta_keywords' => 'electronics, gadgets, devices',
                'sort_order' => 1,
                'is_featured' => true,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Child Category
        Category::firstOrCreate(
            ['slug' => 'mobile-phones'],
            [
                'name' => 'Mobile Phones',
                'description' => 'Smartphones and mobile devices',
                'parent_id' => $electronics->id,
                'image_url' => 'categories/mobiles.png',
                'meta_title' => 'Mobile Phones',
                'meta_description' => 'Latest smartphones and accessories',
                'meta_keywords' => 'mobiles, smartphones, phones',
                'sort_order' => 1,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );
    }
}
