<footer class="border-t border-neutral-100 bg-neutral-50">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-4">
            <div>
                <x-logo />
                <p class="mt-3 max-w-xs text-sm text-neutral-500">
                    MyTicket makes it simple to discover events and buy tickets online — concerts, parties, conferences and festivals.
                </p>
            </div>

            <div>
                <p class="text-sm font-semibold text-neutral-900">Explore</p>
                <ul class="mt-3 space-y-2 text-sm text-neutral-500">
                    <li><a href="{{ route('pages.about') }}" class="hover:text-neutral-900">About</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-neutral-900">Events</a></li>
                    <li><a href="{{ route('organizer.login') }}" class="hover:text-neutral-900">For Organizers</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-semibold text-neutral-900">Support</p>
                <ul class="mt-3 space-y-2 text-sm text-neutral-500">
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-neutral-900">Contact</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="hover:text-neutral-900">Terms</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="hover:text-neutral-900">Privacy</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-semibold text-neutral-900">Get in touch</p>
                <ul class="mt-3 space-y-2 text-sm text-neutral-500">
                    <li>{{ \App\Models\Setting::current()->support_email }}</li>
                    <li>{{ \App\Models\Setting::current()->support_phone }}</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-neutral-200 pt-6 text-xs text-neutral-400 sm:flex-row">
            <p>&copy; {{ now()->year }} MyTicket. All rights reserved.</p>
            <p>Built for events, entertainment, and unforgettable experiences.</p>
        </div>
    </div>
</footer>
