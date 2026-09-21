<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::published()->upcoming()
            ->with(['category', 'ticketTypes'])
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%' . $request->string('q') . '%'))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category'))))
            ->when($request->filled('location'), fn ($query) => $query->where('location', 'like', '%' . $request->string('location') . '%'))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('event_date', $request->date('date')))
            ->orderBy('event_date')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'categories' => EventCategory::all(),
            'filters' => $request->only(['q', 'category', 'location', 'date']),
        ]);
    }

    public function show(Event $event): View|Response
    {
        if (! $event->isPublished()) {
            return response()->view('events.unavailable', [], 404);
        }

        $event->load(['category', 'organizer', 'ticketTypes' => fn ($query) => $query->where('status', 'active')]);

        return view('events.show', [
            'event' => $event,
        ]);
    }
}
