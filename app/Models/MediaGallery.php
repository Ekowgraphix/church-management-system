<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'name',
        'slug',
        'description',
        'cover_image',
        'event_id',
        'is_public',
        'allow_downloads',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'allow_downloads' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created this gallery
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the event this gallery is linked to
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all media files in this gallery
     */
    public function mediaFiles()
    {
        return $this->belongsToMany(MediaFile::class, 'gallery_media', 'gallery_id', 'media_file_id')
            ->withPivot('caption', 'photographer', 'sort_order')
            ->withTimestamps()
            ->orderBy('sort_order');
    }
}
