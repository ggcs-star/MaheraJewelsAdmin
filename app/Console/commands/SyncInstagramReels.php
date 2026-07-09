<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstagramReel;
use App\Models\SocialAccount;
use App\Services\InstagramGraphService;

class SyncInstagramReels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:sync-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync latest Instagram posts from Graph API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = SocialAccount::where('platform', 'instagram')
            ->where('is_active', true)
            ->first();

        if (!$account) {
            $this->error('Instagram account not connected.');
            return Command::FAILURE;
        }

        $instagram = app(InstagramGraphService::class);

        $response = $instagram->getMedia(
            $account->instagram_business_id,
            $account->access_token
        );

        if (!isset($response['data'])) {
            $this->error('Unable to fetch Instagram posts.');
            return Command::FAILURE;
        }

        $count = 0;

        foreach ($response['data'] as $media) {

            InstagramReel::updateOrCreate(

                [
                    'instagram_media_id' => $media['id'],
                ],

                [
                    'caption' => $media['caption'] ?? null,

                    'media_type' => $media['media_type'] ?? null,

                    'media_product_type' => $media['media_product_type'] ?? null,

                    'media_url' => $media['media_url'] ?? null,

                    'thumbnail_url' => $media['thumbnail_url'] ?? null,

                    'permalink' => $media['permalink'] ?? null,

                    'instagram_created_at' => $media['timestamp'] ?? null,

                    'is_active' => true,
                ]
            );

            $count++;
        }

        $this->info("Successfully synced {$count} Instagram posts.");

        return Command::SUCCESS;
    }
}