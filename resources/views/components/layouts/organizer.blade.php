@props(['title' => null, 'heading' => null])

@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'squares-2x2', 'url' => route('organizer.dashboard'), 'active' => request()->routeIs('organizer.dashboard')],
        ['label' => 'Events', 'icon' => 'calendar-days', 'url' => route('organizer.events.index'), 'active' => request()->routeIs('organizer.events.*')],
        ['label' => 'Sales', 'icon' => 'ticket', 'url' => route('organizer.tickets.index'), 'active' => request()->routeIs('organizer.tickets.*')],
        ['label' => 'Scanner', 'icon' => 'qr-code', 'url' => route('organizer.scanner'), 'active' => request()->routeIs('organizer.scanner')],
    ];
@endphp

<x-dashboard-shell
    :title="$title"
    :heading="$heading"
    portal="Organizer Portal"
    :nav-items="$navItems"
    :user-name="auth('organizer')->user()->business_name ?? ''"
    :logout-route="route('organizer.logout')"
>
    {{ $slot }}
</x-dashboard-shell>
