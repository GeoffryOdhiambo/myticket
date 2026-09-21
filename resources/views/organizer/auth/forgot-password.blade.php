<x-auth-card title="Forgot Password" subtitle="Enter your email and we'll send you a password reset link." portal="Organizer Portal">
    <form method="POST" action="{{ route('organizer.password.email') }}" class="space-y-5">
        @csrf

        <x-input label="Email address" name="email" type="email" required autofocus />

        <x-button type="submit" class="w-full">Send Reset Link</x-button>
    </form>

    <p class="mt-6 text-center text-sm text-neutral-500">
        <a href="{{ route('organizer.login') }}" class="font-semibold text-brand hover:text-brand-dark">Back to login</a>
    </p>
</x-auth-card>
