<x-auth-card title="Organizer Login" subtitle="Log in to manage your events and ticket sales." portal="Organizer Portal">
    <form method="POST" action="{{ route('organizer.login.store') }}" class="space-y-5">
        @csrf

        <x-input label="Email address" name="email" type="email" required autofocus />
        <x-input label="Password" name="password" type="password" required />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-neutral-600">
                <input type="checkbox" name="remember" class="rounded border-neutral-300 text-brand focus:ring-brand/30">
                Remember me
            </label>
            <a href="{{ route('organizer.password.request') }}" class="text-sm font-semibold text-brand hover:text-brand-dark">Forgot password?</a>
        </div>

        <x-button type="submit" class="w-full">Log In</x-button>
    </form>

    <p class="mt-6 text-center text-sm text-neutral-500">
        Don't have an account? <a href="{{ route('organizer.register') }}" class="font-semibold text-brand hover:text-brand-dark">Register</a>
    </p>
</x-auth-card>
