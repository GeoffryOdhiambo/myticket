<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Organizer;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featuredEvents' => Event::published()->upcoming()
                ->with(['category', 'ticketTypes'])
                ->orderBy('event_date')
                ->take(6)
                ->get(),
            'categories' => EventCategory::withCount('events')->get(),
            'stats' => [
                'events' => Event::published()->count(),
                'organizers' => Organizer::where('status', 'active')->count(),
                'tickets' => Ticket::count(),
            ],
        ]);
    }
}
