<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLivestream extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'started_by',
        'title',
        'description',
        'platform',
        'stream_key',
        'stream_url',
        'youtube_video_id',
        'facebook_video_id',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'peak_viewers',
        'total_views',
        'stream_notes',
        'recording_url',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the service for this livestream
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the user who started this stream
     */
    public function startedBy()
    {
        return $this->belongsTo(User::class, 'started_by');
    }
}
