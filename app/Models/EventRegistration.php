<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'registered_at',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
