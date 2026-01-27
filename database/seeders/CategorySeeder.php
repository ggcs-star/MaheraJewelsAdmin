<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // ELECTRONICS - Main Category
        // ============================================
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'description' => 'All kinds of electronic products including mobile phones, laptops, and accessories',
                'parent_id' => null,
                'image_url' => null,
                'meta_title' => 'Electronics Products - Latest Gadgets & Devices',
                'meta_description' => 'Shop the latest electronics including smartphones, laptops, tablets, and more',
                'meta_keywords' => 'electronics, gadgets, devices, smartphones, laptops, tablets',
                'sort_order' => 1,
                'is_featured' => true,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Mobile - Subcategory of Electronics
        Category::firstOrCreate(
            ['slug' => 'mobile'],
            [
                'name' => 'Mobile',
                'description' => 'Smartphones, mobile phones, and mobile accessories',
                'parent_id' => $electronics->id,
                'image_url' => null,
                'meta_title' => 'Mobile Phones - Smartphones & Accessories',
                'meta_description' => 'Latest smartphones from top brands with best prices',
                'meta_keywords' => 'mobile, smartphone, phones, mobile accessories, android, ios',
                'sort_order' => 1,
                'is_featured' => true,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Laptop - Subcategory of Electronics
        Category::firstOrCreate(
            ['slug' => 'laptop'],
            [
                'name' => 'Laptop',
                'description' => 'Laptops, notebooks, and laptop accessories',
                'parent_id' => $electronics->id,
                'image_url' => null,
                'meta_title' => 'Laptops - Best Laptop Deals & Reviews',
                'meta_description' => 'Shop for laptops from top brands - gaming laptops, business laptops, and more',
                'meta_keywords' => 'laptop, notebooks, gaming laptop, business laptop, laptop accessories',
                'sort_order' => 2,
                'is_featured' => true,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // ============================================
        // T-SHIRTS / CLOTHING - Main Category
        // ============================================
        $tshirts = Category::firstOrCreate(
            ['slug' => 't-shirts'],
            [
                'name' => 'T-Shirts',
                'description' => 'Comfortable and stylish t-shirts for men and women',
                'parent_id' => null,
                'image_url' => null,
                'meta_title' => 'T-Shirts - Men & Women T-Shirts Collection',
                'meta_description' => 'Shop trendy t-shirts for men and women. Cotton, casual, and designer t-shirts',
                'meta_keywords' => 't-shirts, tshirts, mens t-shirts, womens t-shirts, casual wear, cotton t-shirts',
                'sort_order' => 2,
                'is_featured' => true,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Men - Subcategory of T-Shirts
        Category::firstOrCreate(
            ['slug' => 'men'],
            [
                'name' => 'Men',
                'description' => 'T-shirts and casual wear for men',
                'parent_id' => $tshirts->id,
                'image_url' => null,
                'meta_title' => 'Men T-Shirts - Mens Casual Wear',
                'meta_description' => 'Stylish and comfortable t-shirts for men. Cotton, polo, and designer options',
                'meta_keywords' => 'mens t-shirts, mens wear, mens casual, mens clothing, mens fashion',
                'sort_order' => 1,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Women - Subcategory of T-Shirts
        Category::firstOrCreate(
            ['slug' => 'women'],
            [
                'name' => 'Women',
                'description' => 'T-shirts and casual wear for women',
                'parent_id' => $tshirts->id,
                'image_url' => null,
                'meta_title' => 'Women T-Shirts - Womens Casual Wear',
                'meta_description' => 'Trendy and comfortable t-shirts for women. Fashionable and stylish options',
                'meta_keywords' => 'womens t-shirts, womens wear, womens casual, womens clothing, womens fashion',
                'sort_order' => 2,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // ============================================
        // ADDITIONAL TEST CATEGORIES
        // ============================================
        
        // Books - Main Category
        $books = Category::firstOrCreate(
            ['slug' => 'books'],
            [
                'name' => 'Books',
                'description' => 'Books, novels, and educational materials',
                'parent_id' => null,
                'image_url' => null,
                'meta_title' => 'Books - Novels, Educational & More',
                'meta_description' => 'Shop books online - fiction, non-fiction, educational books',
                'meta_keywords' => 'books, novels, fiction, non-fiction, educational books',
                'sort_order' => 3,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Home & Furniture - Main Category
        $home = Category::firstOrCreate(
            ['slug' => 'home-furniture'],
            [
                'name' => 'Home & Furniture',
                'description' => 'Furniture and home decor items',
                'parent_id' => null,
                'image_url' => null,
                'meta_title' => 'Home & Furniture - Furniture & Decor',
                'meta_description' => 'Shop furniture and home decor items for your home',
                'meta_keywords' => 'furniture, home decor, furniture online, home furniture',
                'sort_order' => 4,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );

        // Beauty & Personal Care - Main Category
        $beauty = Category::firstOrCreate(
            ['slug' => 'beauty-personal-care'],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Beauty products and personal care items',
                'parent_id' => null,
                'image_url' => null,
                'meta_title' => 'Beauty & Personal Care Products',
                'meta_description' => 'Shop beauty products, skincare, and personal care items',
                'meta_keywords' => 'beauty, skincare, personal care, cosmetics, beauty products',
                'sort_order' => 5,
                'is_featured' => false,
                'visibility' => 'public',
                'status' => 'active',
            ]
        );
    }
}
