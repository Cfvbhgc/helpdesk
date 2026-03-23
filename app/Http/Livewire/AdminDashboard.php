<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        // SLA compliance
        $slaBreachedCount = Ticket::slaBreached()->count();
        $activeTickets = Ticket::whereNotIn('status', ['resolved', 'closed'])->count();
        $slaComplianceRate = $activeTickets > 0
            ? round((($activeTickets - $slaBreachedCount) / $activeTickets) * 100, 1)
            : 100;

        // Tickets by priority
        $ticketsByPriority = [];
        foreach (Ticket::PRIORITIES as $priority) {
            $ticketsByPriority[$priority] = Ticket::where('priority', $priority)->count();
        }

        // Recent tickets
        $recentTickets = Ticket::with(['user', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // SLA-breached tickets
        $slaBreachedTickets = Ticket::with(['user', 'assignee'])
            ->slaBreached()
            ->orderBy('sla_deadline', 'asc')
            ->limit(5)
            ->get();

        // Agent stats
        $agentStats = User::whereIn('role', ['agent', 'admin'])
            ->withCount([
                'assignedTickets',
                'assignedTickets as active_tickets_count' => function ($query) {
                    $query->whereIn('status', ['open', 'in_progress']);
                },
                'assignedTickets as resolved_tickets_count' => function ($query) {
                    $query->whereIn('status', ['resolved', 'closed']);
                },
            ])
            ->get();

        return view('livewire.admin-dashboard', [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'resolvedTickets' => $resolvedTickets,
            'closedTickets' => $closedTickets,
            'slaBreachedCount' => $slaBreachedCount,
            'slaComplianceRate' => $slaComplianceRate,
            'ticketsByPriority' => $ticketsByPriority,
            'recentTickets' => $recentTickets,
            'slaBreachedTickets' => $slaBreachedTickets,
            'agentStats' => $agentStats,
        ]);
    }
}
