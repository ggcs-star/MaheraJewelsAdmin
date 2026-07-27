<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAmazonInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-amazon-inventory';

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
    app(\App\Services\Amazon\AmazonInventoryService::class)->syncInventory();

    $this->info('Amazon inventory synced successfully.');
}
}
