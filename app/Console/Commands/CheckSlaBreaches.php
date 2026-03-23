<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class CheckSlaBreaches extends Command
{
    protected $signature = 'tickets:check-sla';

    protected $description = 'Check for tickets with breached SLA deadlines and report them';

    public function handle(): int
    {
        $breachedTickets = Ticket::slaBreached()
            ->with(['user', 'assignee'])
            ->get();

        if ($breachedTickets->isEmpty()) {
            $this->info('No SLA breaches found.');
            return self::SUCCESS;
        }

        $this->warn("Found {$breachedTickets->count()} ticket(s) with breached SLA:");

        $rows = $breachedTickets->map(function (Ticket $ticket) {
            return [
                $ticket->id,
                $ticket->title,
                $ticket->priority,
                $ticket->status,
                $ticket->sla_deadline->format('Y-m-d H:i'),
                $ticket->assignee?->name ?? 'Unassigned',
            ];
        })->toArray();

        $this->table(
            ['ID', 'Title', 'Priority', 'Status', 'SLA Deadline', 'Assigned To'],
            $rows
        );

        return self::SUCCESS;
    }
}
