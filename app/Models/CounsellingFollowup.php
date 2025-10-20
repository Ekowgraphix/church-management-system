<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounsellingFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'counsellor_id',
        'follow_up_date',
        'priority',
        'status',
        'notes',
        'reminder_sent',
        'completed_at',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'reminder_sent' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the session for this follow-up
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(CounsellingSession::class);
    }

    /**
     * Get the counsellor for this follow-up
     */
    public function counsellor(): BelongsTo
    {
        return $this->belongsTo(Counsellor::class);
    }

    /**
     * Scope: Get pending follow-ups
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending')
                    ->orderBy('follow_up_date');
    }

    /**
     * Scope: Get overdue follow-ups
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'Pending')
                    ->where('follow_up_date', '<', now());
    }

    /**
     * Scope: Get high priority follow-ups
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['High', 'Urgent'])
                    ->where('status', 'Pending');
    }

    /**
     * Mark follow-up as completed
     */
    public function markCompleted()
    {
        $this->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if follow-up is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'Pending' && $this->follow_up_date->isPast();
    }
}
