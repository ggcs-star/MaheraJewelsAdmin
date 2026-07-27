<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Amazon\AmazonOrderService;
use Throwable;

class SyncAmazonOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-amazon-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Amazon Orders from SP-API';

    /**
     * Execute the console command.
     */
    public function handle(AmazonOrderService $amazonOrderService): int
    {
        $this->info('========================================');
        $this->info(' Amazon Order Sync Started');
        $this->info('========================================');

        try {
            $syncLog = $amazonOrderService->syncOrders();
            if ($syncLog->message) {
    $this->error('Error: ' . $syncLog->message);
}

if ($syncLog->exception) {
    $this->error('Exception: ' . $syncLog->exception);
}

            $this->newLine();
            $this->info('✅ Amazon Orders Synced Successfully');
            $this->info('----------------------------------------');

           if ($syncLog) {
    $this->line('Status         : ' . $syncLog->status);
    $this->line('Module         : ' . $syncLog->module);
    $this->line('API            : ' . $syncLog->api_name);
    $this->line('HTTP Method    : ' . $syncLog->http_method);
    $this->line('HTTP Status    : ' . ($syncLog->http_status ?? 'N/A'));
    $this->line('Execution Time : ' . ($syncLog->execution_time ?? 'N/A'));
    $this->line('Synced At      : ' . ($syncLog->synced_at ?? 'N/A'));

    if (!empty($syncLog->message)) {
        $this->warn('Message        : ' . $syncLog->message);
    }

    if (!empty($syncLog->exception)) {
        $this->error('Exception      : ' . $syncLog->exception);
    }
}

            $this->newLine();
            $this->info('========================================');

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error('Amazon Sync Failed!');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}