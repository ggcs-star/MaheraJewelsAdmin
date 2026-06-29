<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
    }

    public function handle(FirebaseService $firebase)
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {

            // Save notification in DB
            Notification::create([
                'user_id' => $admin->id,
                'order_id' => $this->order->id,
                'title' => 'New Order Received',
                'message' => "Order #{$this->order->order_number} has been placed.",
                'type' => 'new_order',
                'action_url' => route(
                    'admin.orders.show',
                    $this->order->id
                ),
                'is_read' => false,
            ]);

            // Send Push Notification
            $firebase->sendToUser(
                $admin->id,
                '🛒 New Order Received',
                "Order #{$this->order->order_number} has been placed.",
                [
                    'type' => 'new_order',
                    'order_id' => (string) $this->order->id,
                    'order_number' => $this->order->order_number,
                ]
            );
        }
    }
}