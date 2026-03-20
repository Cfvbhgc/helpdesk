<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Livewire\Component;

class TicketDetail extends Component
{
    public Ticket $ticket;
    public string $replyContent = '';
    public string $newStatus = '';
    public ?int $assignTo = null;

    protected $rules = [
        'replyContent' => 'required|string|min:2|max:5000',
    ];

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket->load(['user', 'assignee', 'replies.user']);
        $this->newStatus = $ticket->status;
        $this->assignTo = $ticket->assigned_to;
    }

    /**
     * Add a reply to the ticket.
     */
    public function addReply(): void
    {
        $this->validate();

        TicketReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
        ]);

        $this->replyContent = '';
        $this->ticket->refresh();
        $this->ticket->load('replies.user');

        session()->flash('reply_success', 'Reply added successfully.');
    }

    /**
     * Update ticket status using the workflow transition.
     */
    public function updateStatus(string $status): void
    {
        if ($this->ticket->canTransitionTo($status)) {
            $this->ticket->transitionTo($status);
            $this->newStatus = $status;
            session()->flash('status_success', 'Status updated to ' . str_replace('_', ' ', $status) . '.');
        } else {
            session()->flash('status_error', 'Invalid status transition.');
        }
    }

    /**
     * Assign ticket to an agent.
     */
    public function assignTicket(): void
    {
        $this->ticket->update(['assigned_to' => $this->assignTo]);
        $this->ticket->refresh();

        session()->flash('assign_success', 'Ticket assigned successfully.');
    }

    public function render()
    {
        $agents = User::whereIn('role', ['agent', 'admin'])->get();
        $allowedTransitions = Ticket::STATUS_TRANSITIONS[$this->ticket->status] ?? [];

        return view('livewire.ticket-detail', [
            'agents' => $agents,
            'allowedTransitions' => $allowedTransitions,
        ]);
    }
}
