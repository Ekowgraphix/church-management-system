<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'requires_registration',
        'registration_fee',
        'status',
        'image',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'requires_registration' => 'boolean',
        'registration_fee' => 'decimal:2',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(Member::class, 'event_registrations')
            ->withPivot('status', 'registered_at', 'attended_at', 'notes')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getAttendanceCountAttribute()
    {
        return $this->registrations()->where('status', 'attended')->count();
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->capacity) return null;
        return $this->capacity - $this->registration_count;
    }

    public function isFullyBooked()
    {
        if (!$this->capacity) return false;
        return $this->registration_count >= $this->capacity;
    }

    public function isMemberRegistered($memberId)
    {
        return $this->registrations()
            ->where('member_id', $memberId)
            ->exists();
    }
}
