<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use Livewire\Component;

class TicketCreate extends Component
{
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';

    protected $rules = [
        'title' => 'required|string|min:5|max:255',
        'description' => 'required|string|min:10|max:5000',
        'priority' => 'required|in:low,medium,high,critical',
    ];

    /**
     * Create a new ticket.
     */
    public function createTicket(): void
    {
        $this->validate();

        $ticket = Ticket::create([
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => 'open',
            'user_id' => auth()->id(),
        ]);

        session()->flash('success', 'Ticket #' . $ticket->id . ' created successfully.');
        $this->redirect(route('tickets.show', $ticket));
    }

    public function render()
    {
        return view('livewire.ticket-create');
    }
}
