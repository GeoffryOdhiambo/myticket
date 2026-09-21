<x-state-page icon="exclamation-triangle" title="Too many requests" description="You've made too many attempts. Please wait a moment and try again.">
    <x-slot:action>
        <x-button href="{{ route('home') }}">Back to Home</x-button>
    </x-slot:action>
</x-state-page>
