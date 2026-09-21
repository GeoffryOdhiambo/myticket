<x-state-page icon="exclamation-circle" title="Something went wrong" description="An unexpected error occurred on our end. Please try again in a moment.">
    <x-slot:action>
        <x-button href="{{ route('home') }}">Back to Home</x-button>
    </x-slot:action>
</x-state-page>
