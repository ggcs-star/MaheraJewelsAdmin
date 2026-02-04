<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        /* ------------------------------
         | Helpers
         |------------------------------*/
        $variant = fn ($name, $type = 'text') =>
        Variant::firstOrCreate(
            ['name' => $name],
            [
                'input_type' => $type,
                'is_active' => 1
            ]
        );


        $value = fn ($variantId, $val, $color = null) =>
            VariantValue::firstOrCreate(
                ['variant_id' => $variantId, 'value' => $val],
                ['color' => $color, 'is_active' => 1]
            );

        $create = function ($product, $combo, $sku, $qty, $purchase, $selling, $order = 1) {
            foreach ($combo as $row) {
                ProductVariant::create([
                    'product_id'       => $product->id,
                    'variant_id'       => $row['variant']->id,
                    'variant_value_id' => $row['value']->id,
                    'sku_suffix'       => $sku,
                    'quantity'         => $qty,
                    'purchase_price'   => $purchase,
                    'selling_price'    => $selling,
                    'total_price'      => $qty * $purchase,
                    'sort_order'       => $order,
                    'status'           => 'active',
                ]);
            }
        };

        /* ------------------------------
         | Base data
         |------------------------------*/
        $women = Category::where('slug', 'women')->first();
        $men   = Category::where('slug', 'men')->first();
        $mobile = Category::where('slug', 'mobile')->first();
        $laptop = Category::where('slug', 'laptop')->first();
        $books  = Category::where('slug', 'books')->first();
        $beauty = Category::where('slug', 'beauty-personal-care')->first();
        $home   = Category::where('slug', 'home-furniture')->first();

        $supplier1 = Supplier::where('email', 'rajesh@abctextiles.com')->first();
        $supplier2 = Supplier::where('email', 'sales@xyzdistribution.com')->first();

        /* ------------------------------
         | VARIANTS
         |------------------------------*/
        $color    = $variant('Color', 'color');        // color picker
        $size     = $variant('Size', 'dimension');     // S, M, L, XL, 100ml
        $storage  = $variant('Storage', 'number');     // 128GB, 256GB
        $ram      = $variant('RAM', 'number');         // 8GB, 16GB
        $edition  = $variant('Edition', 'text');       // Paperback
        $language = $variant('Language', 'text');      // English
        $skin     = $variant('Skin Type', 'text');     // Oily Skin
        $material = $variant('Material', 'text');

        /* ==============================
         | WOMEN T-SHIRT
         |==============================*/
        $tshirt = Product::firstOrCreate(
            ['sku' => 'TSH-WOM-001'],
            [
                'name' => "Women's Cotton T-Shirt",
                'category_id' => $women?->id,
                'supplier_id' => $supplier1?->id,
                'brand' => 'FashionHub',
                'base_selling_price' => 350,
                'status' => 'active',
            ]
        );

        $rows = [
            ['Red','S',15,180,320],
            ['Red','M',25,190,330],
            ['Blue','L',18,200,350],
            ['Black','XL',12,215,380],
        ];

        foreach ($rows as $i => [$c,$s,$q,$p,$sp]) {
            $create(
                $tshirt,
                [
                    ['variant' => $color, 'value' => $value($color->id,$c,strtolower($c))],
                    ['variant' => $size,  'value' => $value($size->id,$s)],
                ],
                "-".strtoupper($c)."-".$s,
                $q,$p,$sp,$i+1
            );
        }

        /* ==============================
         | MOBILE (NO SIZE)
         |==============================*/
        $phone = Product::firstOrCreate(
            ['sku' => 'MOB-001'],
            [
                'name' => 'Smartphone Pro',
                'category_id' => $mobile?->id,
                'supplier_id' => $supplier2?->id,
                'brand' => 'TechPro',
                'status' => 'active',
            ]
        );

        $rows = [
            ['128GB','Black',10,24500,31999],
            ['256GB','Blue',6,28000,36999],
        ];

        foreach ($rows as $i => [$st,$c,$q,$p,$sp]) {
            $create(
                $phone,
                [
                    ['variant'=>$storage,'value'=>$value($storage->id,$st)],
                    ['variant'=>$color,'value'=>$value($color->id,$c,strtolower($c))],
                ],
                "-$st-".strtoupper($c),
                $q,$p,$sp,$i+1
            );
        }

        /* ==============================
         | BOOK (NO COLOR)
         |==============================*/
        $book = Product::firstOrCreate(
            ['sku' => 'BOOK-001'],
            [
                'name' => 'Laravel Complete Guide',
                'category_id' => $books?->id,
                'supplier_id' => $supplier1?->id,
                'status' => 'active',
            ]
        );

        $rows = [
            ['Paperback','English',50,430,579],
            ['E-Book','English',100,350,499],
        ];

        foreach ($rows as $i => [$e,$l,$q,$p,$sp]) {
            $create(
                $book,
                [
                    ['variant'=>$edition,'value'=>$value($edition->id,$e)],
                    ['variant'=>$language,'value'=>$value($language->id,$l)],
                ],
                "-".strtoupper($e),
                $q,$p,$sp,$i+1
            );
        }

        /* ==============================
         | FACE WASH (ML)
         |==============================*/
        $wash = Product::firstOrCreate(
            ['sku' => 'BEAU-001'],
            [
                'name' => 'Gentle Face Wash',
                'category_id' => $beauty?->id,
                'supplier_id' => $supplier1?->id,
                'status' => 'active',
            ]
        );

        $rows = [
            ['100ml','All Skin',100,115,189],
            ['200ml','Oily Skin',60,210,349],
        ];

        foreach ($rows as $i => [$s,$k,$q,$p,$sp]) {
            $create(
                $wash,
                [
                    ['variant'=>$size,'value'=>$value($size->id,$s)],
                    ['variant'=>$skin,'value'=>$value($skin->id,$k)],
                ],
                "-$s",
                $q,$p,$sp,$i+1
            );
        }

        /* ==============================
         | STUDY TABLE
         |==============================*/
        $table = Product::firstOrCreate(
            ['sku' => 'FURN-001'],
            [
                'name' => 'Wooden Study Table',
                'category_id' => $home?->id,
                'supplier_id' => $supplier1?->id,
                'status' => 'active',
            ]
        );

        $rows = [
            ['Brown','Solid Wood',8,3400,4799],
            ['White','Engineered Wood',5,3600,5199],
        ];

        foreach ($rows as $i => [$c,$m,$q,$p,$sp]) {
            $create(
                $table,
                [
                    ['variant'=>$color,'value'=>$value($color->id,$c,strtolower($c))],
                    ['variant'=>$material,'value'=>$value($material->id,$m)],
                ],
                "-".strtoupper($c),
                $q,$p,$sp,$i+1
            );
        }
    }
}
