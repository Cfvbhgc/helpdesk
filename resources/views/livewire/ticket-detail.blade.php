<x-layouts.app title="Ticket #{{ $ticket->id }}">
    <div class="max-w-4xl mx-auto">
        <!-- Back Link -->
        <div class="mb-4">
            <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                &larr; Back to Tickets
            </a>
        </div>

        <!-- Flash Messages -->
        @foreach(['success' => 'green', 'reply_success' => 'green', 'status_success' => 'blue', 'assign_success' => 'blue', 'status_error' => 'red'] as $key => $color)
            @if (session($key))
                <div class="mb-4 rounded-md bg-{{ $color }}-50 p-4">
                    <p class="text-sm text-{{ $color }}-700">{{ session($key) }}</p>
                </div>
            @endif
        @endforeach

        <!-- Ticket Header -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <span class="text-gray-400">#{{ $ticket->id }}</span>
                        {{ $ticket->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                        <span>by <strong>{{ $ticket->user->name }}</strong></span>
                        <span>&middot;</span>
                        <span>{{ $ticket->created_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Badges -->
            <div class="mt-4 flex flex-wrap gap-3">
                <!-- Priority Badge -->
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                    @if($ticket->priority === 'critical') bg-red-100 text-red-800
                    @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800
                    @endif">
                    Priority: {{ ucfirst($ticket->priority) }}
                </span>

                <!-- Status Badge -->
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                    @if($ticket->status === 'open') bg-blue-100 text-blue-800
                    @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                    @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    Status: {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                </span>

                <!-- SLA Badge -->
                @if($ticket->sla_deadline)
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        {{ $ticket->isSlaBreached() ? 'bg-red-100 text-red-800' : 'bg-indigo-100 text-indigo-800' }}">
                        SLA: {{ $ticket->isSlaBreached() ? 'BREACHED' : $ticket->sla_deadline->diffForHumans() }}
                    </span>
                @endif

                <!-- Assigned Badge -->
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800">
                    Assigned: {{ $ticket->assignee?->name ?? 'Unassigned' }}
                </span>
            </div>

            <!-- Description -->
            <div class="mt-6 prose prose-sm max-w-none">
                <h3 class="text-sm font-medium text-gray-900">Description</h3>
                <div class="mt-2 text-gray-700 whitespace-pre-wrap bg-gray-50 rounded-md p-4">{{ $ticket->description }}</div>
            </div>
        </div>

        <!-- Agent/Admin Actions -->
        @auth
            @if(auth()->user()->canManageTickets())
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Agent Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Status Transitions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <div class="flex flex-wrap gap-2">
                                @forelse($allowedTransitions as $transition)
                                    <button wire:click="updateStatus('{{ $transition }}')"
                                            class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-500">
                                        &rarr; {{ str_replace('_', ' ', ucfirst($transition)) }}
                                    </button>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No transitions available from current status.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Assign Ticket -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                            <div class="flex gap-2">
                                <select wire:model="assignTo"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                                    <option value="">Unassigned</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->role }})</option>
                                    @endforeach
                                </select>
                                <button wire:click="assignTicket"
                                        class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-500 whitespace-nowrap">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Replies -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Replies ({{ $ticket->replies->count() }})
            </h3>

            <div class="space-y-4">
                @forelse($ticket->replies as $reply)
                    <div class="rounded-lg p-4 {{ $reply->user->canManageTickets() ? 'bg-indigo-50 border border-indigo-100' : 'bg-gray-50 border border-gray-100' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-sm text-gray-900">{{ $reply->user->name }}</span>
                                @if($reply->user->canManageTickets())
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $reply->user->role }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $reply->created_at->format('M d, Y \a\t H:i') }}</span>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $reply->content }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-4">No replies yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Add Reply Form -->
        @auth
            @if($ticket->status !== 'closed')
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Reply</h3>
                    <form wire:submit="addReply">
                        <div>
                            <textarea wire:model="replyContent" rows="4"
                                      placeholder="Type your reply..."
                                      class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            @error('replyContent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove>Submit Reply</span>
                                <span wire:loading>Sending...</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                    <p class="text-sm text-gray-500">This ticket is closed. No further replies can be added.</p>
                </div>
            @endif
        @endauth
    </div>
</x-layouts.app>
