<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'file_type',
        'category',
        'description',
        'tags',
        'uploaded_by',
        'event_name',
        'file_size',
    ];
}
