<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'requester_name',
        'title',
        'description',
        'category',
        'status',
        'is_public',
        'privacy_level',
        'is_urgent',
        'notify_prayer_chain',
        'requested_by',
        'assigned_to',
        'answered_at',
        'resolved_at',
        'answer_testimony',
        'prayer_count',
        'last_prayed_at',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'is_public' => 'boolean',
        'notify_prayer_chain' => 'boolean',
        'answered_at' => 'datetime',
        'resolved_at' => 'datetime',
        'last_prayed_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function responses()
    {
        return $this->hasMany(PrayerRequestResponse::class);
    }

    public function prayerChainMembers()
    {
        return $this->belongsToMany(PrayerChainMember::class, 'prayer_chain_request')
            ->withPivot('notified', 'notified_at', 'prayed', 'prayed_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered')->whereNotNull('answered_at');
    }
}
