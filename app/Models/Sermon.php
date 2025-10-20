<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id',
        'title',
        'description',
        'speaker',
        'preacher',
        'sermon_date',
        'scripture_reference',
        'theme',
        'notes',
        'audio_file',
        'video_file',
        'notes_file',
        'summary',
        'duration',
        'views',
        'downloads',
        'is_published',
        'uploaded_by',
    ];

    protected $casts = [
        'sermon_date' => 'date',
        'is_published' => 'boolean',
    ];
}
