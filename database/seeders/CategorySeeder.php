<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Category
        $electronicsId = DB::table('categories')->insertGetId([
            'name' => 'Electronics',
            'slug' => 'electronics',
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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Child Category
        DB::table('categories')->insert([
            'name' => 'Mobile Phones',
            'slug' => 'mobile-phones',
            'description' => 'Smartphones and mobile devices',
            'parent_id' => $electronicsId,
            'image_url' => 'categories/mobiles.png',
            'meta_title' => 'Mobile Phones',
            'meta_description' => 'Latest smartphones and accessories',
            'meta_keywords' => 'mobiles, smartphones, phones',
            'sort_order' => 1,
            'is_featured' => false,
            'visibility' => 'public',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
