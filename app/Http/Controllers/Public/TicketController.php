<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function show(Ticket $ticket, QrCodeService $qr): View
    {
        $ticket->load('orderItem.order.event', 'orderItem.ticketType');

        return view('tickets.show', [
            'ticket' => $ticket,
            'qrDataUri' => $qr->dataUri($ticket),
        ]);
    }

    public function download(Ticket $ticket, QrCodeService $qr): Response
    {
        $ticket->load('orderItem.order.event', 'orderItem.ticketType');

        $pdf = Pdf::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'qrDataUri' => $qr->dataUri($ticket),
        ])->setPaper([0, 0, 566.93, 283.46]);

        return $pdf->download("{$ticket->ticket_number}.pdf");
    }
}
