<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerChainMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone',
        'notification_preference',
        'is_active',
        'prayers_received',
        'last_notified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function prayerRequests()
    {
        return $this->belongsToMany(PrayerRequest::class, 'prayer_chain_request')
            ->withPivot('notified', 'notified_at', 'prayed', 'prayed_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
