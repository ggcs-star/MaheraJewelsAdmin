<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Banner;

class RenameBannerImages extends Command
{
    protected $signature = 'banner:rename-images';

    protected $description = 'Rename Banner Images in S3 and update database';

    public function handle()
    {
        $this->info('Starting Banner Image Rename...');

        Banner::orderBy('id')
            ->chunk(50, function ($banners) {

                foreach ($banners as $banner) {

                    DB::beginTransaction();

                    try {

                        $folder = Str::slug($banner->title ?: 'banner-' . $banner->id);

                        /*
                        |--------------------------------------------------------------------------
                        | Desktop Image
                        |--------------------------------------------------------------------------
                        */

                        if (!empty($banner->getRawOriginal('image'))) {

                            $oldImage = $banner->getRawOriginal('image');

                            if (Storage::disk('s3')->exists($oldImage)) {

                                $extension = pathinfo($oldImage, PATHINFO_EXTENSION);

                                $newImage = "admin/banner/desktop/{$folder}/{$folder}.{$extension}";

                                if ($oldImage != $newImage) {

                                    if (!Storage::disk('s3')->exists($newImage)) {

                                        Storage::disk('s3')->copy(
                                            $oldImage,
                                            $newImage
                                        );

                                        Storage::disk('s3')->delete(
                                            $oldImage
                                        );
                                    }

                                    $banner->image = $newImage;

                                    $this->info(
                                        "Desktop : {$newImage}"
                                    );
                                }
                            } else {

                                $this->warn(
                                    "Desktop Missing : {$oldImage}"
                                );
                            }
                        }
                                                /*
                        |--------------------------------------------------------------------------
                        | Mobile Image
                        |--------------------------------------------------------------------------
                        */

                        if (!empty($banner->getRawOriginal('mobile_image'))) {

                            $oldMobile = $banner->getRawOriginal('mobile_image');

                            if (Storage::disk('s3')->exists($oldMobile)) {

                                $extension = pathinfo($oldMobile, PATHINFO_EXTENSION);

                                $newMobile = "admin/banner/mobile/{$folder}/{$folder}-mobile.{$extension}";

                                if ($oldMobile != $newMobile) {

                                    if (!Storage::disk('s3')->exists($newMobile)) {

                                        Storage::disk('s3')->copy(
                                            $oldMobile,
                                            $newMobile
                                        );

                                        Storage::disk('s3')->delete(
                                            $oldMobile
                                        );
                                    }

                                    $banner->mobile_image = $newMobile;

                                    $this->info(
                                        "Mobile : {$newMobile}"
                                    );
                                }
                            } else {

                                $this->warn(
                                    "Mobile Missing : {$oldMobile}"
                                );
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Save Banner
                        |--------------------------------------------------------------------------
                        */

                        $banner->save();

                        DB::commit();

                        $this->info(
                            "Banner {$banner->id} Completed"
                        );

                    } catch (\Throwable $e) {

                        DB::rollBack();

                        $this->error(
                            "Banner {$banner->id} Failed"
                        );

                        $this->error(
                            $e->getMessage()
                        );
                    }

                }

            });

        $this->info('');
        $this->info('=======================================');
        $this->info(' Banner Image Rename Completed ');
        $this->info('=======================================');

        return Command::SUCCESS;
    }
}