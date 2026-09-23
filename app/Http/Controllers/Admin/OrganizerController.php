<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrganizerRequest;
use App\Http\Requests\Admin\UpdateOrganizerRequest;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Notifications\OrganizerApproved;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function index(Request $request): View
    {
        $organizers = Organizer::withCount('events')
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->string('q');
                $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.organizers.index', [
            'organizers' => $organizers,
            'filters' => $request->only('q'),
        ]);
    }

    public function create(): View
    {
        return view('admin.organizers.create');
    }

    public function store(StoreOrganizerRequest $request): RedirectResponse
    {
        Organizer::create([
            ...$request->validated(),
            'status' => 'active',
        ]);

        return redirect()->route('admin.organizers.index')->with('success', 'Organizer account created.');
    }

    public function show(Organizer $organizer): View
    {
        $eventIds = $organizer->events()->pluck('id');

        return view('admin.organizers.show', [
            'organizer' => $organizer,
            'events' => $organizer->events()->latest()->get(),
            'ticketsSold' => Ticket::whereHas('orderItem.order', fn ($q) => $q->whereIn('event_id', $eventIds)->where('status', 'paid'))->count(),
            'totalSales' => Order::whereIn('event_id', $eventIds)->where('status', 'paid')->sum('total'),
        ]);
    }

    public function edit(Organizer $organizer): View
    {
        return view('admin.organizers.edit', ['organizer' => $organizer]);
    }

    public function update(UpdateOrganizerRequest $request, Organizer $organizer): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $organizer->update($data);

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'Organizer updated.');
    }

    public function suspend(Organizer $organizer): RedirectResponse
    {
        $organizer->update(['status' => 'suspended']);

        return back()->with('success', 'Organizer suspended.');
    }

    public function activate(Organizer $organizer): RedirectResponse
    {
        $organizer->update(['status' => 'active']);

        return back()->with('success', 'Organizer activated.');
    }

    public function approve(Organizer $organizer): RedirectResponse
    {
        $organizer->update(['status' => 'active']);
        $organizer->notify(new OrganizerApproved);

        return back()->with('success', 'Organizer approved. They can now log in.');
    }
}
