<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Organizer;
use App\Models\Payment;
use App\Models\Ticket;
use App\Services\ImageOptimizerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $eventIds = $events->getCollection()->pluck('id');

        $soldByEvent = Ticket::query()
            ->join('order_items', 'order_items.id', '=', 'tickets.order_item_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.event_id', $eventIds)
            ->where('orders.status', 'paid')
            ->selectRaw('orders.event_id, count(*) as sold')
            ->groupBy('orders.event_id')
            ->pluck('sold', 'event_id');

        $revenueByEvent = Order::whereIn('event_id', $eventIds)
            ->where('status', 'paid')
            ->selectRaw('event_id, sum(total) as revenue')
            ->groupBy('event_id')
            ->pluck('revenue', 'event_id');

        $events->getCollection()->transform(function (Event $event) use ($soldByEvent, $revenueByEvent) {
            $event->tickets_sold = $soldByEvent->get($event->id, 0);
            $event->revenue = $revenueByEvent->get($event->id, 0);

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

    public function update(UpdateEventRequest $request, Event $event, ImageOptimizerService $images): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::url($images->store($request->file('image'), 'events'));
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

    /**
     * Permanently delete an event and everything under it (ticket types,
     * orders, order items, payments, tickets). Unlike the organizer's own
     * delete action, the Super Admin can remove an event even if it has
     * sales — full platform authority per the spec — so this is only
     * exposed in the admin dashboard, never to organizers.
     */
    public function destroy(Event $event): RedirectResponse
    {
        DB::transaction(function () use ($event) {
            $orderIds = $event->orders()->pluck('id');
            $orderItemIds = OrderItem::whereIn('order_id', $orderIds)->pluck('id');

            Ticket::whereIn('order_item_id', $orderItemIds)->delete();
            Payment::whereIn('order_id', $orderIds)->delete();
            OrderItem::whereIn('id', $orderItemIds)->delete();
            Order::whereIn('id', $orderIds)->delete();
            $event->ticketTypes()->delete();
            $event->delete();
        });

        return redirect()->route('admin.events.index')->with('success', 'Event and all associated data permanently deleted.');
    }
}
