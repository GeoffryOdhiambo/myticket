<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Organizer\Concerns\AuthorizesOwnership;
use App\Http\Requests\StoreTicketTypeRequest;
use App\Http\Requests\UpdateTicketTypeRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TicketTypeController extends Controller
{
    use AuthorizesOwnership;

    public function create(Event $event): View
    {
        $this->ensureOwnsEvent($event);

        return view('organizer.ticket-types.create', ['event' => $event]);
    }

    public function store(StoreTicketTypeRequest $request, Event $event): RedirectResponse
    {
        $this->ensureOwnsEvent($event);

        $event->ticketTypes()->create($request->validated());

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket type added.');
    }

    public function edit(TicketType $ticketType): View
    {
        $this->ensureOwnsTicketType($ticketType);

        return view('organizer.ticket-types.edit', ['ticketType' => $ticketType]);
    }

    public function update(UpdateTicketTypeRequest $request, TicketType $ticketType): RedirectResponse
    {
        $this->ensureOwnsTicketType($ticketType);

        $ticketType->update($request->validated());

        return redirect()->route('organizer.events.show', $ticketType->event)->with('success', 'Ticket type updated.');
    }

    public function destroy(TicketType $ticketType): RedirectResponse
    {
        $this->ensureOwnsTicketType($ticketType);
        $event = $ticketType->event;

        if ($ticketType->quantity_sold > 0) {
            return back()->with('error', 'Cannot delete a ticket type that already has sales. Disable it instead.');
        }

        $ticketType->delete();

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket type removed.');
    }
}
