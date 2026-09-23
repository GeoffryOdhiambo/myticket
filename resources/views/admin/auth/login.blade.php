<x-auth-card title="Super Admin Login" subtitle="Log in to manage the MyTicket platform." portal="Super Admin">
    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
        @csrf

        <x-input label="Email address" name="email" type="email" required autofocus />
        <x-input label="Password" name="password" type="password" required />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-neutral-600">
                <input type="checkbox" name="remember" class="rounded border-neutral-300 text-brand focus:ring-brand/30">
                Remember me
            </label>
            <a href="{{ route('admin.password.request') }}" class="text-sm font-semibold text-brand hover:text-brand-dark">Forgot password?</a>
        </div>

        <x-button type="submit" variant="dark" class="w-full">Log In</x-button>
    </form>
</x-auth-card>
