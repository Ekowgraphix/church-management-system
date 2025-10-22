<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaEventAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'assigned_to',
        'assigned_by',
        'role',
        'notes',
        'status',
        'notification_sent',
        'completed_at',
    ];

    protected $casts = [
        'notification_sent' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the event for this assignment
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user assigned to this event
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who made the assignment
     */
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
