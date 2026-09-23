<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketPurchased extends Notification
{
    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;
        $ticketCount = $order->items->sum('quantity');
        $qr = app(QrCodeService::class);

        $message = (new MailMessage)
            ->subject("Your ticket for {$order->event->name} is confirmed")
            ->greeting("Hi {$order->customer_name},")
            ->line("Your ticket for **{$order->event->name}** has been confirmed.")
            ->line("Order reference: **{$order->order_number}**")
            ->line("{$ticketCount} ticket(s) · {$order->event->event_date->format('D, d M Y')} · {$order->event->venue}");

        foreach ($order->items as $item) {
            foreach ($item->tickets as $ticket) {
                $message->action("View Ticket {$ticket->ticket_number}", route('ticket.show', $ticket));

                $pdf = Pdf::loadView('tickets.pdf', [
                    'ticket' => $ticket,
                    'qrDataUri' => $qr->dataUri($ticket),
                ])->setPaper([0, 0, 566.93, 283.46])->output();

                $message->attachData($pdf, "{$ticket->ticket_number}.pdf", ['mime' => 'application/pdf']);
            }
        }

        return $message
            ->line('Show the QR code on your ticket at the entrance to check in.')
            ->line('Thank you for booking with MyTicket!');
    }
}
