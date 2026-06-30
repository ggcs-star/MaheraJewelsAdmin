<?php

namespace App\Jobs;

use App\Mail\UserOrderConfirmedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUserOrderConfirmationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public Order $order;

    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    public function handle(): void
    {
        try {

            \Log::info('User Mail Job Started', [
                'email' => $this->user->email,
                'order' => $this->order->order_number,
            ]);

            Mail::to($this->user->email)
                ->send(new UserOrderConfirmedMail($this->order));

            \Log::info('User Mail Sent Successfully', [
                'email' => $this->user->email,
            ]);

        } catch (\Throwable $e) {

            \Log::error('User Mail Send Failed', [
                'email' => $this->user->email,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }
}