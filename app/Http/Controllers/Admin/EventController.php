<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::with('organizer', 'category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('organizer'), fn ($q) => $q->where('organizer_id', $request->integer('organizer')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $events->getCollection()->transform(function (Event $event) {
            $event->tickets_sold = Ticket::whereHas('orderItem.order', fn ($q) => $q->where('event_id', $event->id)->where('status', 'paid'))->count();
            $event->revenue = Order::where('event_id', $event->id)->where('status', 'paid')->sum('total');

            return $event;
        });

        return view('admin.events.index', [
            'events' => $events,
            'organizers' => Organizer::orderBy('business_name')->get(),
            'filters' => $request->only(['q', 'organizer', 'status']),
        ]);
    }

    public function show(Event $event): View
    {
        $event->load('organizer', 'category', 'ticketTypes');

        return view('admin.events.show', [
            'event' => $event,
            'ticketsSold' => Ticket::whereHas('orderItem.order', fn ($q) => $q->where('event_id', $event->id)->where('status', 'paid'))->count(),
            'revenue' => Order::where('event_id', $event->id)->where('status', 'paid')->sum('total'),
        ]);
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', [
            'event' => $event,
            'categories' => EventCategory::all(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::url($request->file('image')->store('events', 'public'));
        }

        $event->update($data);

        return redirect()->route('admin.events.show', $event)->with('success', 'Event updated.');
    }

    public function publish(Event $event): RedirectResponse
    {
        $event->update(['status' => 'published']);

        return back()->with('success', 'Event published.');
    }

    public function unpublish(Event $event): RedirectResponse
    {
        $event->update(['status' => 'unpublished']);

        return back()->with('success', 'Event unpublished.');
    }

    public function suspend(Event $event): RedirectResponse
    {
        $event->update(['status' => 'suspended']);

        return back()->with('success', 'Event suspended.');
    }
}
