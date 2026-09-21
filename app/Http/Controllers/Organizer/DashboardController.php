<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizer = Auth::guard('organizer')->user();

        $events = $organizer->events()->latest()->get();
        $eventIds = $events->pluck('id');

        return view('organizer.dashboard', [
            'totalEvents' => $events->count(),
            'activeEvents' => $events->where('status', 'published')->count(),
            'ticketsSold' => Ticket::whereHas('orderItem.order', function ($query) use ($eventIds) {
                $query->whereIn('event_id', $eventIds)->where('status', 'paid');
            })->count(),
            'totalRevenue' => Order::whereIn('event_id', $eventIds)->where('status', 'paid')->sum('total'),
            'recentEvents' => $events->take(5),
        ]);
    }
}
