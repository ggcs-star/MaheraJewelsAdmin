<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class S3Helper
{
    public static function store($file, string $path)
    {
        return $file->store($path, 's3');
    }
    public static function storeAs($file, string $path, string $name)
    {
        return $file->storeAs($path, $name, 's3');
    }
    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('s3')->delete($path);
        }
    }
    public static function put(string $path, $content)
    {
        Storage::disk('s3')->put($path, $content);
    }
    public static function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk('s3')->exists($path);
    }

    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk('s3')->url($path);
    }
}