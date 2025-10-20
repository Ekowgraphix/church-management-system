<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CounsellingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'counsellor_id',
        'category_id',
        'member_name',
        'member_contact',
        'counsellor_name',
        'topic',
        'session_date',
        'session_time',
        'session_type',
        'duration',
        'notes',
        'outcome',
        'rating',
        'follow_up_date',
        'requires_followup',
        'status',
        'is_confidential',
        'access_level',
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_time' => 'datetime',
        'follow_up_date' => 'date',
        'is_confidential' => 'boolean',
        'requires_followup' => 'boolean',
    ];

    /**
     * Get the member associated with the session
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the counsellor for the session
     */
    public function counsellor(): BelongsTo
    {
        return $this->belongsTo(Counsellor::class);
    }

    /**
     * Get the category for the session
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CounsellingCategory::class);
    }

    /**
     * Get all follow-ups for the session
     */
    public function followups(): HasMany
    {
        return $this->hasMany(CounsellingFollowup::class, 'session_id');
    }

    /**
     * Get the notes for the session
     */
    public function sessionNotes(): HasOne
    {
        return $this->hasOne(CounsellingNote::class, 'session_id');
    }

    /**
     * Scope: Get upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>=', now())
                    ->where('status', 'pending')
                    ->orderBy('session_date');
    }

    /**
     * Scope: Get completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('session_date', 'desc');
    }

    /**
     * Scope: Get sessions requiring follow-up
     */
    public function scopeNeedsFollowup($query)
    {
        return $query->where('requires_followup', true)
                    ->where('status', 'completed')
                    ->whereNotNull('follow_up_date');
    }

    /**
     * Scope: Get confidential sessions (only authorized users)
     */
    public function scopeConfidential($query)
    {
        return $query->where('is_confidential', true);
    }

    /**
     * Check if session is past
     */
    public function isPast(): bool
    {
        return $this->session_date->isPast();
    }

    /**
     * Check if follow-up is due
     */
    public function isFollowupDue(): bool
    {
        return $this->follow_up_date && $this->follow_up_date->lte(now());
    }

    /**
     * Get session duration in hours
     */
    public function getDurationInHours(): float
    {
        return $this->duration / 60;
    }
}
