<x-layouts.app title="Admin Dashboard">
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Overview of ticket statistics and SLA compliance.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <dt class="text-sm font-medium text-gray-500">Total Tickets</dt>
                <dd class="mt-1 text-3xl font-bold text-gray-900">{{ $totalTickets }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4">
                <dt class="text-sm font-medium text-blue-600">Open</dt>
                <dd class="mt-1 text-3xl font-bold text-blue-900">{{ $openTickets }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-4">
                <dt class="text-sm font-medium text-yellow-600">In Progress</dt>
                <dd class="mt-1 text-3xl font-bold text-yellow-900">{{ $inProgressTickets }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4">
                <dt class="text-sm font-medium text-green-600">Resolved</dt>
                <dd class="mt-1 text-3xl font-bold text-green-900">{{ $resolvedTickets }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <dt class="text-sm font-medium text-gray-500">Closed</dt>
                <dd class="mt-1 text-3xl font-bold text-gray-900">{{ $closedTickets }}</dd>
            </div>
        </div>

        <!-- SLA Compliance + Priority Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- SLA Compliance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SLA Compliance</h3>
                <div class="flex items-center justify-center mb-4">
                    <div class="relative">
                        <div class="text-center">
                            <span class="text-5xl font-bold {{ $slaComplianceRate >= 90 ? 'text-green-600' : ($slaComplianceRate >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $slaComplianceRate }}%
                            </span>
                            <p class="text-sm text-gray-500 mt-1">Compliance Rate</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">SLA Breached Tickets</span>
                        <span class="font-medium text-red-600">{{ $slaBreachedCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Tickets by Priority -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tickets by Priority</h3>
                <div class="space-y-4">
                    @foreach($ticketsByPriority as $priority => $count)
                        @php
                            $maxCount = max(1, max($ticketsByPriority));
                            $percentage = ($count / $maxCount) * 100;
                            $colors = [
                                'critical' => 'bg-red-500',
                                'high' => 'bg-orange-500',
                                'medium' => 'bg-yellow-500',
                                'low' => 'bg-green-500',
                            ];
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">{{ ucfirst($priority) }}</span>
                                <span class="text-gray-500">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $colors[$priority] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SLA Breached Tickets + Agent Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- SLA Breached -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <span class="text-red-600">SLA Breached Tickets</span>
                </h3>
                @if($slaBreachedTickets->isEmpty())
                    <p class="text-sm text-green-600 text-center py-4">No SLA breaches. Great job!</p>
                @else
                    <div class="space-y-3">
                        @foreach($slaBreachedTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket) }}" class="block p-3 rounded-md bg-red-50 border border-red-100 hover:bg-red-100 transition-colors">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-900">#{{ $ticket->id }} {{ Str::limit($ticket->title, 35) }}</span>
                                    <span class="text-xs text-red-600 font-medium">{{ $ticket->sla_deadline->diffForHumans() }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Assigned: {{ $ticket->assignee?->name ?? 'Unassigned' }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Agent Performance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Agent Performance</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Active</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Resolved</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($agentStats as $agent)
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        {{ $agent->name }}
                                        <span class="text-xs text-gray-400 ml-1">({{ $agent->role }})</span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-center text-yellow-600 font-medium">{{ $agent->active_tickets_count }}</td>
                                    <td class="px-3 py-2 text-sm text-center text-green-600 font-medium">{{ $agent->resolved_tickets_count }}</td>
                                    <td class="px-3 py-2 text-sm text-center text-gray-900 font-medium">{{ $agent->assigned_tickets_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Tickets</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">#{{ $ticket->id }}</a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ Str::limit($ticket->title, 40) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        @if($ticket->priority === 'critical') bg-red-100 text-red-800
                                        @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                        @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
