<?php

namespace App\Services\Notifications;

use App\Models\Order;

interface WhatsAppServiceInterface
{
    /**
     * Send the ticket confirmation message (with reference and ticket
     * document) to the customer's WhatsApp number.
     */
    public function sendTicketConfirmation(Order $order): void;
}
