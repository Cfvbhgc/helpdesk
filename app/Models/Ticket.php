<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'sla_deadline',
        'user_id',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'sla_deadline' => 'datetime',
        ];
    }

    /**
     * Priority levels with their SLA hours.
     */
    public const PRIORITY_SLA_HOURS = [
        'critical' => 4,
        'high'     => 8,
        'medium'   => 24,
        'low'      => 72,
    ];

    public const PRIORITIES = ['low', 'medium', 'high', 'critical'];
    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];

    /**
     * Allowed status transitions.
     */
    public const STATUS_TRANSITIONS = [
        'open'        => ['in_progress'],
        'in_progress' => ['resolved'],
        'resolved'    => ['closed', 'in_progress'],
        'closed'      => [],
    ];

    /**
     * Auto-set SLA deadline based on priority when creating a ticket.
     */
    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->sla_deadline) && isset(self::PRIORITY_SLA_HOURS[$ticket->priority])) {
                $hours = self::PRIORITY_SLA_HOURS[$ticket->priority];
                $ticket->sla_deadline = Carbon::now()->addHours($hours);
            }

            if (empty($ticket->status)) {
                $ticket->status = 'open';
            }
        });
    }

    /**
     * Check if the SLA deadline has been breached.
     */
    public function isSlaBreached(): bool
    {
        if (!$this->sla_deadline) {
            return false;
        }

        if (in_array($this->status, ['resolved', 'closed'])) {
            return false;
        }

        return Carbon::now()->isAfter($this->sla_deadline);
    }

    /**
     * Get remaining SLA time as a human-readable string.
     */
    public function getSlaRemainingAttribute(): ?string
    {
        if (!$this->sla_deadline) {
            return null;
        }

        if ($this->isSlaBreached()) {
            return 'Breached';
        }

        return Carbon::now()->diffForHumans($this->sla_deadline, ['parts' => 2]);
    }

    /**
     * Check if a status transition is valid.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::STATUS_TRANSITIONS[$this->status] ?? []);
    }

    /**
     * Transition ticket to a new status.
     */
    public function transitionTo(string $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;
        return $this->save();
    }

    /**
     * The user who created the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The agent/admin assigned to the ticket.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Replies on this ticket.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    /**
     * Scope: filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: filter by priority.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: tickets with breached SLA.
     */
    public function scopeSlaBreached($query)
    {
        return $query->where('sla_deadline', '<', Carbon::now())
                     ->whereNotIn('status', ['resolved', 'closed']);
    }

    /**
     * Get priority badge color for Tailwind CSS.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'critical' => 'red',
            'high'     => 'orange',
            'medium'   => 'yellow',
            'low'      => 'green',
            default    => 'gray',
        };
    }

    /**
     * Get status badge color for Tailwind CSS.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open'        => 'blue',
            'in_progress' => 'yellow',
            'resolved'    => 'green',
            'closed'      => 'gray',
            default       => 'gray',
        };
    }
}
