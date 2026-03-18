<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'HelpDesk' }} - HelpDesk</title>

    <!-- Tailwind CSS via CDN (for demo purposes) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-indigo-600">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796l-3.448 4.138m5.716-1.87l-4.138 3.448M7.288 19.67l3.448-4.138m-3.448 4.138a9.027 9.027 0 01-1.306-1.652 9.027 9.027 0 01-1.652-1.306m2.958 2.958a9.014 9.014 0 010-9.424m4.596 6.466a3.736 3.736 0 00.88 1.388 3.737 3.737 0 001.388.88m-2.268-2.268a3.765 3.765 0 010-2.528m2.268 4.796l3.448-4.138m-5.716 1.87l4.138-3.448" />
                            </svg>
                            <span class="text-white text-xl font-bold">HelpDesk</span>
                        </a>

                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('home') }}" class="text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Home</a>
                            <a href="{{ route('knowledge-base') }}" class="text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Knowledge Base</a>
                            @auth
                                <a href="{{ route('tickets.index') }}" class="text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Tickets</a>
                                <a href="{{ route('tickets.create') }}" class="text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-md px-3 py-2 text-sm font-medium">New Ticket</a>
                                @if(auth()->user()->canManageTickets())
                                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-indigo-100 text-sm">
                                {{ auth()->user()->name }}
                                <span class="ml-1 inline-flex items-center rounded-full bg-indigo-500 px-2 py-0.5 text-xs text-white">
                                    {{ auth()->user()->role }}
                                </span>
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-indigo-100 hover:text-white text-sm font-medium">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-indigo-100 hover:text-white text-sm font-medium">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-auto bg-white border-t border-gray-200">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} HelpDesk. Built with Laravel, Livewire & Tailwind CSS.
                </p>
            </div>
        </footer>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
