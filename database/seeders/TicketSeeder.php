<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $agents = User::whereIn('role', ['agent', 'admin'])->get();

        $ticketData = [
            ['title' => 'Cannot login to my account', 'priority' => 'high', 'status' => 'open'],
            ['title' => 'Payment not processed correctly', 'priority' => 'critical', 'status' => 'in_progress'],
            ['title' => 'How to reset my password?', 'priority' => 'low', 'status' => 'resolved'],
            ['title' => 'Application crashes on file upload', 'priority' => 'critical', 'status' => 'open'],
            ['title' => 'Feature request: dark mode', 'priority' => 'low', 'status' => 'open'],
            ['title' => 'Email notifications not working', 'priority' => 'high', 'status' => 'in_progress'],
            ['title' => 'Billing discrepancy on invoice #1234', 'priority' => 'high', 'status' => 'open'],
            ['title' => 'Unable to export reports to PDF', 'priority' => 'medium', 'status' => 'resolved'],
            ['title' => 'Two-factor authentication setup issue', 'priority' => 'medium', 'status' => 'in_progress'],
            ['title' => 'API rate limit exceeded unexpectedly', 'priority' => 'high', 'status' => 'open'],
            ['title' => 'Mobile app display broken on iOS 17', 'priority' => 'medium', 'status' => 'open'],
            ['title' => 'Request for bulk user import feature', 'priority' => 'low', 'status' => 'closed'],
            ['title' => 'SSL certificate expiration warning', 'priority' => 'critical', 'status' => 'resolved'],
            ['title' => 'Dashboard loads slowly with large dataset', 'priority' => 'medium', 'status' => 'in_progress'],
            ['title' => 'Integration with Slack not syncing', 'priority' => 'high', 'status' => 'open'],
            ['title' => 'Permission error when accessing settings', 'priority' => 'medium', 'status' => 'open'],
            ['title' => 'Data export includes wrong date range', 'priority' => 'medium', 'status' => 'resolved'],
            ['title' => 'Account deletion request', 'priority' => 'low', 'status' => 'closed'],
            ['title' => 'Webhook delivery failures', 'priority' => 'high', 'status' => 'in_progress'],
            ['title' => 'Search results not relevant', 'priority' => 'low', 'status' => 'open'],
        ];

        foreach ($ticketData as $data) {
            $ticket = Ticket::create([
                'title' => $data['title'],
                'description' => fake()->paragraphs(2, true),
                'priority' => $data['priority'],
                'status' => $data['status'],
                'user_id' => $users->random()->id,
                'assigned_to' => in_array($data['status'], ['in_progress', 'resolved'])
                    ? $agents->random()->id
                    : ($data['status'] === 'open' && rand(0, 1) ? $agents->random()->id : null),
            ]);

            // Add 1-3 replies per ticket
            $replyCount = rand(1, 3);
            for ($i = 0; $i < $replyCount; $i++) {
                TicketReply::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => collect([$ticket->user_id, $agents->random()->id])->random(),
                    'content' => fake()->paragraph(),
                    'created_at' => $ticket->created_at->addHours(rand(1, 48)),
                ]);
            }
        }
    }
}
