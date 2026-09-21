<?php

namespace App\Services\Notifications;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Default WhatsApp driver: logs the message that would be sent instead of
 * calling a real provider. Swap the `whatsapp.driver` config binding for a
 * real implementation (e.g. Twilio, Meta Cloud API) when one is chosen.
 */
class LogWhatsAppService implements WhatsAppServiceInterface
{
    public function sendTicketConfirmation(Order $order): void
    {
        $ticketCount = $order->items->sum('quantity');

        Log::info('[WhatsApp] Ticket confirmation', [
            'to' => $order->customer_whatsapp,
            'message' => "Your ticket for {$order->event->name} has been confirmed. "
                . "Order reference: {$order->order_number}. {$ticketCount} ticket(s) attached.",
        ]);
    }
}
