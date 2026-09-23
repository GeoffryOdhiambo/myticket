<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Organizer\Concerns\AuthorizesOwnership;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use AuthorizesOwnership;

    public function index(): View
    {
        $events = Auth::guard('organizer')->user()
            ->events()
            ->withCount('ticketTypes')
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', ['events' => $events]);
    }

    public function create(): View
    {
        return view('organizer.events.create', [
            'categories' => EventCategory::all(),
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $path = $request->file('image')->store('events', 'public');

        Auth::guard('organizer')->user()->events()->create([
            ...$request->validated(),
            'image_url' => Storage::url($path),
            'status' => 'draft',
        ]);

        return redirect()->route('organizer.events.index')->with('success', 'Event created as a draft. Publish it when you\'re ready.');
    }

    public function show(Event $event): View
    {
        $this->ensureOwnsEvent($event);
        $event->load('ticketTypes', 'category');

        $ticketsSold = Ticket::whereHas('orderItem.order', fn ($q) => $q->where('event_id', $event->id)->where('status', 'paid'))->count();
        $grossSales = Order::where('event_id', $event->id)->where('status', 'paid')->sum('total');
        $settings = Setting::current();
        $platformFee = $settings->platformFee($grossSales);

        return view('organizer.events.show', [
            'event' => $event,
            'ticketsSold' => $ticketsSold,
            'grossSales' => $grossSales,
            'platformFee' => $platformFee,
            'earnings' => $grossSales - $platformFee,
        ]);
    }

    public function edit(Event $event): View
    {
        $this->ensureOwnsEvent($event);

        return view('organizer.events.edit', [
            'event' => $event,
            'categories' => EventCategory::all(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->ensureOwnsEvent($event);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::url($request->file('image')->store('events', 'public'));
        }

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->ensureOwnsEvent($event);

        if ($event->orders()->exists()) {
            return back()->with('error', 'Cannot delete an event that already has ticket sales.');
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event deleted.');
    }

    public function publish(Event $event): RedirectResponse
    {
        $this->ensureOwnsEvent($event);

        if ($event->ticketTypes()->count() === 0) {
            return back()->with('error', 'Add at least one ticket type before publishing.');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event published.');
    }

    public function unpublish(Event $event): RedirectResponse
    {
        $this->ensureOwnsEvent($event);
        $event->update(['status' => 'unpublished']);

        return back()->with('success', 'Event unpublished.');
    }

    /**
     * The in-app attendee list, so an organizer can see who has already
     * checked in versus who is still pending without leaving the browser.
     * A printable/PDF version is available from here as a door-side backup.
     */
    public function attendees(Event $event, Request $request): View
    {
        $this->ensureOwnsEvent($event);

        $tickets = $this->attendeeTickets($event);

        $status = $request->string('status')->value();
        $filtered = match ($status) {
            'checked_in' => $tickets->where('checked_in', true)->values(),
            'pending' => $tickets->where('checked_in', false)->values(),
            default => $tickets,
        };

        return view('organizer.events.attendees', [
            'event' => $event,
            'tickets' => $filtered,
            'totalCount' => $tickets->count(),
            'checkedInCount' => $tickets->where('checked_in', true)->count(),
            'pendingCount' => $tickets->where('checked_in', false)->count(),
            'status' => $status,
        ]);
    }

    /**
     * A printable attendee list (ticket number, guest name, ticket type,
     * phone, a checkbox to tick manually) so entry staff have a backup
     * if QR scanning fails at the door.
     */
    public function downloadAttendees(Event $event): Response
    {
        $this->ensureOwnsEvent($event);

        $pdf = Pdf::loadView('organizer.events.attendees-pdf', [
            'event' => $event,
            'tickets' => $this->attendeeTickets($event),
        ])->setPaper('a4', 'portrait');

        return $pdf->download(Str::slug($event->name).'-attendee-list.pdf');
    }

    private function attendeeTickets(Event $event): Collection
    {
        return Ticket::query()
            ->whereHas('orderItem.order', fn ($q) => $q->where('event_id', $event->id)->where('status', 'paid'))
            ->with(['orderItem.order', 'orderItem.ticketType'])
            ->get()
            ->sortBy('ticket_number', SORT_NATURAL)
            ->values();
    }
}
