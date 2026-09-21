<x-auth-card title="Reset Password" subtitle="Choose a new password for your Super Admin account." portal="Super Admin">
    <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-input label="Email address" name="email" type="email" :value="$email" required autofocus />
        <x-input label="New password" name="password" type="password" required hint="At least 8 characters." />
        <x-input label="Confirm new password" name="password_confirmation" type="password" required />

        <x-button type="submit" variant="dark" class="w-full">Reset Password</x-button>
    </form>
</x-auth-card>
