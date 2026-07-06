<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class RenameCategoryImages extends Command
{
    protected $signature = 'category:rename-images';

    protected $description = 'Rename category images in S3 and update database';

    public function handle()
    {
        $this->info('Starting category image rename...');

        Category::orderBy('id')->chunk(100, function ($categories) {

            foreach ($categories as $category) {

                try {

                    if (empty($category->image_url)) {
                        $this->warn("Category {$category->id} : Image not found. Skipped.");
                        continue;
                    }

                    $oldPath = $category->getRawOriginal('image_url');

                    if (!Storage::disk('s3')->exists($oldPath)) {
                        $this->error("Image missing in S3 : {$oldPath}");
                        continue;
                    }

                    $extension = pathinfo($oldPath, PATHINFO_EXTENSION);

                    $directory = dirname($oldPath);

                    $newFileName = $category->slug . '-' . $category->id . '.' . $extension;

                    $newPath = $directory . '/' . $newFileName;

                    // Already renamed
                    if ($oldPath === $newPath) {
                        $this->line("Already renamed : {$oldPath}");
                        continue;
                    }

                    // New file already exists
                    if (Storage::disk('s3')->exists($newPath)) {

                        $category->update([
                            'image_url' => $newPath
                        ]);

                        $this->warn("Already exists : {$newPath}");
                        continue;
                    }

                    // Copy
                    Storage::disk('s3')->copy($oldPath, $newPath);

                    // Delete old
                    Storage::disk('s3')->delete($oldPath);

                    // Update DB
                    $category->update([
                        'image_url' => $newPath
                    ]);

                    $this->info("Renamed :");
                    $this->line("OLD : {$oldPath}");
                    $this->line("NEW : {$newPath}");
                    $this->line("-------------------------------------------");

                } catch (\Throwable $e) {

                    $this->error("Category ID {$category->id}");

                    $this->error($e->getMessage());

                    $this->line("-------------------------------------------");

                }

            }

        });

        $this->info("Done.");
    }
}