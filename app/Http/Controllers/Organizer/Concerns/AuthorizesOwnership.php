<?php

namespace App\Http\Controllers\Organizer\Concerns;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Auth;

trait AuthorizesOwnership
{
    protected function ensureOwnsEvent(Event $event): void
    {
        abort_unless($event->organizer_id === Auth::guard('organizer')->id(), 403);
    }

    protected function ensureOwnsTicketType(TicketType $ticketType): void
    {
        abort_unless($ticketType->event->organizer_id === Auth::guard('organizer')->id(), 403);
    }
}
