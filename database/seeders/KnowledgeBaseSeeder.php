<?php

namespace Database\Seeders;

use App\Models\KnowledgeBase;
use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Getting Started with HelpDesk',
                'category' => 'Getting Started',
                'content' => "## Welcome to HelpDesk\n\nThis guide will help you get started with our support platform.\n\n### Creating Your First Ticket\n\n1. Navigate to the Tickets section\n2. Click \"Create New Ticket\"\n3. Fill in the title and description\n4. Select the appropriate priority level\n5. Submit your ticket\n\n### Priority Levels\n\n- **Critical** — System down, SLA: 4 hours\n- **High** — Major feature broken, SLA: 8 hours\n- **Medium** — Minor issue, SLA: 24 hours\n- **Low** — General question, SLA: 72 hours",
            ],
            [
                'title' => 'Understanding SLA Policies',
                'category' => 'Getting Started',
                'content' => "## SLA (Service Level Agreement)\n\nOur SLA policies ensure timely response and resolution of your issues.\n\n### Response Times\n\nEach priority level has a defined response time:\n\n| Priority | Response Time |\n|----------|---------------|\n| Critical | 4 hours |\n| High | 8 hours |\n| Medium | 24 hours |\n| Low | 72 hours |\n\n### Escalation Process\n\nIf an SLA deadline is approaching, the ticket is automatically flagged for escalation.",
            ],
            [
                'title' => 'How to Reset Your Password',
                'category' => 'Account',
                'content' => "## Password Reset Guide\n\n### Step-by-step Instructions\n\n1. Go to the login page\n2. Click \"Forgot Password?\"\n3. Enter your email address\n4. Check your inbox for the reset link\n5. Click the link and set a new password\n\n### Password Requirements\n\n- Minimum 8 characters\n- At least one uppercase letter\n- At least one number\n- At least one special character",
            ],
            [
                'title' => 'Managing Your Account Settings',
                'category' => 'Account',
                'content' => "## Account Settings\n\nManage your profile, notifications, and security settings.\n\n### Profile Settings\n\n- Update your name and email\n- Change your avatar\n- Set your timezone\n\n### Notification Preferences\n\n- Email notifications for ticket updates\n- Browser push notifications\n- Daily digest option\n\n### Security\n\n- Change password\n- Enable two-factor authentication\n- View login history",
            ],
            [
                'title' => 'Understanding Billing and Invoices',
                'category' => 'Billing',
                'content' => "## Billing Overview\n\n### Viewing Invoices\n\nAll invoices are available in Settings > Billing.\n\n### Payment Methods\n\nWe accept:\n- Credit/debit cards (Visa, Mastercard, Amex)\n- PayPal\n- Bank transfers (Enterprise plans)\n\n### Refund Policy\n\nRefunds are processed within 5-10 business days.",
            ],
            [
                'title' => 'API Documentation Overview',
                'category' => 'Technical',
                'content' => "## API Overview\n\nOur REST API allows programmatic access to HelpDesk features.\n\n### Authentication\n\nUse Bearer token authentication:\n\n```\nAuthorization: Bearer your-api-token\n```\n\n### Endpoints\n\n- `GET /api/tickets` — List all tickets\n- `POST /api/tickets` — Create a ticket\n- `GET /api/tickets/{id}` — Get ticket details\n- `PUT /api/tickets/{id}` — Update a ticket\n\n### Rate Limits\n\n- 100 requests per minute\n- 10,000 requests per day",
            ],
            [
                'title' => 'Troubleshooting Common Issues',
                'category' => 'FAQ',
                'content' => "## Frequently Asked Questions\n\n### I can't log in to my account\n\n- Verify your email and password\n- Check if Caps Lock is on\n- Try resetting your password\n- Clear browser cache and cookies\n\n### My ticket hasn't been answered\n\nCheck the SLA time for your ticket's priority. Critical tickets are handled first.\n\n### How do I change my email?\n\nGo to Settings > Profile and update your email. You'll need to verify the new address.",
            ],
            [
                'title' => 'Setting Up Integrations',
                'category' => 'Technical',
                'content' => "## Integration Setup\n\n### Slack Integration\n\n1. Go to Settings > Integrations\n2. Click \"Connect Slack\"\n3. Authorize the app in your Slack workspace\n4. Select the channel for notifications\n\n### Webhook Configuration\n\nSet up webhooks to receive real-time events:\n\n- Ticket created\n- Ticket updated\n- Reply added\n- SLA breached\n\nWebhook URL format: `https://your-app.com/webhooks/helpdesk`",
            ],
        ];

        foreach ($articles as $article) {
            KnowledgeBase::create([
                'title' => $article['title'],
                'content' => $article['content'],
                'category' => $article['category'],
                'published' => true,
            ]);
        }
    }
}
