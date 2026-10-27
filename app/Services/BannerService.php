<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class BannerService
{
    public function getBanners(
        ?string $page = null,
        ?string $position = null
    ): Collection {
        $query = Banner::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });

        if (in_array($page, ['home', 'category', 'product'], true)) {
            $query->where('page', $page);
        }

        if (in_array($position, ['hero', 'mid', 'bottom'], true)) {
            $query->where('position', $position);
        }

        return $query->orderBy('sort_order')->get();
    }

    public function findBanner(int|string $id): ?Banner
    {
        return Banner::query()
            ->where('id', $id)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->first();
    }

    public function getBannersByPage(string $page): Collection
    {
        return Banner::query()
            ->where('status', 1)
            ->where('page', $page)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();
    }

    public function getBannersByPosition(string $position): Collection
    {
        return Banner::query()
            ->where('status', 1)
            ->where('position', $position)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();
    }
}
