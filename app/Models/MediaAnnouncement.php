<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAnnouncement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'title',
        'content',
        'platforms',
        'image_path',
        'video_path',
        'status',
        'scheduled_at',
        'published_at',
        'analytics',
        'is_urgent',
    ];

    protected $casts = [
        'platforms' => 'array',
        'analytics' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'is_urgent' => 'boolean',
    ];

    /**
     * Get the user who created this announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
