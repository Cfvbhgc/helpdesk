<?php

namespace Tests\Unit;

use App\Models\Ticket;
use PHPUnit\Framework\TestCase;

class TicketModelTest extends TestCase
{
    public function test_priority_sla_hours_are_defined(): void
    {
        $this->assertEquals(4, Ticket::PRIORITY_SLA_HOURS['critical']);
        $this->assertEquals(8, Ticket::PRIORITY_SLA_HOURS['high']);
        $this->assertEquals(24, Ticket::PRIORITY_SLA_HOURS['medium']);
        $this->assertEquals(72, Ticket::PRIORITY_SLA_HOURS['low']);
    }

    public function test_status_transitions_are_correct(): void
    {
        $this->assertEquals(['in_progress'], Ticket::STATUS_TRANSITIONS['open']);
        $this->assertEquals(['resolved'], Ticket::STATUS_TRANSITIONS['in_progress']);
        $this->assertEquals(['closed', 'in_progress'], Ticket::STATUS_TRANSITIONS['resolved']);
        $this->assertEquals([], Ticket::STATUS_TRANSITIONS['closed']);
    }

    public function test_all_priorities_are_listed(): void
    {
        $this->assertCount(4, Ticket::PRIORITIES);
        $this->assertContains('low', Ticket::PRIORITIES);
        $this->assertContains('medium', Ticket::PRIORITIES);
        $this->assertContains('high', Ticket::PRIORITIES);
        $this->assertContains('critical', Ticket::PRIORITIES);
    }

    public function test_all_statuses_are_listed(): void
    {
        $this->assertCount(4, Ticket::STATUSES);
        $this->assertContains('open', Ticket::STATUSES);
        $this->assertContains('in_progress', Ticket::STATUSES);
        $this->assertContains('resolved', Ticket::STATUSES);
        $this->assertContains('closed', Ticket::STATUSES);
    }
}
