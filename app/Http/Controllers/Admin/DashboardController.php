<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRevenue = Order::where('status', 'paid')->sum('total');

        return view('admin.dashboard', [
            'totalOrganizers' => Organizer::count(),
            'totalEvents' => Event::count(),
            'activeEvents' => Event::where('status', 'published')->count(),
            'ticketsSold' => Ticket::count(),
            'totalSales' => $totalRevenue,
            'platformRevenue' => Setting::current()->platformFee($totalRevenue),
            'recentEvents' => Event::with('organizer')->latest()->take(6)->get(),
        ]);
    }
}
