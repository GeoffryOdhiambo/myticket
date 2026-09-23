@props(['title', 'subtitle' => null, 'portal' => null])

<x-layouts.base :title="$title">
    <div class="flex min-h-screen items-center justify-center bg-neutral-50 px-4 py-8 sm:py-12">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-semibold text-neutral-500 hover:text-neutral-900">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Back to Tiko
            </a>

            <div class="mb-6 flex flex-col items-center text-center sm:mb-8">
                <a href="{{ route('home') }}">
                    <x-logo size="text-2xl sm:text-3xl" />
                </a>
                @if($portal)
                    <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-neutral-400">{{ $portal }}</p>
                @endif
            </div>

            <x-card class="shadow-md">
                <h1 class="text-xl font-extrabold text-neutral-900">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-1.5 text-sm text-neutral-500">{{ $subtitle }}</p>
                @endif

                @if(session('success'))
                    <x-toast type="success">{{ session('success') }}</x-toast>
                @endif

                @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                    <x-alert type="error" class="mt-5">{{ $errors->first() }}</x-alert>
                @endif

                <div class="mt-6">
                    {{ $slot }}
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.base>
