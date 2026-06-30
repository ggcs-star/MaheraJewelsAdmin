<?php

namespace App\Jobs;

use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewOrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $admin;
    public Order $order;

    public function __construct(User $admin, Order $order)
    {
        $this->admin = $admin;
        $this->order = $order;
    }

public function handle(): void
{
    try {

        \Log::info('Mail Job Started', [
            'email' => $this->admin->email,
            'order' => $this->order->order_number,
        ]);

        Mail::to($this->admin->email)
            ->send(new NewOrderMail($this->order));

        \Log::info('Mail Sent Successfully', [
            'email' => $this->admin->email,
        ]);

    } catch (\Throwable $e) {

        \Log::error('Mail Send Failed', [
            'email' => $this->admin->email,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        throw $e;
    }
}
}