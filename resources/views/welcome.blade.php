<x-layouts.app title="Welcome">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="mx-auto max-w-4xl py-16 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                HelpDesk <span class="text-indigo-600">Support System</span>
            </h1>
            <p class="mt-6 text-lg leading-8 text-gray-600">
                A modern ticketing system built with Laravel and Livewire. Create tickets, track SLA compliance,
                manage priorities, and browse our knowledge base — all in one place.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                @auth
                    <a href="{{ route('tickets.create') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Create a Ticket
                    </a>
                    <a href="{{ route('tickets.index') }}" class="text-sm font-semibold leading-6 text-gray-900">
                        View My Tickets <span aria-hidden="true">&rarr;</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Login to Get Started
                    </a>
                    <a href="{{ route('knowledge-base') }}" class="text-sm font-semibold leading-6 text-gray-900">
                        Browse Knowledge Base <span aria-hidden="true">&rarr;</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-12">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">Key Features</h2>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Ticket Management</h3>
                    <p class="mt-2 text-sm text-gray-600">Create, track, and resolve support tickets with priorities and status workflows.</p>
                </div>

                <!-- Feature 2 -->
                <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">SLA Tracking</h3>
                    <p class="mt-2 text-sm text-gray-600">Automatic SLA deadlines based on priority. Monitor compliance and breaches in real-time.</p>
                </div>

                <!-- Feature 3 -->
                <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Knowledge Base</h3>
                    <p class="mt-2 text-sm text-gray-600">Searchable articles organized by category. Find answers before creating a ticket.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Credentials -->
    <div class="py-8">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-lg bg-indigo-50 p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-indigo-900 mb-4">Demo Credentials</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-indigo-700 font-medium">Admin:</span>
                        <span class="text-indigo-600 font-mono">admin@helpdesk.test / password</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-indigo-700 font-medium">Agent:</span>
                        <span class="text-indigo-600 font-mono">agent@helpdesk.test / password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
