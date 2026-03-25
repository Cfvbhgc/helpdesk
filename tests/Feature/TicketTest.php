<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_sla_is_set_on_creation(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'Test ticket',
            'description' => 'Test description for the ticket',
            'priority' => 'critical',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($ticket->sla_deadline);
        // Critical SLA = 4 hours
        $this->assertTrue($ticket->sla_deadline->diffInHours(now()) <= 4);
    }

    public function test_ticket_status_transitions(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'Test ticket',
            'description' => 'Test description for the ticket',
            'priority' => 'medium',
            'user_id' => $user->id,
        ]);

        $this->assertEquals('open', $ticket->status);

        // Valid transition: open -> in_progress
        $this->assertTrue($ticket->canTransitionTo('in_progress'));
        $ticket->transitionTo('in_progress');
        $this->assertEquals('in_progress', $ticket->status);

        // Invalid transition: in_progress -> closed
        $this->assertFalse($ticket->canTransitionTo('closed'));

        // Valid transition: in_progress -> resolved
        $this->assertTrue($ticket->canTransitionTo('resolved'));
        $ticket->transitionTo('resolved');
        $this->assertEquals('resolved', $ticket->status);

        // Valid transition: resolved -> closed
        $this->assertTrue($ticket->canTransitionTo('closed'));
        $ticket->transitionTo('closed');
        $this->assertEquals('closed', $ticket->status);
    }

    public function test_sla_breach_detection(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'Breached ticket',
            'description' => 'This ticket has a past SLA deadline',
            'priority' => 'critical',
            'user_id' => $user->id,
        ]);

        // Manually set SLA to the past
        $ticket->update(['sla_deadline' => now()->subHours(1)]);

        $this->assertTrue($ticket->isSlaBreached());
    }

    public function test_resolved_ticket_is_not_sla_breached(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'Resolved ticket',
            'description' => 'This ticket was resolved before SLA',
            'priority' => 'critical',
            'status' => 'open',
            'user_id' => $user->id,
        ]);

        $ticket->update([
            'sla_deadline' => now()->subHours(1),
            'status' => 'resolved',
        ]);

        $this->assertFalse($ticket->isSlaBreached());
    }

    public function test_authenticated_user_can_view_tickets(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tickets.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_tickets(): void
    {
        $response = $this->get(route('tickets.index'));
        $response->assertRedirect(route('login'));
    }
}
