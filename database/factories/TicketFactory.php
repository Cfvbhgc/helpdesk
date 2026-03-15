<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $priority = fake()->randomElement(Ticket::PRIORITIES);
        $status = fake()->randomElement(Ticket::STATUSES);

        return [
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(2, true),
            'priority' => $priority,
            'status' => $status,
            'user_id' => User::factory(),
            'assigned_to' => null,
        ];
    }

    public function critical(): static
    {
        return $this->state(fn () => ['priority' => 'critical']);
    }

    public function open(): static
    {
        return $this->state(fn () => ['status' => 'open']);
    }

    public function withAssignee(): static
    {
        return $this->state(fn () => [
            'assigned_to' => User::factory()->agent(),
        ]);
    }
}
