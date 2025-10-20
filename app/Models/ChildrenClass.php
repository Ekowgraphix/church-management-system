<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChildrenClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age_group',
        'min_age',
        'max_age',
        'capacity',
        'current_enrollment',
        'teacher_id',
        'room_location',
        'meeting_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ChildrenAttendance::class, 'class_id');
    }

    /**
     * Check if class is full
     */
    public function isFull()
    {
        return $this->current_enrollment >= $this->capacity;
    }

    /**
     * Get available spots
     */
    public function availableSpots()
    {
        return max(0, $this->capacity - $this->current_enrollment);
    }
}
