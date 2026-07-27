<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAmazonProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-amazon-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
public function handle()
{
    $result = app(\App\Services\Amazon\AmazonProductSyncService::class)->sync();

    $this->info('Success : ' . $result['success']);
    $this->info('Failed  : ' . $result['failed']);
}
}
