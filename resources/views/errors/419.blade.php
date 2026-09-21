<x-state-page icon="clock" title="Page expired" description="Your session took too long. Please refresh and try again.">
    <x-slot:action>
        <x-button href="{{ url()->previous() }}">Go Back</x-button>
    </x-slot:action>
</x-state-page>
