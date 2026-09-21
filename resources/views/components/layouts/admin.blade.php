@props(['title' => null, 'heading' => null])

@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'squares-2x2', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
        ['label' => 'Organizers', 'icon' => 'building-storefront', 'url' => route('admin.organizers.index'), 'active' => request()->routeIs('admin.organizers.*')],
        ['label' => 'Events', 'icon' => 'calendar-days', 'url' => route('admin.events.index'), 'active' => request()->routeIs('admin.events.*')],
        ['label' => 'Tickets', 'icon' => 'ticket', 'url' => route('admin.tickets.index'), 'active' => request()->routeIs('admin.tickets.*')],
        ['label' => 'Settings', 'icon' => 'cog-6-tooth', 'url' => route('admin.settings.edit'), 'active' => request()->routeIs('admin.settings.*')],
    ];
@endphp

<x-dashboard-shell
    :title="$title"
    :heading="$heading"
    portal="Super Admin"
    :nav-items="$navItems"
    :user-name="auth()->user()->name ?? ''"
    :logout-route="route('admin.logout')"
>
    {{ $slot }}
</x-dashboard-shell>
