<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uploaded_by',
        'title',
        'description',
        'type',
        'file_name',
        'file_path',
        'file_url',
        'mime_type',
        'file_size',
        'storage_provider',
        'metadata',
        'tags',
        'category',
        'event_id',
        'views_count',
        'downloads_count',
        'thumbnail_path',
        'is_public',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this file
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the event this file is linked to
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get galleries this file belongs to
     */
    public function galleries()
    {
        return $this->belongsToMany(MediaGallery::class, 'gallery_media', 'media_file_id', 'gallery_id')
            ->withPivot('caption', 'photographer', 'sort_order')
            ->withTimestamps();
    }
}
