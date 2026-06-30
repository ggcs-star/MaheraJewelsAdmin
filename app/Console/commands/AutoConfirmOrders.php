<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\DeliverySetting;
use Illuminate\Console\Command;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-confirm-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically confirm pending orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setting = DeliverySetting::first();

        if (!$setting) {
            $this->error('Delivery settings not found.');

            return Command::FAILURE;
        }

        if (!$setting->auto_confirm_enabled) {
            $this->info('Auto Confirm is disabled.');

            return Command::SUCCESS;
        }

        $minutes = $setting->auto_confirm_minutes;

        $orders = Order::where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes($minutes))
            ->get();

        if ($orders->isEmpty()) {

            $this->info("No pending orders older than {$minutes} minutes.");

            return Command::SUCCESS;
        }

        foreach ($orders as $order) {

            $order->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            $this->info("Order #{$order->order_number} confirmed successfully.");
        }

        $this->info("Total Orders Confirmed : {$orders->count()}");

        return Command::SUCCESS;
    }
}