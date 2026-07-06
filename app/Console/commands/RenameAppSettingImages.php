<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RenameAppSettingImages extends Command
{
    protected $signature = 'app-settings:rename-images';

    protected $description = 'Rename App Setting Images in S3 and update DB';

    public function handle()
    {
        $this->info('Starting App Setting Image Rename...');

        AppSetting::chunk(10, function ($settings) {

            foreach ($settings as $setting) {

                DB::beginTransaction();

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | App Logo
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($setting->getRawOriginal('app_logo'))) {

                        $old = $setting->getRawOriginal('app_logo');

                        if (Storage::disk('s3')->exists($old)) {

                            $ext = pathinfo($old, PATHINFO_EXTENSION);

                            $new = "admin/app-settings/applogo/app-logo.{$ext}";

                            if ($old != $new) {

                                if (!Storage::disk('s3')->exists($new)) {

                                    Storage::disk('s3')->copy($old, $new);

                                    Storage::disk('s3')->delete($old);
                                }

                                $setting->app_logo = $new;

                                $this->info("App Logo : {$new}");
                            }
                        }
                    }
                                        /*
                    |--------------------------------------------------------------------------
                    | Splash Logo
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($setting->getRawOriginal('splash_logo'))) {

                        $old = $setting->getRawOriginal('splash_logo');

                        if (Storage::disk('s3')->exists($old)) {

                            $ext = pathinfo($old, PATHINFO_EXTENSION);

                            $new = "admin/app-settings/splash/splash-logo.{$ext}";

                            if ($old != $new) {

                                if (!Storage::disk('s3')->exists($new)) {

                                    Storage::disk('s3')->copy($old, $new);

                                    Storage::disk('s3')->delete($old);
                                }

                                $setting->splash_logo = $new;

                                $this->info("Splash Logo : {$new}");
                            }
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Header Logo
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($setting->getRawOriginal('header_logo'))) {

                        $old = $setting->getRawOriginal('header_logo');

                        if (Storage::disk('s3')->exists($old)) {

                            $ext = pathinfo($old, PATHINFO_EXTENSION);

                            $new = "admin/app-settings/header/header-logo.{$ext}";

                            if ($old != $new) {

                                if (!Storage::disk('s3')->exists($new)) {

                                    Storage::disk('s3')->copy($old, $new);

                                    Storage::disk('s3')->delete($old);
                                }

                                $setting->header_logo = $new;

                                $this->info("Header Logo : {$new}");
                            }
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Save
                    |--------------------------------------------------------------------------
                    */

                    $setting->save();

                    DB::commit();

                    $this->info(
                        "App Setting {$setting->id} Completed"
                    );

                } catch (\Throwable $e) {

                    DB::rollBack();

                    $this->error(
                        "App Setting {$setting->id} Failed"
                    );

                    $this->error(
                        $e->getMessage()
                    );
                }
            }
        });

        $this->info('');
        $this->info('=======================================');
        $this->info(' App Setting Images Renamed ');
        $this->info('=======================================');

        return Command::SUCCESS;
    }
}