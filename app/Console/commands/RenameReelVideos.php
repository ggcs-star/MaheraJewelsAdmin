<?php

namespace App\Console\Commands;

use App\Models\Reel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RenameReelVideos extends Command
{
    protected $signature = 'reels:rename-videos';

    protected $description = 'Rename Reel Videos in S3 and update database';

    public function handle()
    {
        $this->info('Starting Reel Video Rename...');

        Reel::orderBy('id')
            ->chunk(50, function ($reels) {

                foreach ($reels as $reel) {

                    DB::beginTransaction();

                    try {

                        if (!empty($reel->video)) {

                            $oldVideo = $reel->video;

                            if (Storage::disk('s3')->exists($oldVideo)) {

                                $extension = pathinfo($oldVideo, PATHINFO_EXTENSION);

                                $name = $reel->title
                                    ? Str::slug($reel->title)
                                    : 'reel-' . $reel->id;

                                $newVideo = "admin/reels/{$name}.{$extension}";

                                if ($oldVideo != $newVideo) {

                                    if (!Storage::disk('s3')->exists($newVideo)) {

                                        Storage::disk('s3')->copy(
                                            $oldVideo,
                                            $newVideo
                                        );

                                        Storage::disk('s3')->delete(
                                            $oldVideo
                                        );
                                    }

                                    $reel->video = $newVideo;

                                    $this->info(
                                        "Video : {$newVideo}"
                                    );
                                }
                            } else {

                                $this->warn(
                                    "Missing : {$oldVideo}"
                                );
                            }
                        }
                                                /*
                        |--------------------------------------------------------------------------
                        | Save Reel
                        |--------------------------------------------------------------------------
                        */

                        $reel->save();

                        DB::commit();

                        $this->info(
                            "Reel {$reel->id} Completed"
                        );

                    } catch (\Throwable $e) {

                        DB::rollBack();

                        $this->error(
                            "Reel {$reel->id} Failed"
                        );

                        $this->error(
                            $e->getMessage()
                        );
                    }

                }

            });

        $this->info('');
        $this->info('=======================================');
        $this->info(' Reel Videos Renamed Successfully ');
        $this->info('=======================================');

        return Command::SUCCESS;
    }
}