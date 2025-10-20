<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmallGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'leader_id',
        'meeting_day',
        'meeting_time',
        'location',
        'max_members',
        'is_active',
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function leader()
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'small_group_members')
            ->withPivot('joined_date', 'role', 'is_active')
            ->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivot('is_active', true);
    }

    public function attendance()
    {
        return $this->hasMany(SmallGroupAttendance::class);
    }

    public function getMemberCountAttribute()
    {
        return $this->activeMembers()->count();
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_members) return null;
        return $this->max_members - $this->member_count;
    }

    public function isFull()
    {
        if (!$this->max_members) return false;
        return $this->member_count >= $this->max_members;
    }

    public function hasMember($memberId)
    {
        return $this->activeMembers()->where('small_group_members.member_id', $memberId)->exists();
    }
}
