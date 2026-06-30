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
use App\Jobs\SendNewOrderMailJob;
use App\Models\NotificationSetting;
use App\Mail\UserOrderConfirmedMail;
use App\Jobs\SendUserOrderConfirmationMailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

public function handle(FirebaseService $firebase): void
{
    Log::info('New Order Notification Job Started', [
        'order_id' => $this->order->id,
        'order_number' => $this->order->order_number,
    ]);

    $emails = NotificationSetting::where('receive_order', true)
        ->where('is_active', true)
        ->pluck('email');

    Log::info('Notification Setting Emails', [
        'emails' => $emails->toArray()
    ]);

    $admins = User::role('admin')
        ->whereIn('email', $emails)
        ->get();

    Log::info('Matched Admin Users', [
        'count' => $admins->count(),
        'emails' => $admins->pluck('email')->toArray()
    ]);

    // Admin Notifications
    foreach ($admins as $admin) {

        Notification::create([
            'user_id' => $admin->id,
            'order_id' => $this->order->id,
            'title' => 'New Order Received',
            'message' => "Order #{$this->order->order_number} has been placed.",
            'type' => 'new_order',
            'action_url' => route('admin.orders.confirm', $this->order->id),
            'is_read' => false,
        ]);

        try {

            $response = $firebase->sendToUser(
                $admin->id,
                '🛒 New Order Received',
                "Order #{$this->order->order_number} has been placed.",
                [
                    'type' => 'new_order',
                    'order_id' => (string) $this->order->id,
                    'order_number' => (string) $this->order->order_number,
                    'url' => route('admin.orders.confirm', $this->order->id),
                ]
            );

            SendNewOrderMailJob::dispatch(
                $admin,
                $this->order
            );

            Log::info('Firebase Response', [
                'admin_id' => $admin->id,
                'response' => $response,
            ]);

        } catch (\Throwable $e) {

            Log::error('Firebase Notification Failed', [
                'admin_id' => $admin->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // User Order Confirmation Email
    try {

        $this->order->load('user');

        if ($this->order->user && $this->order->user->email) {

       SendUserOrderConfirmationMailJob::dispatch(
    $this->order->user,
    $this->order
);

            Log::info('User Order Confirmation Mail Sent', [
                'user_id' => $this->order->user->id,
                'email' => $this->order->user->email,
            ]);
        }

    } catch (\Throwable $e) {

        Log::error('User Order Confirmation Mail Failed', [
            'order_id' => $this->order->id,
            'message' => $e->getMessage(),
        ]);
    }
}
}