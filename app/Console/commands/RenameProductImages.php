<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductVariant;

class RenameProductImages extends Command
{
    protected $signature = 'product:rename-images';

    protected $description = 'Rename product images, gallery images and variant images in S3';

    public function handle()
    {
        $this->info('Starting Product Image Rename...');

        Product::with('variants')
            ->orderBy('id')
            ->chunk(50, function ($products) {

                foreach ($products as $product) {

                    DB::beginTransaction();

                    try {

                        $productSlug = $product->slug;

                        /*
                        |--------------------------------------------------------------------------
                        | Main Image
                        |--------------------------------------------------------------------------
                        */

                        if (!empty($product->getRawOriginal('image_url'))) {

                            $oldPath = $product->getRawOriginal('image_url');

                            if (Storage::disk('s3')->exists($oldPath)) {

                                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);

                                $directory = dirname($oldPath);

                                $newPath = $directory . '/' . $productSlug . '-' . $product->id . '.' . $extension;

                                if ($oldPath != $newPath) {

                                    if (!Storage::disk('s3')->exists($newPath)) {

                                        Storage::disk('s3')->copy($oldPath, $newPath);

                                        Storage::disk('s3')->delete($oldPath);
                                    }

                                    $product->image_url = $newPath;
                                }
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Gallery Images
                        |--------------------------------------------------------------------------
                        */

                        $gallery = is_array($product->gallery_images)
                            ? $product->gallery_images
                            : [];

                        $newGallery = [];

                        foreach ($gallery as $index => $image) {

                            if (!$image) {
                                continue;
                            }

                            if (!Storage::disk('s3')->exists($image)) {

                                $newGallery[] = $image;

                                continue;
                            }

                            $extension = pathinfo($image, PATHINFO_EXTENSION);

                            $directory = dirname($image);

                            $newImage = $directory . '/'
                                . $productSlug
                                . '-'
                                . $product->id
                                . '-'
                                . ($index + 1)
                                . '.'
                                . $extension;

                            if ($image != $newImage) {

                                if (!Storage::disk('s3')->exists($newImage)) {

                                    Storage::disk('s3')->copy($image, $newImage);

                                    Storage::disk('s3')->delete($image);
                                }
                            }

                            $newGallery[] = $newImage;
                        }

                        $product->gallery_images = $newGallery;
                                                /*
                        |--------------------------------------------------------------------------
                        | Variant Images
                        |--------------------------------------------------------------------------
                        */

                        foreach ($product->variants as $variant) {

                            if (empty($variant->image_url)) {
                                continue;
                            }

                            $oldVariant = $variant->getRawOriginal('image_url');

                            if (!Storage::disk('s3')->exists($oldVariant)) {
                                continue;
                            }

                            $extension = pathinfo($oldVariant, PATHINFO_EXTENSION);

                            $directory = dirname($oldVariant);

                            $variantFolder = basename($directory);

                            $newVariant = $directory . '/'
                                . $variantFolder
                                . '-'
                                . $variant->id
                                . '.'
                                . $extension;

                            if ($oldVariant != $newVariant) {

                                if (!Storage::disk('s3')->exists($newVariant)) {

                                    Storage::disk('s3')->copy(
                                        $oldVariant,
                                        $newVariant
                                    );

                                    Storage::disk('s3')->delete(
                                        $oldVariant
                                    );
                                }

                                $variant->image_url = $newVariant;

                                $variant->save();

                                $this->info(
                                    "Variant Renamed : {$newVariant}"
                                );
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Save Product
                        |--------------------------------------------------------------------------
                        */

                        $product->save();

                        DB::commit();

                        $this->info(
                            "Product {$product->id} Completed"
                        );

                    } catch (\Throwable $e) {

                        DB::rollBack();

                        $this->error(
                            "Product {$product->id} Failed"
                        );

                        $this->error(
                            $e->getMessage()
                        );
                    }

                }

            });

        $this->info('');
        $this->info('=======================================');
        $this->info(' Product Image Rename Completed ');
        $this->info('=======================================');

        return Command::SUCCESS;
    }

}