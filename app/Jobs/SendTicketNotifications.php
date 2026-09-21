<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\TicketPurchased;
use App\Services\Notifications\WhatsAppServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTicketNotifications implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $orderId) {}

    public function handle(WhatsAppServiceInterface $whatsApp): void
    {
        $order = Order::with('items.tickets', 'items.ticketType', 'event')->find($this->orderId);

        if (! $order || ! $order->isPaid()) {
            return;
        }

        $order->notify(new TicketPurchased($order));

        $whatsApp->sendTicketConfirmation($order);
    }
}
