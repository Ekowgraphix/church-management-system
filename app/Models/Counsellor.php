<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counsellor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'status',
        'bio',
        'photo',
        'total_sessions',
        'rating',
    ];

    /**
     * Get all sessions for the counsellor
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(CounsellingSession::class);
    }

    /**
     * Get all follow-ups assigned to the counsellor
     */
    public function followups(): HasMany
    {
        return $this->hasMany(CounsellingFollowup::class);
    }

    /**
     * Scope: Get active counsellors
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active')->orderBy('name');
    }

    /**
     * Scope: Get available counsellors (active and not on leave)
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['Active'])->orderBy('total_sessions');
    }

    /**
     * Check if counsellor is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Increment session count
     */
    public function incrementSessions()
    {
        $this->increment('total_sessions');
    }

    /**
     * Get average rating display
     */
    public function getRatingStars(): string
    {
        return str_repeat('⭐', $this->rating);
    }
}
