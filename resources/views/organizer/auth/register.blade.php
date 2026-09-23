<x-auth-card title="Create Organizer Account" subtitle="Register to start creating events and selling tickets on Tiko." portal="Organizer Portal">
    <form method="POST" action="{{ route('organizer.register.store') }}" class="space-y-5">
        @csrf

        <x-input label="Your name" name="name" required autofocus />
        <x-input label="Business name" name="business_name" required />
        <x-input label="Email address" name="email" type="email" required />
        <x-input label="Phone number" name="phone" required />
        <x-input label="Password" name="password" type="password" required hint="At least 8 characters." />
        <x-input label="Confirm password" name="password_confirmation" type="password" required />

        <x-button type="submit" class="w-full">Create Account</x-button>
    </form>

    <p class="mt-6 text-center text-sm text-neutral-500">
        Already have an account? <a href="{{ route('organizer.login') }}" class="font-semibold text-brand hover:text-brand-dark">Log in</a>
    </p>
</x-auth-card>
