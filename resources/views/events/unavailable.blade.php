<x-state-page icon="calendar-days" title="Event no longer available" description="This event has been unpublished or is no longer accepting ticket sales.">
    <x-slot:action>
        <x-button href="{{ route('events.index') }}">Browse Other Events</x-button>
    </x-slot:action>
</x-state-page>
